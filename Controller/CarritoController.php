<?php
require_once '../Model/Conexion.php';
require('Constants.php');

class CarritoController {
    private $con;
    
    public function __construct() {
        $this->con = new Conexion();
        session_start();
    }
    
    public function agregar() {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
            exit;
        }
        
        $id_producto = $_POST['id_producto'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 1;
        
        if (!$id_producto) {
            echo json_encode(['success' => false, 'message' => 'Producto no especificado']);
            exit;
        }
        
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $id_carrito = $this->con->getOrCreateCarrito($id_usuario);
        $result = $this->con->addToCarrito($id_carrito, $id_producto, $cantidad);
        
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Producto agregado al carrito' : 'Error al agregar producto'
        ]);
    }
    
    public function ver() {
        if (!isset($_SESSION['usuario'])) {
            header('Location: login.php');
            exit;
        }
        
        $id_usuario = $_SESSION['usuario']['id_usuario'];
        $id_carrito = $this->con->getOrCreateCarrito($id_usuario);
        $items = $this->con->getCarritoItems($id_carrito);
        
        include '../Views/CarritoView.php';
    }
    
    public function actualizar() {
        // Implementar actualización de cantidades
    }
    
    public function eliminar() {
        // Implementar eliminación de items
    }
}

$controller = new CarritoController();
$action = $_GET['action'] ?? 'ver';

switch ($action) {
    case 'agregar':
        $controller->agregar();
        break;
    case 'actualizar':
        $controller->actualizar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        $controller->ver();
        break;
}