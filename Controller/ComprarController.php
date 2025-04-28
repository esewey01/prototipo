<?php
require_once ('../Model/Conexion.php');
require('Constants.php');

class ProductosController {
    private $con;
    
    public function __construct() {
        $this->con = new Conexion();
        session_start();
    }
    
    public function index() {
        $categorias = $this->con->getAllCategorias();
        $id_categoria = $_GET['categoria'] ?? null;
        $productos = $this->con->getProductosByCategoria($id_categoria);
        
        include '../views/ComprarView.php';
    }
    
    public function detalle($id_producto) {
        $producto = $this->con->getProductoById($id_producto);
        if (!$producto) {
            header('Location: ../Views/ComprarView.php');
            exit;
        }
        
        $valoraciones = $this->con->getValoracionesProducto($id_producto);
        include '../Views/ProductoDetalle.php';
    }
}

$controller = new ProductosController();
$action = $_GET['action'] ?? 'index';

if ($action == 'detalle' && isset($_GET['id'])) {
    $controller->detalle($_GET['id']);
} else {
    $controller->index();
}