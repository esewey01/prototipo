<?php
session_start();

require('../Model/Conexion.php');
require('Constants.php');

// Verificar sesión
if (!isset($_SESSION['usuario']['login'])) {
    header("Location: ../index.php");
    exit();
}

$urlViews = URL_VIEWS;
$alerta = $_SESSION['alerta'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';



try {
    $con = new Conexion();
    if (!$con) throw new Exception("Error de conexión");
    //obtener el rol del usuario actual
    $rolUsuario = $con->getRolUser($_SESSION['usuario']['id_usuario']);
    // Obtener información del usuario logueado
    $currentUser = $con->getUserWithRole($_SESSION['usuario']['login']);
    
    // Verificar si es super usuario
    $isSuperUser = ($rolUsuario['id_rol'] == 1);
    
    // Obtener usuarios según roles
    $administradores = $con->getUsersByRoleType('ADMINISTRADOR');
    $vendedores = $con->getUsersByRoleType('VENDEDOR');
    $clientes = $con->getUsersByRoleType('CLIENTE');

    // Preparar datos para la vista
    $viewData = [
        'usuario' => $_SESSION['usuario'],
        'nombres' => $_SESSION['usuario']['nombre'] ?? 'Usuario',
        'foto' => $_SESSION['usuario']['foto_perfil'] ?? 'user.png',
        'isSuperUser' => $isSuperUser,
        'administradores' => $administradores,
        'vendedores' => $vendedores,
        'clientes' => $clientes,
        'rolActual' => $rolUsuario['nombre_rol']
    ];

    // Cargar vista
    require("../Views/UsuarioViewFinal.php");
    unset($_SESSION['alerta'], $_SESSION['mensaje']); // Limpiar después de mostrar
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
