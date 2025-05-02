<?php

session_start();
require_once '../Model/Conexion.php';
require('Constants.php');

$action = $_GET['action'] ?? '';
$db = new Conexion();

try {
    switch ($action) {
        case 'agregar':
            if (!isset($_SESSION['usuario'])) {
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
                exit;
            }
            
            $id_producto = $_POST['id_producto'] ?? 0;
            $cantidad = $_POST['cantidad'] ?? 1; // Aquí recibimos la cantidad
            
            if ($id_producto <= 0) {
                echo json_encode(['success' => false, 'message' => 'Producto inválido']);
                exit;
            }
            
            $id_usuario = $_SESSION['usuario']['id_usuario'];
            $success = $db->agregarProductoCarrito($id_usuario, $id_producto, $cantidad);
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Producto añadido al carrito' : 'Error al agregar producto'
            ]);
            break;

        case 'contador':
            $total = 0;
            if (isset($_SESSION['usuario'])) {
                $total = $db->obtenerCantidadTotalCarrito($_SESSION['usuario']['id_usuario']);
            }
            echo json_encode(['total' => $total]);
            break;

        case 'ver':
            if (!isset($_SESSION['usuario'])) {
                header('Location: LoginController.php');
                exit;
            }

            $productos = $db->obtenerProductosCarrito($_SESSION['usuario']['id_usuario']);
            $total = $db->obtenerTotalCarrito($_SESSION['usuario']['id_usuario']);

            // Pasar las variables a la vista
            require '../Views/CarritoView.php';
            break;
        case 'actualizar':
            // Manejar actualización de cantidad
            if (!isset($_SESSION['usuario'])) {
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
                exit;
            }

            $id_detalle = $_POST['id_detalle'] ?? 0;
            $cantidad = $_POST['cantidad'] ?? 1;

            if ($id_detalle <= 0) {
                echo json_encode(['success' => false, 'message' => 'Ítem inválido']);
                exit;
            }

            $success = $db->actualizarCantidadCarrito($id_detalle, $cantidad);
            echo json_encode(['success' => $success]);
            break;

        case 'eliminar':
            // Manejar eliminación de ítem
            if (!isset($_SESSION['usuario'])) {
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
                exit;
            }

            $id_detalle = $_POST['id_detalle'] ?? 0;

            if ($id_detalle <= 0) {
                echo json_encode(['success' => false, 'message' => 'Ítem inválido']);
                exit;
            }

            $success = $db->eliminarProductoCarrito($id_detalle);
            echo json_encode(['success' => $success]);
            break;

        case 'vaciar':
            // Manejar vaciado de carrito
            if (!isset($_SESSION['usuario'])) {
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión']);
                exit;
            }

            $success = $db->vaciarCarrito($_SESSION['usuario']['id_usuario']);
            echo json_encode(['success' => $success]);
            break;

        default:
            // Redirigir al carrito si no se especifica acción
            header('Location: CarritoController.php?action=ver');
            exit;
    }
} catch (Exception $e) {
    // Registrar el error para depuración
    error_log('Error en CarritoController: ' . $e->getMessage());

    // Mostrar mensaje de error apropiado
    if (
        $action === 'agregar' || $action === 'contador' || $action === 'actualizar' ||
        $action === 'eliminar' || $action === 'vaciar'
    ) {
        echo json_encode(['success' => false, 'message' => 'Error en el servidor']);
    } else {
        $_SESSION['mensaje'] = 'Error al procesar tu solicitud';
        $_SESSION['alerta'] = 'alert-danger';
        header('Location: PrincipalController.php');
    }
    exit;
}
