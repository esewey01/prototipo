<?php
session_start();

require('../Model/Conexion.php');
require('Constants.php');

// Verificar sesión y permisos
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol']['id_rol'] != 2) {
    $_SESSION['mensaje'] = "No eres un vendedor, no puedes acceder a esta sección";
    $_SESSION['alerta']= "alert-danger";
    header("Location: PrincipalController.php");
    exit();
}

try {
    $db = new Conexion();

    // Obtener el ID del vendedor desde la sesión
    $id_vendedor = $_SESSION['usuario']['id_usuario'];

    // Obtener clientes relacionados con órdenes pagadas del vendedor
    $clientes = $db->getClientesPorVendedor($id_vendedor);

    $data = [
        'clientes' => $clientes,
        'id_vendedor' => $id_vendedor
    ];

    require("../Views/ClienteViews.php");

} catch (Exception $e) {
    $_SESSION['error'] = "ERROR AL OBTENER CLIENTES: " . $e->getMessage();
    header("Location: ../Index.php");
    exit();
}