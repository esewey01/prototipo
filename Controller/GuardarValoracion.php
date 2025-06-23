<?php
session_start();
require_once '../Model/Conexion.php';

if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensaje'] = "Debes iniciar sesión para valorar.";
    $_SESSION['alerta'] = "alert-danger";
    header("Location: ../index.php");
    exit;
}

$conexion = new Conexion();
$id_cliente = $_SESSION['usuario']['id_usuario'];
$id_vendedor = filter_input(INPUT_POST, 'id_vendedor', FILTER_VALIDATE_INT);
$calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_VALIDATE_INT);
$comentario = trim($_POST['comentario'] ?? '');

if (!$id_vendedor || !$calificacion || $calificacion < 1 || $calificacion > 5) {
    $_SESSION['mensaje'] = "Datos inválidos.";
    $_SESSION['alerta'] = "alert-danger";
    header("Location: ValoracionesView.php?id=$id_vendedor");
    exit;
}

// Verificar si ya valoró
if ($conexion->usuarioYaValoroAVendedor($id_cliente, $id_vendedor)) {
    $_SESSION['mensaje'] = "Ya has valorado a este vendedor.";
    $_SESSION['alerta'] = "alert-warning";
    header("Location: ValoracionesView.php?id=$id_vendedor");
    exit;
}

// Guardar valoración
if ($conexion->agregarValoracion($id_cliente, 1, $calificacion, $comentario)) {
    $_SESSION['mensaje'] = "Gracias por tu valoración.";
    $_SESSION['alerta'] = "alert-success";
} else {
    $_SESSION['mensaje'] = "Error al guardar tu valoración.";
    $_SESSION['alerta'] = "alert-danger";
}

header("Location: ValoracionesView.php?id=$id_vendedor");
exit;