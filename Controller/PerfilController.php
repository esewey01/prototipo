<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$con = new Conexion();
$urlViews = URL_VIEWS;
$id_usuario = $_SESSION['usuario']['id_usuario'];
$rol_usuario = $_SESSION['usuario']['rol']['nombre_rol'];

// Procesar formularios POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'update_profile':
                    $data = [
                      
                        'email' => trim($_POST['email']),
                        'nombre' => trim($_POST['nombre']),
                        'apellido' => trim($_POST['apellido']),
                        'telefono' => trim($_POST['telefono']),
                        'direccion' => trim($_POST['direccion']),
                        'fecha_nacimiento' => !empty($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null,
                        'genero' => $_POST['genero'] ?? null,
                        'foto_perfil' => $_SESSION['usuario']['foto'] // Mantener por defecto
                    ];
                    
                    // Manejo de la imagen
                    if (!empty($_FILES['foto']['name'])) {
                        $uploadDir = '../fotoproducto/';
                        $foto = 'user_' . $id_usuario . '_' . time() . '_' . basename($_FILES['foto']['name']);
                        $uploadFile = $uploadDir . $foto;
                        
                        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                            $data['foto_perfil'] = $foto;
                            $_SESSION['usuario']['foto'] = $foto;
                        }
                    }
                    
                    $con->updateUserProfile($id_usuario, $data);
                    
                    // Actualizar datos en sesión
                    $_SESSION['usuario']['email'] = $data['email'];
                    $_SESSION['usuario']['nombre'] = $data['nombre'];
                    $_SESSION['usuario']['apellido'] = $data['apellido'];
                    $_SESSION['usuario']['telefono'] = $data['telefono'];
                    $_SESSION['usuario']['direccion'] = $data['direccion'];
                    $_SESSION['usuario']['fecha_nacimiento'] = $data['fecha_nacimiento'];
                    $_SESSION['usuario']['genero'] = $data['genero'];
                    
                    header("Location: Perfil.php?success=1");
                    exit();
                    
                case 'update_password':
                    // Cambio de contraseña común a todos los roles
                    $current_password = $_POST['current_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];
                    
                    // Verificar contraseña actual
                    $user = $con->getUser($_SESSION['usuario']['login']);
                    if ($user[0]['password'] !== $current_password) {
                        throw new Exception("La contraseña actual es incorrecta");
                    }
                    
                    if ($new_password !== $confirm_password) {
                        throw new Exception("Las contraseñas no coinciden");
                    }
                    
                    $con->updatePassword($id_usuario, $new_password);
                    header("Location: Perfil.php?success=1");
                    exit();
                    
                case 'update_social':
                    // Actualización de redes sociales común a todos los roles
                    $facebook = $_POST['facebook'] ?? '';
                    $instagram = $_POST['instagram'] ?? '';
                    $twitter = $_POST['twitter'] ?? '';
                    $linkedin=$_POST['linkedin']??'';
                    
                    $con->updateSocialNetworks($id_usuario, $facebook, $instagram,$linkedin, $twitter);
                    header("Location: Perfil.php?success=1");
                    exit();
                    
                case 'seller_request':
                    // Solo para clientes que quieren ser vendedores
                    if (strtolower($rol_usuario) === 'cliente') {
                        $id_categoria = (int)$_POST['categoria'];
                        $descripcion = trim($_POST['descripcion']);
                        
                        if (empty($id_categoria)) {
                            throw new Exception("Debes seleccionar una categoría");
                        }
                        
                        if (empty($descripcion)) {
                            throw new Exception("Debes proporcionar una descripción");
                        }
                        
                        // Verificar si ya tiene una solicitud pendiente
                        $solicitudes = $con->getSolicitudesUsuario($id_usuario);
                        $tienePendiente = false;
                        
                        foreach ($solicitudes as $solicitud) {
                            if ($solicitud['estado'] === 'PENDIENTE') {
                                $tienePendiente = true;
                                break;
                            }
                        }
                        
                        if ($tienePendiente) {
                            throw new Exception("Ya tienes una solicitud pendiente");
                        }
                        
                        $con->createSellerRequest($id_usuario, $id_categoria, $descripcion);
                        header("Location: Perfil.php?success=1");
                        exit();
                    } else {
                        throw new Exception("Acción no permitida para tu rol");
                    }
                    break;
                    
                default:
                    throw new Exception("Acción no válida");
            }
        } catch (Exception $e) {
            header("Location: Perfil.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Obtener datos para la vista
$user_data = [
    'id_usuario' => $_SESSION['usuario']['id_usuario'],
    'login' => $_SESSION['usuario']['login'],
    'email' => $_SESSION['usuario']['email'] ?? '',
    'nombre' => $_SESSION['usuario']['nombre'],
    'apellido' => $_SESSION['usuario']['apellido'] ?? '',
    'telefono' => $_SESSION['usuario']['telefono'] ?? '',
    'direccion' => $_SESSION['usuario']['direccion'] ?? '',
    'fecha_nacimiento' => $_SESSION['usuario']['fecha_nacimiento']->format('Y-m-d'),
    'genero' => $_SESSION['usuario']['genero'] ?? '',
    'foto_perfil' => $_SESSION['usuario']['foto'] ?? 'fotoproducto/user.png',
    'fecha_registro' => $_SESSION['usuario']['fecha_registro'],
    'ultimo_acceso' => $_SESSION['usuario']['ultimo_acceso'] ?? '',
    'activo' => $_SESSION['usuario']['activo'] ?? 1,
    'verificado' => $_SESSION['usuario']['verificado'] ?? 0
];

//obtener rol
$id_rol=$con->getRolUser($_SESSION['usuario']['id_usuario']);
// Redes sociales
$social_data = $con->getSocialNetworks($id_usuario);
if (!empty($social_data)) {
    $social_data = $social_data[0];
} else {
    $social_data = ['facebook' => '', 'instagram' => '','linkedin'=>'', 'twitter' => ''];
}

// Categorías (solo para clientes)
$categories = [];
if (strtolower($rol_usuario) === 'cliente') {
    $categories = $con->getCategories();
}

// Solicitudes (solo para clientes)
$solicitudes = [];
if (strtolower($rol_usuario) === 'cliente') {
    $solicitudes = $con->getSolicitudesUsuario($id_usuario);
}

require('../Views/Perfil.php');