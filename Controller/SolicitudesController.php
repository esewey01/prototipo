<?php
require('../Model/Conexion.php');
require('Constants.php');
session_start();

if (!isset($_SESSION['login']) || $_SESSION['id_tipo'] != 1) {
    require('../index.php');
    exit();
}

$urlViews = URL_VIEWS;
$con = new Conexion();

$menuUser=$con->getMenuAdmin();

// Procesar acciones
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'aprobar':
                    $con->actualizarEstadoSolicitud($_POST['id_solicitud'], 'APROBADA');
                    // Opcional: convertir usuario en vendedor
                    // $con->actualizarTipoUsuario($_POST['id_usuario'], 2);
                    $_SESSION['mensaje_exito'] = "Solicitud aprobada correctamente";
                    break;
                    
                case 'rechazar':
                    $con->actualizarEstadoSolicitud($_POST['id_solicitud'], 'RECHAZADA');
                    $_SESSION['mensaje_exito'] = "Solicitud rechazada correctamente";
                    break;
            }
        } catch (Exception $e) {
            $_SESSION['mensaje_error'] = $e->getMessage();
        }
    }
    require('../Views/SolicitudesView.php');
    
    exit();
}

// Obtener datos para la vista
$solicitudes = $con->getSolicitudesVendedores();
require('../Views/SolicitudesViews.php');
//require_once('../Views/AdminSolicitudes.php');