<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');

class VentasController
{
    private $conexion;
    private $currentUser;
    private $isVendedor;
    private $vendedorId;
    private $success;
    private $error;
    private $urlViews;

    public function __construct()
    {
        $this->checkSession();
        $this->initializeSession();
        $this->conexion = new Conexion();
        $this->initializeProperties();
    }

    private function checkSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario'])) {
            header('Location: LoginController.php');
            exit();
        }

        if ($_SESSION['usuario']['rol']['id_rol'] != 2) {
            $_SESSION['error'] = "Acceso no autorizado para este rol";
            header('Location: LoginController.php');
            exit();
        }
    }

    private function initializeSession()
    {
        $this->urlViews = URL_VIEWS;
        $this->success = $_SESSION['success'] ?? '';
        $this->error = $_SESSION['error'] ?? '';

        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }

    private function initializeProperties()
    {
        $this->currentUser = $_SESSION['usuario'];
        $this->vendedorId = $this->currentUser['id_usuario'];
        $this->isVendedor = ($this->currentUser['rol']['id_rol'] == 2);
    }

    public function handleRequest()
    {
        try {
            $action = $_GET['action'] ?? '';

            switch ($action) {
                case 'actualizar_estado':
                    $this->handleUpdateOrderStatus();
                    break;
                default:
                    $this->showOrdersList();
                    break;
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function handleUpdateOrderStatus() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_orden = $_POST['id_orden'] ?? null;
        $nuevo_estado = $_POST['estado'] ?? null;
        
        if (!$id_orden || !$nuevo_estado) {
            $_SESSION['error'] = "Datos incompletos para actualizar el estado";
            header('Location: VentasController.php');
            exit();
        }
        
        try {
            $resultado = $this->conexion->actualizarEstadoOrden($id_orden, $nuevo_estado);
            
            if ($resultado) {
                $_SESSION['success'] = "Estado de la orden actualizado correctamente";
            } else {
                $_SESSION['error'] = "Error al actualizar el estado de la orden";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
        header('Location: VentasController.php');
        exit();
    }
}

  
    private function showOrdersList()
    {
        $filtro = $_GET['estado'] ?? null;
        $ordenes = $this->conexion->getHistorialVendedor($this->vendedorId, $filtro);

        // Agregar conteo de productos por orden
        foreach ($ordenes as &$orden) {
            $detalles = $this->conexion->getDetalleOrden($orden['id_orden']);
            $orden['total_productos'] = count($detalles);
        }

        $data = [
            'currentUser' => $this->currentUser,
            'isVendedor' => $this->isVendedor,
            'ordenes' => $ordenes,
            'success' => $this->success,
            'error' => $this->error,
            'urlViews' => $this->urlViews
        ];

        $this->renderView('VentasView.php', $data);
    }

    private function renderView($viewName, $data = [])
    {
        extract($data);
        require_once("../Views/$viewName");
    }

    private function handleError(Exception $e)
    {
        error_log("Error en VentasController: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header('Location: VentasController.php');
        exit();
    }
}

try {
    // Punto de entrada
    $controller = new VentasController();
    $controller->handleRequest();
} catch (Throwable $e) {
    error_log("Error crítico en VentasController: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['error'] = "Error en el sistema. Por favor intente más tarde.";
    header('Location: VentasController.php');
    exit();
}
