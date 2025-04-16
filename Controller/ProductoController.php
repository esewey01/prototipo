<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

// Verificar sesión y tipo de usuario
if (!isset($_SESSION['login'])){
    header("Location: /Prototipo/index.php");
    exit();
}

$con = new Conexion();
$id_usuario = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['id_tipo'];

try {
    // Obtener menú según tipo de usuario
    $menuUser = ($tipo_usuario == 1) ? $con->getMenuAdmin() : $con->getMenuVendedor();
    
    // Obtener productos
    if ($tipo_usuario == 2) { // Vendedor
        $productos = $con->getProductosByVendedor($id_usuario);
    } else { // Admin ve todos
        $productos = $con->getAllProducto();
    }

    // Obtener otros datos necesarios
    $tipoProductos = $con->getAllTipoProducto();
    
    // Variables para la vista
    $urlViews = URL_VIEWS;
    $userLogueado = $_SESSION['nombres'] ?? 'Usuario';
    $imageUser = $_SESSION['foto'] ?? 'default.png';

    // Cargar vista
    require("../Views/ProductoViews.php");
    
} catch (Exception $e) {
    die("Error en el sistema: " . $e->getMessage());
}