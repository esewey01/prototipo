<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class ProductosController
{
    private $conexion;
    private $requestMethod;
    private $isAjax;
    private $urlViews;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->urlViews = URL_VIEWS;
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->isAjax = $this->isAjaxRequest();
        session_start();
    }

    public function handleRequest()
    {
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'detalle':
                $this->handleProductDetail();
                break;
            case 'getValoraciones':
                $this->handleGetRatings();
                break;
            default:
                $this->showProductList();
        }
    }

    private function showProductList()
    {
        try {
            $categorias = $this->conexion->getAllCategorias();
            $id_categoria = $_GET['categoria'] ?? null;
            $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $productos_por_pagina = 12; // Número de productos por página

            // Calcular el offset para la consulta SQL
            $offset = ($pagina_actual - 1) * $productos_por_pagina;

            // Obtener el total de productos activos
            $total_productos = $this->conexion->getTotalProductosActivos($id_categoria);
            $total_paginas = ceil($total_productos / $productos_por_pagina);

            // Obtener los productos para la página actual
            $productos = $this->conexion->getProductosByCategoria($id_categoria, $productos_por_pagina, $offset);

            $this->renderView('ComprarView.php', [
                'categorias' => $categorias,
                'productos' => $productos,
                'total_paginas' => $total_paginas,
                'pagina_actual' => $pagina_actual,
                'error' => empty($productos) ? 'No se encontraron productos' : null
            ]);
        } catch (Exception $e) {
            error_log("Error en showProductList: " . $e->getMessage());
            $this->renderView('ComprarView.php', [
                'categorias' => [],
                'productos' => [],
                'error' => 'Error al cargar los productos'
            ]);
        }
    }

    private function handleProductDetail()
    {
        if (!isset($_GET['id'])) {
            $this->sendErrorResponse('ID de producto no proporcionado');
            return;
        }

        $id_producto = $_GET['id'];
        $producto = $this->conexion->getProductoById($id_producto);

        if (!$producto) {
            $this->handleProductNotFound();
            return;
        }

        // Obtener valoraciones y promedio
        $valoraciones = $this->conexion->getValoracionesProducto($id_producto);
        $promedio = $this->conexion->getPromedioValoraciones($id_producto);

        if ($this->isAjax) {
            $this->renderPartialView('ProductoDetalle.php', [
                'producto' => $producto,
                'valoraciones' => $valoraciones,
                'promedio' => $promedio,
                'URL_VIEWS' => URL_VIEWS
            ]);
        } else {
            $this->renderView('ProductoDetalle.php', [
                'producto' => $producto,
                'valoraciones' => $valoraciones,
                'promedio' => $promedio
            ]);
        }
    }

    private function handleGetRatings()
    {
        if (!isset($_GET['id'])) {
            $this->sendErrorResponse('ID de producto no proporcionado');
            return;
        }

        $id_producto = $_GET['id'];
        $valoraciones = $this->conexion->getValoracionesProducto($id_producto);
        $promedio = $this->conexion->getPromedioValoraciones($id_producto);

        $this->sendJsonResponse([
            'success' => true,
            'data' => $valoraciones,
            'promedio' => $promedio
        ]);
    }

    private function handleProductNotFound()
    {
        if ($this->isAjax) {
            $this->sendJsonResponse([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } else {
            $_SESSION['mensaje'] = 'Producto no encontrado';
            header('Location: ComprarController.php');
            exit;
        }
    }

    private function renderView($viewPath, $data = [])
    {
        extract($data);
        include "../Views/{$viewPath}";
        exit;
    }

    private function renderPartialView($viewPath, $data = [])
    {
        ob_start();
        $this->renderView($viewPath, $data);
        $html = ob_get_clean();
        echo $html;
        exit;
    }

    private function sendJsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    private function sendErrorResponse($message, $statusCode = 400)
    {
        $this->sendJsonResponse([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

try {
    // Punto de entrada de la aplicación
    $controller = new ProductosController();
    $controller->handleRequest();
} catch (Throwable $e) { // Captura tanto Exception como Error
    // Registrar el error en logs
    error_log("Error crítico: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

    // Iniciar sesión si no está iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['mensaje'] = "Error en el sistema. Por favor intente más tarde.";
    $_SESSION['alerta'] = "alert-danger";
    header("Location: ComprarController.php");
    exit();
}
