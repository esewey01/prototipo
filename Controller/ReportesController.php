<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

$action = $_GET['action'] ?? '';
$db = new Conexion();

// Verificar permisos (descomentar cuando esté listo)
/*
if ($_SESSION['usuario']['rol']['id_rol'] != 1) {
    $_SESSION['error'] = "NO POSEES PERMISOS DE ADMINISTRADOR";
    header("Location: PrincipalController.php");
    exit();
}
*/

try {
    // Obtener todos los reportes incluyendo los de tipo ORDEN
    $reportes = $db->getReportes();
    
    // reportes relacionados por prodcutos
    $reportesProductos = array_filter($reportes, function($r) { return $r['tipo_reporte'] == 'PRODUCTO'; });
    // reportes relacionados hacia vendedores
    $reportesVendedores = array_filter($reportes, function($r) { return $r['tipo_reporte'] == 'VENDEDOR'; });
    // reportes relacionados hacia usuarios
    $reportesUsuarios = array_filter($reportes, function($r) { return $r['tipo_reporte'] == 'USUARIO'; });
    //reportes relacionados hacia ordenes de clientes
    $reportesOrdenes = array_filter($reportes, function($r) { return $r['tipo_reporte'] == 'ORDEN'; });

    $data = [
        'reportesProductos' => $reportesProductos,
        'reportesVendedores' => $reportesVendedores,
        'reportesUsuarios' => $reportesUsuarios,
        'reportesOrdenes' => $reportesOrdenes,
        'usuario' => $_SESSION['usuario']['login']
    ];

    // Limpiar mensajes
    unset($_SESSION['mensaje']);
    unset($_SESSION['error']);

    require("../Views/ReportesRegistrados.php");
} catch (Exception $e) {
    $_SESSION['error'] = "ERROR AL OBTENER REPORTES: " . $e->getMessage();
    header("Location: ../Index.php");
    exit();
}