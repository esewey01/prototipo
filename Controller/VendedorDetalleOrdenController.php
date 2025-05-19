<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');

$db = new Conexion();
$id_orden = $_GET['id'] ?? 0;
$isModal = isset($_GET['modal']);

// Verificar autenticación y rol de vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol']['id_rol'] != 2) {
    if ($isModal) {
        die(json_encode(['error' => 'No autorizado']));
    } else {
        header('Location: LoginController.php');
        exit;
    }
}

$vendedor_id = $_SESSION['usuario']['id_usuario'];

try {
    // Obtener la orden
    $orden = $db->getOrdenById($id_orden);
    
    // Verificar que la orden pertenece a este vendedor
    if (!$orden || $orden['id_vendedor'] != $vendedor_id) {
        throw new Exception("No tienes permiso para ver esta orden");
    }
    
    // Obtener detalles
    $detalles = $db->getDetallesOrdenCompleto($id_orden);
    
    if ($isModal) {
        // Solo incluir el contenido del modal sin layout
        ob_start();
        include('../Views/VendedorDetalleOrdenView.php');
        $content = ob_get_clean();
        echo $content;
        exit;
    } else {
        // Vista normal
        include('../Views/VendedorDetalleOrdenView.php');
    }
    
} catch (Exception $e) {
    if ($isModal) {
        die(json_encode(['error' => $e->getMessage()]));
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: VendedorOrdenesController.php');
        exit;
    }
}
?>