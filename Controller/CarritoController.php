<?php
require_once '../Model/Conexion.php';
require('Constants.php');

class CarritoController {
    private $conexion;
    private $usuario;
    
    public function __construct(Conexion $conexion = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->conexion = $conexion ?? new Conexion();
        $this->usuario = $_SESSION['usuario'] ?? null;
    }
    
    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        
        try {
            switch ($action) {
                case 'agregar':
                    $this->agregarProducto();
                    break;
                case 'contador':
                    $this->obtenerContador();
                    break;
                case 'ver':
                    $this->verCarrito();
                    break;
                case 'actualizar':
                    $this->actualizarCantidad();
                    break;
                case 'eliminar':
                    $this->eliminarProducto();
                    break;
                case 'vaciar':
                    $this->vaciarCarrito();
                    break;
                default:
                    $this->redirigirAVerCarrito();
            }
        } catch (Exception $e) {
            $this->handleError($e, $action);
        }
    }
    
    private function agregarProducto() {
        $this->validarSesion();
        
        $id_producto = $_POST['id_producto'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if ($id_producto <= 0) {
            $this->sendJsonResponse(false, 'Producto inválido');
        }
        
        $success = $this->conexion->agregarProductoCarrito(
            $this->usuario['id_usuario'], 
            $id_producto, 
            $cantidad
        );
        
        $this->sendJsonResponse(
            $success,
            $success ? 'Producto añadido al carrito' : 'Error al agregar producto'
        );
    }
    
    private function obtenerContador() {
        $total = 0;
        if ($this->usuario) {
            $total = $this->conexion->obtenerCantidadTotalCarrito($this->usuario['id_usuario']);
        }
        $this->sendJsonResponse(true, '', ['total' => $total]);
    }
    
    private function verCarrito() {
        $this->validarSesion();
        
        $productos = $this->conexion->obtenerProductosCarrito($this->usuario['id_usuario']);
        $total = $this->conexion->obtenerTotalCarrito($this->usuario['id_usuario']);
        
        $this->renderView('CarritoView.php', [
            'productos' => $productos,
            'total' => $total
        ]);
    }
    
    private function actualizarCantidad() {
        $this->validarSesion();
        
        $id_detalle = $_POST['id_detalle'] ?? 0;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if ($id_detalle <= 0) {
            $this->sendJsonResponse(false, 'Ítem inválido');
        }
        
        $success = $this->conexion->actualizarCantidadCarrito($id_detalle, $cantidad);
        $this->sendJsonResponse($success);
    }
    
    private function eliminarProducto() {
        $this->validarSesion();
        
        $id_detalle = $_POST['id_detalle'] ?? 0;
        
        if ($id_detalle <= 0) {
            $this->sendJsonResponse(false, 'Ítem inválido');
        }
        
        $success = $this->conexion->eliminarProductoCarrito($id_detalle);
        $this->sendJsonResponse($success);
    }
    
    private function vaciarCarrito() {
        $this->validarSesion();
        
        $success = $this->conexion->vaciarCarrito($this->usuario['id_usuario']);
        $this->sendJsonResponse($success);
    }
    
    private function redirigirAVerCarrito() {
        header('Location: CarritoController.php?action=ver');
        exit;
    }
    
    private function validarSesion() {
        if (!$this->usuario) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, 'Debes iniciar sesión');
            } else {
                header('Location: LoginController.php');
                exit;
            }
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
    
    private function renderView($viewPath, $data = []) {
        extract($data);
        require "../Views/{$viewPath}";
        exit;
    }
    
    private function handleError(Exception $e, $action) {
        error_log('Error en CarritoController: ' . $e->getMessage());
        
        if ($this->isAjaxAction($action)) {
            $this->sendJsonResponse(false, 'Error en el servidor');
        } else {
            $_SESSION['mensaje'] = 'Error al procesar tu solicitud';
            $_SESSION['alerta'] = 'alert-danger';
            header('Location: PrincipalController.php');
            exit;
        }
    }
    
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    private function isAjaxAction($action) {
        return in_array($action, [
            'agregar', 'contador', 'actualizar', 
            'eliminar', 'vaciar'
        ]);
    }
}

// Punto de entrada
$controller = new CarritoController();
$controller->handleRequest();