<?php
// PROTOTIPO/Controller/RedirectController.php
session_start();

require ('../Model/Conexion.php');
require ('Constants.php');
$user= $_SESSION['login'];
//echo $user;
//Verificar sesión activa
if (!isset($_SESSION['login'])) {
    require ('../index.php');
    exit();
}

$con = new Conexion();


$urlViews = URL_VIEWS;

// Redirigir según tipo de usuario
switch (strtolower($usuario['nombre_rol'])) {
    case 'administrador':
        $mensaje = "ADMINISTRADOR: " . $usuario['nombre'];
        require('../Views/Wellcome.php');
        break;
    case 'vendedor':
        $mensaje = "VENDEDOR: " . $usuario['nombre'];
        require('../Views/WellcomeVendedor.php');
        break;
    case 'cliente':
        $mensaje = "CLIENTE: " . $usuario['nombre'];
        require('../Views/WellcomeCliente.php');
        break;
    default:
        $errores = "Rol no reconocido";
        mostrarMensaje($errores);
        require('../Views/LoginView.php');
}
exit();
?>