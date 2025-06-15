<?php 
session_start();
require_once '../Model/Conexion.php';
require 'Constants.php';

if (!isset($_SESSION['usuario'])){
    $_SESSION['mensaje']= "OCURRIO UN ERROR AL CARGAR LOS REPORTES";
    $_SESSION['alerta'] = "alert-danger";
    header(
        "Location: PrincipalController.php"
    );
    exit;
}


try {
    $conexion = new Conexion();
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    //obtener los reportes del usuario
    $reportes = $conexion->getReportesPorUsuario($id_usuario);
    require '../Views/MisReportesView.php';

}
catch(Exception $e) {
    session_start();
    $_SESSION['mensaje'] = "Error al cargar los reportes: " . $e->getMessage();
    $_SESSION['alerta'] = "alert-danger";
    header("Location: PrincipalController.php");
    exit;
}

