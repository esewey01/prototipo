<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id_usuario'])) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para valorar.";
    $_SESSION['alerta'] = "alert-danger";
    header("Location: ValoracionesController.php");
    exit();
}

$id_usuario = $_SESSION['usuario']['id_usuario'];
$tipo_valoracion = filter_input(INPUT_POST, 'tipo_valoracion', FILTER_SANITIZE_STRING);
$calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 5]
]);
$comentario = filter_input(INPUT_POST, 'comentario', FILTER_SANITIZE_STRING);

include_once __DIR__ . '/../model/Conexion.php';
$conexion = new Conexion();

if ($tipo_valoracion === 'producto') {
    $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_VALIDATE_INT);

    if (!$id_producto) {
        $_SESSION['mensaje'] = "ID de producto inválido.";
        header("Location: ValoracionesController.php");
        exit();
    }

    if (!$conexion->verificarProductoComprado($id_usuario, $id_producto)) {
        $_SESSION['mensaje'] = "No puedes valorar este producto o no está pagado.";
        header("Location: ValoracionesController.php");
        exit();
    }

    if ($conexion->yaValoroProducto($id_usuario, $id_producto)) {
        $_SESSION['mensaje'] = "Ya has valorado este producto.";
        header("Location: ValoracionesController.php");
        exit();
    }

    if ($conexion->agregarValoracion($id_usuario, $id_producto, $calificacion, $comentario)) {
        $_SESSION['mensaje'] = "¡Gracias por tu valoración!";
        $_SESSION['alerta'] = "alert-success";
    } else {
        $_SESSION['mensaje'] = "Error al guardar la valoración.";
        $_SESSION['alerta'] = "alert-danger";
    }
} else {
    $_SESSION['mensaje'] = "Tipo de valoración no válido.";
    $_SESSION['alerta'] = "alert-danger";
}

header("Location: ValoracionesController.php");
exit();
