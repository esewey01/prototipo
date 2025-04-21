<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

$alerta = $_SESSION['alerta'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensaje'] = "Acceso no autorizado";
    $_SESSION['alerta'] = "alert-danger";
    header('Location: ../Views/LoginView.php');
    exit();
}

// OBTENER DATOS DEL FORMULARIO
$urlViews = URL_VIEWS;
$login = trim($_POST['login']);
$password = trim($_POST['password']);
$con = new Conexion();


// VALIDACIÓN DE CAMPOS VACÍOS
if (empty($login) || empty($password)) {
    $_SESSION['mensaje'] = "Ningún campo debe estar vacío";
    $_SESSION['alerta'] = "alert-danger";
    header('Location: ../Views/LoginView.php');
    exit();
}

try {

    $usuario = $con->getUserWithRole($login); //BUSCAR USUARIO Y ROL

    //VERIFICAR SI EXISTE
    if (!$usuario) {
        throw new Exception("Usuario o contraseña incorrectos");
    }

    // VERIFICAR USUARIO Y CONTRASEÑA
    if ($usuario['password'] !== $password) {
        throw new Exception("Usuario o Password incorrectos, por favor intenta de nuevo");
    }

    // VERIFICAR SI EL USUARIO ESTÁ ACTIVO
    if (!$usuario['activo']) {
        throw new Exception("Tu cuenta está desactivada. Contacta al administrador");
    }

    // ALMACENAR DATOS EN SESIÓN
    $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'login' => $usuario['login'],
        'password' => $usuario['password'],
        'email' => $usuario['email'],
        'nombre' => $usuario['nombre'],
        'apellido' => $usuario['apellido'],
        'telefono' => $usuario['telefono'],
        'direccion' => $usuario['direccion'],
        'genero' => $usuario['genero'],
        'fecha_nacimiento' =>  $usuario['fecha_nacimiento']->format('Y-m-d'),
        'foto' => $usuario['foto_perfil'],
        'fecha_registro' => $usuario['fecha_registro']->format('Y-m-d H:i:s'),
        'ultimo_registro' => $usuario['ultimo_registro'] ?? ' ',
        'rol' => [
            'id_rol' => $usuario['id_rol'],
            'nombre_rol' => $usuario['nombre_rol']
        ],
        'menu' => $con->getMenuByRol($usuario['id_rol'])
    ];

    



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
            throw new Exception("Rol no reconocido");
    }
    exit();
} catch (Exception $e) {
    $_SESSION ['mensaje'] = $e->getMessage();
    $_SESSION ['alerta'] = "alert-danger";
    header('Location: ../Views/LoginView.php');
    exit();
}
