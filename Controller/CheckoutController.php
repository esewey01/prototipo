<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class CheckoutController {
    private $conexion;
    private $urlViews;
    private $usuario;
    private $isAjax;
    
    public function __construct(Conexion $conexion = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->conexion = $conexion ?? new Conexion();
        $this->urlViews = URL_VIEWS;
        $this->usuario = $_SESSION['usuario'] ?? null;
        $this->isAjax = $this->isAjaxRequest();
    }
    
    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        
        try {
            switch ($action) {
                case 'procesarPago':
                    $this->procesarPago();
                    break;
                case 'marcar_entregado':
                    $this->marcarComoEntregado();
                    break;
                default:
                    $this->handleDefaultAction();
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    private function procesarPago() {
        $this->validarAutenticacion();
        
        $id_usuario = $this->usuario['id_usuario'];
        $id_carrito = $this->conexion->obtenerCarritoActivo($id_usuario);
        $direccion = $_POST['direccion'] ?? '';
        $comentarios = $_POST['comentarios'] ?? '';
        
        // Verificar que el carrito no esté vacío
        $productos = $this->conexion->obtenerProductosCarrito($id_usuario);
        if (empty($productos)) {
            $this->sendJsonResponse(false, 'El carrito está vacío');
        }
        
        // Crear órdenes (una por cada vendedor)
        $ids_ordenes = $this->conexion->crearOrden($id_usuario, $id_carrito, $direccion, $comentarios);
        
        if (!$ids_ordenes) {
            throw new Exception("Error al crear las órdenes");
        }
        
        // Guardar las órdenes creadas en sesión
        $_SESSION['ultimas_ordenes'] = $ids_ordenes;
        
        if ($this->isAjax) {
            $this->sendJsonResponse(true, '', [
                'redirect' => '../Views/CheckoutView.php'
            ]);
        } else {
            $this->redirect('../Views/CheckoutView.php');
        }
    }
    
    private function marcarComoEntregado() {
        $this->validarAdministrador();
        
        $id_orden = $_POST['id_orden'] ?? 0;
        if ($id_orden <= 0) {
            $this->sendJsonResponse(false, 'ID de orden inválido');
        }
        
        // Actualizar estado de la orden
        $this->conexion->executeNonQuery(
            "UPDATE ORDENES SET estado = 'ENTREGADO', fecha_actualizacion = GETDATE() WHERE id_orden = ?",
            [$id_orden]
        );
        
        // Notificar al cliente
        $this->enviarNotificacionEntrega($id_orden);
        
        $this->sendJsonResponse(true, 'Orden marcada como entregada');
    }
    
    private function enviarNotificacionEntrega($id_orden) {
        $orden = $this->conexion->getOrdenById($id_orden);
        if ($orden) {
            $this->conexion->executeNonQuery(
                "INSERT INTO NOTIFICACIONES (id_usuario, titulo, mensaje) VALUES (?, ?, ?)",
                [
                    $orden['id_usuario'],
                    'Pedido entregado',
                    'Tu pedido #'.$id_orden.' ha sido marcado como entregado'
                ]
            );
        }
    }
    
    private function handleDefaultAction() {
        if (!$this->isAjax) {
            if (isset($_SESSION['ultimas_ordenes'])) {
                $this->redirect('../Views/CheckoutView.php');
            } else {
                $this->redirect('../Controller/CarritoController.php');
            }
        }
    }
    
    private function validarAutenticacion() {
        if (!$this->usuario) {
            if ($this->isAjax) {
                $this->sendJsonResponse(false, 'Debes iniciar sesión');
            } else {
                $this->redirect('../Controller/LoginController.php');
            }
        }
    }
    
    private function validarAdministrador() {
        if (!$this->usuario || $this->usuario['id_rol'] != 2) {
            $this->sendJsonResponse(false, 'Acceso no autorizado');
        }
    }
    
    private function sendJsonResponse($success, $message = '', $data = []) {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'success' => $success,
            'message' => $message
        ], $data));
        exit;
    }
    
    private function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    private function handleError(Exception $e) {
        error_log('Error en CheckoutController: ' . $e->getMessage());
        
        if ($this->isAjax) {
            $this->sendJsonResponse(
                false,
                'Error al procesar el pago: ' . $e->getMessage()
            );
        } else {
            $_SESSION['error_checkout'] = $e->getMessage();
            $this->redirect('../Controller/CarritoController.php');
        }
    }
    
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

// Punto de entrada
$controller = new CheckoutController();
$controller->handleRequest();