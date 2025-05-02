<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

$action = $_GET['action'] ?? '';
$db = new Conexion();
/*
if ($_SESSION['usuario']['rol']['id_rol'] != 1) {
    $_SESSION['error'] = "NO POSEES PERMISOS DE ADMINISTRADOR";
    header("Location: PrincipalController.php");
    exit();
}
*/

$con = new Conexion();
try {
    $reportes = $con->getReportes();

    $error = $_SESSION['error'] ?? '';
    $mensaje = $_SESSION['mensaje'] ?? '';


    //PREPARANDO DATOS 
    $data = [
        'reportes' => $reportes,

        'usuario' => $_SESSION['usuario']['login'],
        'password' => $_SESSION['usuario']['password']
    ];

    //LIMPIAR MENSAJES
    unset($_SESSION['mensaje']);
    unset($_SESSION['error']);

    require("../Views/ReportesRegistrados.php");
} catch (Exception $e) {
    $_SESSION['error'] = "ERROR AL OBTENER REPORTES: " . $e->getMessage();
    header("Location ../Index.php");
    exit();
}
