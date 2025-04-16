<?php
require('../Model/Conexion.php');
require('Constants.php');

$errores = []; //MENSAJES DE ERROR
$mensaje = [];

session_start(); //SE INICIA SESIÓN

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensaje = "Acceso no autorizado";
    mostrarMensaje($mensaje);
    require('../Views/LoginView.php');
    exit();
}

// OBTENER DATOS DEL FORMULARIO
$urlViews = URL_VIEWS;
$login = trim($_POST['login']);
$password = trim($_POST['password']);
$con = new Conexion();

// VALIDACIÓN DE CAMPOS VACÍOS
if (empty($login) || empty($password)) {
    $errores = "Ningún campo debe estar vacío";
    mostrarMensaje($errores);
    require('../Views/LoginView.php');
    exit();
}

try {
    $usuario = $con->getUserWithRole($login);
    // BUSCAR USUARIO 


    // VERIFICAR USUARIO Y CONTRASEÑA
    if (!$usuario and $usuario['password'] !== $password) {
        $errores = "Usuario o Password incorrectos, por favor intenta de nuevo";
        mostrarMensaje($errores);
        require('../Views/LoginView.php');
        exit();
    }

    // VERIFICAR SI EL USUARIO ESTÁ ACTIVO
    if (!$usuario['activo']) {
        $errores = "Tu cuenta está desactivada. Contacta al administrador.";
        mostrarMensaje($errores);
        require('../Views/LoginView.php');
        exit();
    }

    // ALMACENAR DATOS EN SESIÓN
    $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'login' => $usuario['login'],
        'password'=>$usuario['password'],
        'email'=> $usuario['email'],
        'nombre' => $usuario['nombre'],
        'apellido'=>$usuario['apellido'],
        'telefono' => $usuario['telefono'],
        'direccion'=>$usuario['direccion'],
        'genero'=>$usuario['genero'],
        'fecha_nacimiento' => isset($usuario['fecha_nacimiento']) ? $usuario['fecha_nacimiento'] : null,
        'foto' => $usuario['foto_perfil'],
        'fecha_registro' => $usuario['fecha_registro']->format('Y-m-d H:i:s'),
        'ultimo_registro'=>$usuario['ultimo_registro'] ?? ' ',
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
            $errores = "Rol no reconocido";
            mostrarMensaje($errores);
            require('../Views/LoginView.php');
    }

    exit();
} catch (Exception $e) {
    error_log("Error en login: " . $e->getMessage());
    $errores = "Error en el sistema. Por favor intenta más tarde.";
    mostrarMensaje($errores);
    require('../Views/LoginView.php');
}



function mostrarMensaje($mensaje)
{
    echo '<script language="javascript">
        alert("' . addslashes($mensaje) . '");   
    </script>';
}
