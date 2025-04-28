<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class SolicitudesController
{
    private $con;

    public function __construct()
    {
        $this->con = new Conexion();
        session_start();
    }

    public function index()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: login.php');
            exit;
        }


        if ($_SESSION['usuario']['rol']['id_rol'] !== 1) {
            $_SESSION['mensaje'] = "Acceso no autorizado";
            $_SESSION['alerta'] = "alert-danger";
            require("../Views/LoginView.php");
        }

        $solicitudes_pendientes = $this->con->getAllSellerRequestsPend('PENDIENTE');
        $solicitudes_aprobadas = $this->con->getAllSellerRequests('APROBADA');
        $solicitudes_rechazadas = $this->con->getAllSellerRequests('RECHAZADA');

        include '../views/SolicitudesViews.php';
    }

    public function procesar()
    {
        if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
            exit;
        }

        $id_solicitud = $_POST['id_solicitud'] ?? null;
        $accion = $_POST['accion'] ?? null;
        $comentarios = $_POST['comentarios'] ?? '';
        $admin_id = $_SESSION['usuario']['id_usuario'];

        if (!$id_solicitud || !$accion) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        try {
            if ($accion == 'aprobar') {
                // 1. Actualizar estado de la solicitud
                $this->con->updateSellerRequestStatus(
                    $id_solicitud,
                    'APROBADA',
                    $admin_id,
                    $comentarios
                );

                // 2. Cambiar rol del usuario a vendedor
                $solicitud = $this->con->getSellerRequestDetails($id_solicitud);
                $this->con->changeUserRole($solicitud['id_usuario'], 2);

                echo json_encode(['success' => true, 'message' => 'Solicitud aprobada correctamente']);
            } elseif ($accion == 'rechazar') {
                $this->con->updateSellerRequestStatus(
                    $id_solicitud,
                    'RECHAZADA',
                    $admin_id,
                    $comentarios
                );

                echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function detalles()
    {
        $id_solicitud = $_POST['id_solicitud'] ?? null;

        if (!$id_solicitud) {
            echo "ID de solicitud no proporcionado";
            exit;
        }

        $solicitud = $this->con->getSellerRequestDetails($id_solicitud);

        if (!$solicitud) {
            echo "Solicitud no encontrada";
            exit;
        }

        // Generar HTML con los detalles
        $html = '<div class="row">';
        $html .= '<div class="col-md-6"><h4>Información del Solicitante</h4>';
        $html .= '<p><strong>Nombre:</strong> ' . htmlspecialchars($solicitud['nombre']) . '</p>';
        $html .= '<p><strong>Usuario:</strong> ' . htmlspecialchars($solicitud['login']) . '</p>';
        $html .= '<p><strong>Email:</strong> ' . htmlspecialchars($solicitud['email']) . '</p>';
        $html .= '<p><strong>Teléfono:</strong> ' . htmlspecialchars($solicitud['telefono']) . '</p>';
        $html .= '</div>';

        $html .= '<div class="col-md-6"><h4>Detalles de la Solicitud</h4>';
        $html .= '<p><strong>Fecha:</strong> ' . $solicitud['fecha_solicitud']->format('d/m/Y H:i') . '</p>';
        $html .= '<p><strong>Estado:</strong> ' . htmlspecialchars($solicitud['estado']) . '</p>';

        if ($solicitud['estado'] != 'PENDIENTE') {
            $html .= '<p><strong>Revisor:</strong> ' . htmlspecialchars($solicitud['nombre_revisor']) . '</p>';
            $html .= '<p><strong>Fecha Revisión:</strong> ' . $solicitud['fecha_revision']->format('d/m/Y H:i') . '</p>';
        }

        $html .= '<p><strong>Descripción:</strong></p>';
        $html .= '<div class="well">' . nl2br(htmlspecialchars($solicitud['descripcion'])) . '</div>';

        if (!empty($solicitud['comentarios'])) {
            $html .= '<p><strong>Comentarios:</strong></p>';
            $html .= '<div class="well">' . nl2br(htmlspecialchars($solicitud['comentarios'])) . '</div>';
        }

        $html .= '</div></div>';

        echo $html;
    }
}

// Uso del controlador
$controller = new SolicitudesController();

$action = $_GET['action'] ?? 'index';
if (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'procesar':
        $controller->procesar();
        break;
    case 'detalles':
        $controller->detalles();
        break;
    default:
        $controller->index();
        break;
}
