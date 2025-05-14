<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class UsuarioController {
    private $conexion;
    private $urlViews;
    
    public function __construct() {
        $this->conexion = new Conexion();
        $this->urlViews=URL_VIEWS;
    }
    
    public function handleRequest() {
        $action = $_GET['action'] ?? 'detalle';
        
        switch ($action) {
            case 'detalle':
                $this->handleDetalleUsuario();
                break;
            default:
                $this->sendJsonResponse([
                    'success' => false,
                    'message' => 'Acción no válida'
                ], 400);
        }
    }
    
    private function handleDetalleUsuario() {
        if (!isset($_GET['id'])) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'ID de usuario no proporcionado'
            ], 400);
            return;
        }
        
        $id_usuario = $_GET['id'];
        $usuario = $this->conexion->getUserById($id_usuario);
        
        if (!$usuario) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
            return;
        }
        
        // Obtener redes sociales del usuario
        $redes = $this->conexion->getSocialNetworks($id_usuario);
        
        if (!$redes) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
            return;
        }
        $this->sendJsonResponse([
            'success' => true,
            'data' => [
                'usuario' => $usuario,
                'redes' => $redes
            ]
        ]);
    }
    
    private function sendJsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}

// Punto de entrada
$controller = new UsuarioController();
$controller->handleRequest();