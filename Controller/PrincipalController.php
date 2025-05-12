<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../Model/Conexion.php');
require('Constants.php');

if (!isset($_SESSION['usuario']['login'])) {
    require ('../index.php');
    exit();
}

$con = new Conexion();

// Obtener datos para el dashboard
$id_usuario = $_SESSION['usuario']['id_usuario'];

// 1. Pagos pendientes (órdenes con estado PENDIENTE)
$pagos_pendientes = $con->getHistorialCliente($id_usuario, 'PENDIENTE');

// 2. Productos nuevos (últimos 5 productos agregados)
$productos_nuevos = $con->getProductosByCategoria();

// 3. Gastos del cliente (total de órdenes pagadas)
$gastos_totales = 0;
$ordenes_pagadas = $con->getHistorialCliente($id_usuario, 'PAGADO');
foreach ($ordenes_pagadas as $orden) {
    $gastos_totales += $orden['total'];
}

// 4. Productos en carrito
$cantidad_carrito = $con->obtenerCantidadTotalCarrito($id_usuario);

require('../Views/Wellcome.php');
unset($_SESSION['error']);
unset($_SESSION['mensaje']);
exit();
?>