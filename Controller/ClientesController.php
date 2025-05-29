<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

$id_vendedor = $_SESSION['usuario']['id_usuario'];

// Verificar permisos (descomentar cuando esté listo)
if ($_SESSION['usuario']['rol']['id_rol'] === "2") {
    $_SESSION['mensaje'] = "NO POSEES PERMISOS DE ADMINISTRADOR";
    header("Location: PrincipalController.php");
    exit();
}

try {
    $db = new Conexion();
    $action = $_GET['action'] ?? ($_POST['action'] ?? '');

    if ($action === 'getDetalleCliente') {
    try {
        $id_cliente = $_GET['id'] ?? 0;
        
        if (!is_numeric($id_cliente)) {
            throw new Exception("ID de cliente no válido");
        }

        $cliente = $db->getDetalleCliente($id_cliente);
        
        if (!$cliente) {
            throw new Exception("Cliente no encontrado");
        }

        // Agregar redes sociales si es necesario
        $cliente['redes'] = $db->getSocialNetworks($id_cliente);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'cliente' => $cliente
        ]);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
    exit();
}
    // Obtener clientes relacionados con órdenes pagadas del vendedor
    $clientes = $db->getClientesPorVendedor($id_vendedor);

    $data = [
        'clientes' => $clientes,
        'id_vendedor' => $id_vendedor
    ];

    require("../Views/ClienteViews.php");
} catch (Exception $e) {
    if ($action === 'getDetalleCliente') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit();
    } else {
        $_SESSION['error'] = "ERROR AL OBTENER CLIENTES: " . $e->getMessage();
        header("Location: ../Index.php");
        exit();
    }
}
