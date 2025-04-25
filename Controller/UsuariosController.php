<?php
if (session_status ()===PHP_SESSION_NONE){
    session_start();
}

require_once('../Model/Conexion.php');
require('Constants.php');


// Verificar sesión
if (!isset($_SESSION['usuario']['login'])) {
    die('Sesion no iniciada');
    require(" ../index.php");
    exit();
}

$urlViews = URL_VIEWS;
$alerta = $_SESSION['alerta'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';
$error=$_SESSION['error']??'';



try {
    $con = new Conexion();
    if (!$con) throw new Exception("Error de conexión");
    $roles=$con->getRoles();
    $_SESSION['usuario']['login'];
    $_SESSION['nombre_rol']=$roles;
    //obtener el rol del usuario actual
    $rolUsuario = $con->getRolUser($_SESSION['usuario']['id_usuario']);
    // Obtener información del usuario logueado
    $currentUser = $con->getUserWithRole($_SESSION['usuario']['login']);
    
    // Verificar si es super usuario
    $isSuperUser = ($rolUsuario['id_rol'] == 0);
    
    // Obtener usuarios según roles
    $administradores = $con->getUsersByRoleType('ADMINISTRADOR');
    $vendedores = $con->getUsersByRoleType('VENDEDOR');
    $clientes = $con->getUsersByRoleType('CLIENTE');

    // Preparar datos para la vista
    $viewData = [
        'usuario' => $_SESSION['usuario'],
        'nombres' => $_SESSION['usuario']['nombre'] ,
        'foto' => $_SESSION['usuario']['foto_perfil'] ?? 'user.png',
        'isSuperUser' => $isSuperUser,
        'administradores' => $administradores,
        'vendedores' => $vendedores,
        'clientes' => $clientes,
        'rolActual' => $rolUsuario['nombre_rol']
    ];

    // Cargar vista
    require("../Views/UsuarioViewFinal.php");
    unset($_SESSION['error']);
    unset($_SESSION['mensaje']);
    unset($_SESSION['alerta']);
   
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
