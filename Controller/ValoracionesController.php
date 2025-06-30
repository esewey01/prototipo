<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    header("Location: ../views/LoginView.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id_usuario'];

include_once __DIR__ . '/../model/Conexion.php';
$conexion = new Conexion();

$productosPagados = $conexion->getProductosComprados($id_usuario);

include_once __DIR__ . '/../views/ValoracionesView.php';