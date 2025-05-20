<?php
session_start();
require_once('../Model/Conexion.php');

try {
    if (!isset($_SESSION['usuario'])) {
        throw new Exception("Acceso no autorizado");
    }

    $id_orden = $_GET['id'] ?? null;
    if (!$id_orden) {
        throw new Exception("ID de orden no especificado");
    }

    $conexion = new Conexion();
    $orden = $conexion->getOrdenById($id_orden);
    $detalles = $conexion->getDetalleOrden($id_orden);

    if (!$orden) {
        throw new Exception("Orden no encontrada");
    }

    // Mostrar solo si es una solicitud modal
    if (isset($_GET['modal'])) {
        include('../Views/VendedorDetalleOrdenView.php');
        exit();
    }

    // Si no es modal, redirigir o mostrar vista completa
    header('Location: VentasController.php');
    exit();

} catch (Exception $e) {
    error_log("Error en VendedorDetalleOrdenController: " . $e->getMessage());
    if (isset($_GET['modal'])) {
        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: VentasController.php');
    }
    exit();
}