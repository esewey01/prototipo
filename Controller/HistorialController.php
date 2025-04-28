<?php
require ('../Model/Conexion.php');
require_once 'Constants.php';

try {
    $con = new Conexion();
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $id_carrito = $con->getOrCreateCarrito($id_usuario);
    $items = $con->getCarritoItems($id_carrito);

    include '../Views/HistorialView.php';
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}