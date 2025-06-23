<?php
session_start();
require_once '../Model/Conexion.php';
require 'Constants.php';

if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para ver esto.";
    $_SESSION['alerta'] = "alert-danger";
    header("Location: ../index.php");
    exit;
}

$conexion = new Conexion();

// Obtener ID del vendedor desde la URL
$id_vendedor = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


// Obtener datos del vendedor
$vendedor = $conexion->getUser($id_vendedor); // Asegúrate de tener este método

// Obtener todas las valoraciones del vendedor
$valoraciones = $conexion->getValoracionesByVendedor($id_vendedor);
$promedio = $conexion->getPromedioValoraciones($id_vendedor);

// Verificar rol del usuario actual
$id_usuario_actual = $_SESSION['usuario']['id_usuario'];
$es_cliente = false;

// Aquí debes asegurarte de obtener el rol correctamente
$rol_usuario = $conexion->getRolUsuario($id_usuario_actual); // Crea este método en Conexion.php
if ($rol_usuario && $rol_usuario['id_rol'] == 3) { // Suponiendo que 3 es CLIENTE
    $es_cliente = true;
}

include '../Views/ValoracionesView.php';