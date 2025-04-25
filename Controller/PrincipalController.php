<?php
// PROTOTIPO/Controller/RedirectController.php
session_start();

require ('../Model/Conexion.php');
require ('Constants.php');

//echo $user;
//Verificar sesión activa
if (!isset($_SESSION['usuario']['login'])) {
    require ('../index.php');
    exit();
}

$con = new Conexion();


$error=$_SESSION['error']??'';
$mensaje=$_SESSION['mensaje']??'';

require('Location: ../Views/Wellcome.php');




exit();
?>