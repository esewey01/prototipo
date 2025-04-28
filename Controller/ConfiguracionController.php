<?php 

if (session_status ()===PHP_SESSION_NONE){
    session_start();
}

require('../Model/Conexion.php');
require('Constants.php');



if(!isset($_SESSION['usuario']['login'])){
    $_SESSION['error']="Sesion no iniciada";
    require("../index.php");
    exit();
}

$urlViews = URL_VIEWS;
$alerta = $_SESSION['alerta'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';
$error=$_SESSION['error']??'';

try{
    $con= new Conexion();
    if(!$con) throw new Exception("Error de conexión");


    $_SESSION['mensaje'] = "Vsita aun no disponible";
    $_SESSION['alerta'] = "alert-success";
    

    require("../Views/ConfiguracionView.php");
    unset($_SESSION['error']);
    unset($_SESSION['mensaje']);
    unset($_SESSION['alerta']);
}catch(Exception $e){
    $_SESSION['error'] = $e->getMessage();
        $_SESSION['alerta'] = "alert-danger";
}


