<?php
session_start();
require_once('../Model/Conexion.php');

$db = new Conexion();
$action = $_GET['action'] ?? '';

// Para debug - quitar en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    switch ($action) {
        case 'procesarPago':
            if (!isset($_SESSION['usuario'])) {
                // Si es AJAX
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
                    exit;
                }
                // Si no es AJAX
                header('Location: ../Controller/LoginController.php');
                exit;
            }

            $id_usuario = $_SESSION['usuario']['id_usuario'];
            $id_carrito = $db->obtenerCarritoActivo($id_usuario);
            $total = $db->obtenerTotalCarrito($id_usuario);
            $direccion = $_POST['direccion'] ?? '';
            $comentarios = $_POST['comentarios'] ?? '';

            if ($total <= 0) {
                echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
                exit;
            }

            $id_orden = $db->crearOrden($id_usuario, $id_carrito, $total, $direccion, $comentarios);
            
            if ($id_orden) {
                $_SESSION['ultima_orden'] = $id_orden;
                
                // Si es petición AJAX
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    echo json_encode([
                        'success' => true,
                        'redirect' => '../Views/CheckoutView.php'
                    ]);
                } else {
                    // Redirección directa
                    header('Location: ../Views/CheckoutView.php');
                }
                exit;
            } else {
                throw new Exception("Error al crear la orden");
            }
            break;

        default:
            // Mostrar vista directamente si no es petición AJAX
            if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                if(isset($_SESSION['ultima_orden'])) {
                    header('Location: ../Views/CheckoutView.php');
                    exit;
                }
                header('Location: ../Controller/CarritoController.php');
            }
            break;

            case 'marcar_entregado':
                if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 2) {
                    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
                    exit;
                }
            
                $id_orden = $_POST['id_orden'] ?? 0;
                $db->executeNonQuery(
                    "UPDATE ORDENES SET estado = 'ENTREGADO', fecha_actualizacion = GETDATE() WHERE id_orden = ?",
                    [$id_orden]
                );
                
                // Notificar al cliente (ejemplo básico)
                $orden = $db->getOrdenById($id_orden);
                if ($orden) {
                    $db->executeNonQuery(
                        "INSERT INTO NOTIFICACIONES (id_usuario, titulo, mensaje) VALUES (?, ?, ?)",
                        [$orden['id_usuario'], 'Pedido entregado', 'Tu pedido #'.$id_orden.' ha sido marcado como entregado']
                    );
                }
                
                echo json_encode(['success' => true, 'message' => 'Orden marcada como entregada']);
                break;
    }
} catch (Exception $e) {
    error_log('Error en CheckoutController: ' . $e->getMessage());
    
    // Manejo de errores para AJAX
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode([
            'success' => false,
            'message' => 'Error al procesar el pago: ' . $e->getMessage()
        ]);
    } else {
        // Redirección con mensaje de error
        $_SESSION['error_checkout'] = $e->getMessage();
        header('Location: ../Controller/CarritoController.php');
    }
    exit;
}
?>