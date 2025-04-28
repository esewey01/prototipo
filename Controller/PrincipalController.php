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


require('../Views/Wellcome.php');
unset($_SESSION['error']);
unset($_SESSION['mensaje']);
exit();
?>