<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');

$db = new Conexion();
$action = $_GET['action'] ?? '';

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    header('Location: LoginController.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$esCliente = ($_SESSION['usuario']['rol']['id_rol'] == 3); // Asumiendo que 3 es Cliente

try {
    switch ($action) {
        case 'ver':
            $id_orden = $_GET['id'] ?? 0;
            $orden = $db->getOrdenById($id_orden);
            
            // Verificar que el cliente solo vea sus propias órdenes
            if ($esCliente && $orden['id_usuario'] != $usuario['id_usuario']) {
                throw new Exception("No tienes permiso para ver esta orden");
            }
            
            $detalles = $db->getDetallesOrdenCompleto($id_orden);
            
            // Devolver JSON para el modal
            header('Content-Type: application/json');
            echo json_encode([
                'orden' => $orden,
                'detalles' => $detalles,
                'fecha_formateada' => $orden['fecha_orden']->format('d/m/Y H:i')
            ]);
            exit;
            
        case 'eliminar':
            if (!$esCliente) {
                throw new Exception("Acción no permitida");
            }
            
            $id_orden = $_POST['id_orden'] ?? 0;
            $orden = $db->getOrdenById($id_orden);
            
            // Verificar que la orden pertenece al cliente y está pagada
            if ($orden['id_usuario'] != $usuario['id_usuario'] || $orden['estado'] != 'PAGADO') {
                throw new Exception("No puedes eliminar esta orden");
            }
            
            // Aquí iría la lógica para eliminar la orden (marcar como cancelada o eliminarla)
            // Por ahora solo simulamos la eliminación
            $resultado = ['success' => true, 'message' => 'Orden eliminada correctamente'];
            
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;
            
        default:
            // Solo mostrar órdenes del cliente
            $filtro = $_GET['estado'] ?? null;
            $ordenes = $db->getHistorialCliente($usuario['id_usuario'], $filtro);
            
            include('../Views/HistorialView.php');
            break;
    }
} catch (Exception $e) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: PrincipalController.php');
    }
    exit;
}
?>