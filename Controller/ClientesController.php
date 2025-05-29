<?php
session_start();
require('../Model/Conexion.php');

// Verificar permisos (descomentar cuando esté listo)
if ($_SESSION['usuario']['rol']['id_rol'] >= 2) {
    $_SESSION['error'] = "NO POSEES PERMISOS DE ADMINISTRADOR";
    header("Location: PrincipalController.php");
    exit();
}

try {
    $db = new Conexion();
    $id_vendedor = $_GET['id_vendedor'] ?? 0;

    // Obtener clientes relacionados con órdenes pagadas del vendedor
    $clientes = $db->getClientesPorVendedor($id_vendedor);

    $data = [
        'clientes' => $clientes,
        'id_vendedor' => $id_vendedor
    ];

    require("../Views/ClientesViews.php");
} catch (Exception $e) {
    $_SESSION['error'] = "ERROR AL OBTENER CLIENTES: " . $e->getMessage();
    header("Location: ../Index.php");
    exit();
}