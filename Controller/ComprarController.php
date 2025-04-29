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
        
        if ($producto) {
            // Si es una petición AJAX, devolver JSON
            header('Content-Type: application/json');
            if (!$producto) {
                if ($this->isAjaxRequest()) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
                    exit;
                } else {
                    $_SESSION['mensaje'] = 'Producto no encontrado';
                    header('Location: ComprarController.php');
                    exit;
                }
            }
            
            $valoraciones = $this->con->getValoracionesProducto($id_producto);

            if ($this->isAjaxRequest()) {
                // Renderizar la vista de detalle y devolverla como HTML
                ob_start();
                include '../Views/ProductoDetalle.php';
                $html = ob_get_clean();
                echo $html;
                exit;
            } else {
                include '../Views/ProductoDetalle.php';
            }
            echo json_encode([
                'success' => true,
                'producto' => $producto,
                'valoraciones' => $valoraciones
            ]);
            exit;
        } else {
            // Petición normal (sin JS), cargar la vista completa
            if (!$producto) {
                header('Location: ../Views/ComprarView.php');
                exit;
            }
            
            $valoraciones = $this->con->getValoracionesProducto($id_producto);
            include '../Views/ProductoDetalle.php';
        }
    }
    
    public function getValoraciones($id_producto) {
        header('Content-Type: application/json');
        $valoraciones = $this->con->getValoracionesProducto($id_producto);
        echo json_encode([
            'success' => true,
            'data' => $valoraciones
        ]);
        exit;
    }

    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

$controller = new ProductosController();
$action = $_GET['action'] ?? 'index';

if ($action == 'detalle' && isset($_GET['id'])) {
    $controller->detalle($_GET['id']);
} elseif ($action == 'getValoraciones' && isset($_GET['id'])) {
    $controller->getValoraciones($_GET['id']);
} else {
    $controller->index();
}

