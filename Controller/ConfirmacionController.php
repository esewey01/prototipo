<?php
session_start();

if (!isset($_SESSION['orden_id'])) {
    header('Location: CarritoController.php');
    exit;
}

$orden_id = $_SESSION['orden_id'];
unset($_SESSION['orden_id']);

// Aquí podrías conectar a la base de datos para obtener más detalles
// $db = new Conexion();
// $orden = $db->getOrden($orden_id);

require_once('../Views/ConfirmacionView.php');
?>