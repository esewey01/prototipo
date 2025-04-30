<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../Model/Conexion.php');
require('Constants.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}




$con = new Conexion();
$alerta = $_SESSION['alerta'] ?? '';
$mensaje = $_SESSION['mensaje'] ?? '';
$urlViews = URL_VIEWS;
$id_usuario = $_SESSION['usuario']['id_usuario'];
$rol_usuario = $_SESSION['usuario']['rol']['nombre_rol'];


// Procesar formularios POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_usuario = $_SESSION['usuario']['id_usuario'];

    // Verificar si existe la última fecha de modificación en la sesión
    if (isset($_SESSION['ultima_actualizacion_perfil'][$id_usuario])) {
        $ultima_actualizacion = strtotime($_SESSION['ultima_actualizacion_perfil'][$id_usuario]);
        $ahora = time();
        $diferencia_segundos = $ahora - $ultima_actualizacion;
        $una_hora_en_segundos = 3600;

        if ($diferencia_segundos < $una_hora_en_segundos) {
            $_SESSION['mensaje'] = "Debes esperar una hora antes de poder actualizar tu perfil nuevamente.";
            $_SESSION['alerta'] = "alert-warning";
            header("Location: PerfilController.php"); // Redirige al usuario
            exit();
        }
    }

    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'update_profile':
                    $data = [
                        'email' => trim($_POST['email'] ?? null),
                        'nombre' => trim($_POST['nombre'] ?? null),
                        'apellido' => trim($_POST['apellido'] ?? null),
                        'telefono' => trim($_POST['telefono'] ?? null),
                        'direccion' => trim($_POST['direccion'] ?? null),
                        'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? null,
                        'genero' => $_POST['genero'] ?? null,
                        'foto_perfil' => $_SESSION['usuario']['foto'] // Valor por defecto
                    ];

                    // Manejo de la imagen
                    if (!empty($_FILES['foto']['name'])) {
                        $uploadDir = '../Views/fotoproducto/';
                        $foto = 'user_' . $id_usuario . '_' . time() 
                        . '_' . basename($_FILES['foto']['name']);
                        $uploadFile = $uploadDir . $foto;

                        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
                            $data['foto_perfil'] = "fotoproducto/".$foto;
                        }
                    }

                    $result = $con->updateUserProfile(
                        $id_usuario,
                        $data['email'],
                        $data['nombre'],
                        $data['apellido'],
                        $data['telefono'],
                        $data['direccion'],
                        $data['fecha_nacimiento'],
                        $data['genero'],
                        $data['foto_perfil']
                    );


                    if ($resutl === false) {
                        throw new Exception("Error al actualizar el perfil");
                    }

                    // Actualizar datos en sesión
                    $_SESSION['usuario']['email'] = $data['email'];
                    $_SESSION['usuario']['nombre'] = $data['nombre'];
                    $_SESSION['usuario']['apellido'] = $data['apellido'];
                    $_SESSION['usuario']['telefono'] = $data['telefono'];
                    $_SESSION['usuario']['direccion'] = $data['direccion'];
                    $_SESSION['usuario']['fecha_nacimiento'] = $data['fecha_nacimiento'];
                    $_SESSION['usuario']['genero'] = $data['genero'];


                    $_SESSION['ultima_actualizacion_perfil'][$id_usuario] = date('Y-m-d H:i:s');
                    $_SESSION['mensaje'] = "Elementos actualizados correctamente";
                    $_SESSION['alerta'] = "alert-success";
                    header("Location: PerfilController.php");
                    exit();
                    break;

                case 'update_password':
                    // Cambio de contraseña común a todos los roles
                    $current_password = $_POST['current_password'];
                    $new_password = $_POST['new_password'];
                    $confirm_password = $_POST['confirm_password'];

                    // Verificar usuario actual
                    $user = $con->getUser($_SESSION['usuario']['login']);
                    if ($user[0]['password'] !== trim($current_password)) {

                        throw new Exception("La contraseña no conicide con la ingresada");
                    }

                    if ($new_password !== $confirm_password) {
                        throw new Exception("La contraseñas ingresadas no coinciden");
                    }

                    $con->updatePassword($id_usuario, $new_password);
                    $_SESSION['ultima_actualizacion_perfil'][$id_usuario] = date('Y-m-d H:i:s');
                    $_SESSION['mensaje'] = "Contraseña actualizada";
                    $_SESSION['alerta'] = "alert-success";
                    header("Location: PerfilController.php");
                    exit();
                    break;




                case 'update_social':
                    // Actualización de redes sociales común a todos los roles
                    $facebook = $_POST['facebook'] ?? '';
                    $instagram = $_POST['instagram'] ?? '';
                    $twitter = $_POST['twitter'] ?? '';
                    $linkedin = $_POST['linkedin'] ?? '';

                    $con->updateSocialNetworks($id_usuario, $facebook, $instagram, $linkedin, $twitter);
                    $_SESSION['mensaje'] = "Perfiles actualizados correctamente";
                    $_SESSION['alerta'] = "alert-success";
                    header("Location: PerfilController.php");
                    exit();
                    break;

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
                        $_SESSION['ultima_actualizacion_perfil'][$id_usuario] = date('Y-m-d H:i:s');
                        $_SESSION['mensaje'] = "SOLICITUD CREADA CORRECTAMENTE";
                        $_SESSION['alerta'] = "alert-success";
                        header("Location: PerfilController.php");
                        exit();
                        break;


                        exit();
                    } else {
                        throw new Exception("Acción no permitida para tu rol");
                    }
                    break;

                default:
                    throw new Exception("Acción no válida");
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['alerta'] = "alert-danger";
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
    'fecha_nacimiento' => $_SESSION['usuario']['fecha_nacimiento'],
    'genero' => $_SESSION['usuario']['genero'] ?? '',
    'foto_perfil' => $_SESSION['usuario']['foto'] ?? 'fotoproducto/user.png',
    'fecha_registro' => $_SESSION['usuario']['fecha_registro'],
    'ultimo_acceso' => $_SESSION['usuario']['ultimo_acceso'] ?? '',
    'activo' => $_SESSION['usuario']['activo'] ?? 1,
    'verificado' => $_SESSION['usuario']['verificado'] ?? 0
];


//obtener rol
$id_rol = $con->getRolUser($_SESSION['usuario']['id_usuario']);
/*
echo '<pre>'; // Para formatear la salida en HTML
print_r($id_rol);
echo '</pre>';*/
// Redes sociales
$social_data = $con->getSocialNetworks($id_usuario);
if (!empty($social_data)) {
    $social_data = $social_data[0];
} else {
    $social_data = ['facebook' => '', 'instagram' => '', 'linkedin' => '', 'twitter' => ''];
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
