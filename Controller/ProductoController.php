<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

// Verificar sesión y tipo de usuario
if (!isset($_SESSION['usuario']['login'])){
    header("Location: /Prototipo/index.php");
    exit();
}

$con = new Conexion();
// Variables para la vista
$urlViews = URL_VIEWS;
$mensaje = $_SESSION['mensaje'] ?? null;
$alerta = $_SESSION['alerta'] ?? null;
$usuario = $_SESSION['usuario']['login'];
$password = $_SESSION['usuario']['password'];
$id_usuario = $_SESSION['usuario']['id_usuario'];
$rol_usuario = $_SESSION['usuario']['rol']['nombre_rol'];



try {
    // Obtener productos según el rol
    if (strtolower($rol_usuario) === 'vendedor') {
        // Vendedor solo ve sus productos
        $productos = $con->getProductosByVendedor($id_usuario);
    } else {
        // Admin ve todos los productos con info del vendedor
        $productos = $con->getAllProductosWithVendedor();
    }

    // Obtener categorías para formularios
    $categorias = $con->getAllCategorias();
    
    // Preparar datos para la vista
    $data = [
        'productos' => $productos,
        'categorias' => $categorias,
        'urlViews' => URL_VIEWS,
        'userLogueado' => $_SESSION['usuario']['nombre'] ?? 'Usuario',
        'imageUser' => $_SESSION['usuario']['foto'] ?? 'default.png',
        'esAdministrador' => (strtolower($rol_usuario) === 'administrador'),
        'esVendedor' => (strtolower($rol_usuario) === 'vendedor')
    ];

    
    
    
    

    // Cargar vista
    require("../Views/ProductoViews.php");
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar productos: " . $e->getMessage();
    header("Location: Error.php");
    exit();
}