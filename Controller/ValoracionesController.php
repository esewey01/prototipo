<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

// Verificar sesión más robustamente
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    error_log("Intento de acceso no autorizado a ValoracionesController");
    header("Location: ../views/LoginView.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id_usuario'];
error_log("Usuario ID en sesión: " . $id_usuario);

$conexion = new Conexion();

// Depuración de productos por valorar
error_log("Obteniendo productos por valorar...");
$productosPorValorar = $conexion->getProductosCompradosPorValorar($id_usuario);
error_log("Productos por valorar obtenidos: " . count($productosPorValorar));

// Depuración de otros datos
$productosValorados = $conexion->getProductosValorados($id_usuario);
error_log("Productos valorados obtenidos: " . count($productosValorados));

$vendedoresPorValorar = $conexion->getVendedoresPorValorar($id_usuario);
error_log("Vendedores por valorar obtenidos: " . count($vendedoresPorValorar));

$vendedoresValorados = $conexion->getVendedoresValorados($id_usuario);
error_log("Vendedores valorados obtenidos: " . count($vendedoresValorados));

// Al inicio del script
function debug_log($message) {
    error_log($message);
    echo "<script>console.log('PHP: " . addslashes($message) . "');</script>";
}

// Luego usa debug_log en lugar de error_log para ver en ambos lugares
debug_log("Usuario ID en sesión: " . $_SESSION['usuario']['id_usuario']);

// Verificar si hay errores en la conexión
if ($conexion->getConnection() === false) {
    error_log("Error de conexión a la base de datos");
    die("Error de conexión a la base de datos");
}

$viewData = [
    'productosPorValorar' => $productosPorValorar,
    'productosValorados' => $productosValorados,
    'vendedoresPorValorar' => $vendedoresPorValorar,
    'vendedoresValorados' => $vendedoresValorados
];

include_once __DIR__ . '/../views/ValoracionesView.php';

