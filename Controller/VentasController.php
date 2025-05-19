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

    private function handleUpdateOrderStatus()
    {
        // Asegurar que siempre devolvemos JSON
        header('Content-Type: application/json');

        try {
            // Verificación exhaustiva
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido", 405);
            }

            if (!isset($_POST['id_orden'], $_POST['estado'])) {
                throw new Exception("Datos incompletos", 400);
            }

            $id_orden = (int)$_POST['id_orden'];
            $nuevo_estado = strtoupper(trim($_POST['estado']));

            // Validaciones
            if ($id_orden <= 0) {
                throw new Exception("ID de orden inválido", 400);
            }

            $estados_permitidos = ['PENDIENTE', 'PAGADO', 'ENTREGADO', 'CANCELADO'];
            if (!in_array($nuevo_estado, $estados_permitidos)) {
                throw new Exception("Estado no válido: $nuevo_estado", 400);
            }

            // Obtener orden actual
            $orden = $this->conexion->getOrdenById($id_orden);
            if (!$orden) {
                throw new Exception("Orden no encontrada", 404);
            }

            // Verificar permisos
            if ($orden['id_vendedor'] != $this->vendedorId) {
                throw new Exception("No tienes permiso para modificar esta orden", 403);
            }

            // Actualizar estado con transacción
            $result = $this->conexion->actEstado($nuevo_estado, $id_orden);

            if (!$result) {
                throw new Exception("Error al ejecutar la actualización en BD", 500);
            }

            // Respuesta exitosa
            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'order' => [
                    'id' => $id_orden,
                    'previous_status' => $orden['estado'],
                    'new_status' => $nuevo_estado
                ]
            ]);
        } catch (Exception $e) {
            http_response_code($e->getCode() ?: 500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
                'code' => $e->getCode() ?: 500
            ]);
        }
        exit; // Asegurar que no se ejecute más código
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
