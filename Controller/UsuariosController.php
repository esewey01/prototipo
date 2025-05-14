<?php
require_once('../Model/Conexion.php');
require('Constants.php');

class UsuarioController {
    private $conexion;
    private $currentUser;
    private $isSuperUser;
    private $alerta;
    private $error;
    private $mensaje;
    private $urlViews;
    private $roles;
    
    public function __construct() {
        $this->checkSession();
        $this->initializeSession();
        $this->conexion = new Conexion();
        $this->initializeProperties();
    }
    
    private function checkSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario']['login'])) {
            header("Location: ../index.php");
            exit();
        }
        
        if ($_SESSION['usuario']['rol']['id_rol'] >= 2) {
            $_SESSION['mensaje'] = "Acceso no autorizado";
            $_SESSION['alerta'] = "alert-danger";
            header("Location: ../Views/LoginView.php");
            exit();
        }
    }
    
    private function initializeSession() {
        $this->urlViews = URL_VIEWS;
        $this->alerta = $_SESSION['alerta'] ?? '';
        $this->mensaje = $_SESSION['mensaje'] ?? '';
        $this->error = $_SESSION['error'] ?? '';
        
        // Limpiar mensajes después de mostrarlos
        unset($_SESSION['mensaje']);
        unset($_SESSION['alerta']);
        unset($_SESSION['error']);
    }
    
    private function initializeProperties() {
        $this->roles = $this->conexion->getRoles();
        $this->currentUser = $this->conexion->getUserWithRole($_SESSION['usuario']['login']);
        $rolUsuario = $this->conexion->getRolUser($_SESSION['usuario']['id_usuario']);
        $this->isSuperUser = ($rolUsuario['id_rol'] == 0);
    }
    
    public function handleRequest() {
        try {
            if (!$this->conexion) {
                throw new Exception("Error de conexión");
            }
            
            $data = [
                'currentUser' => $this->currentUser,
                'isSuperUser' => $this->isSuperUser,
                'administradores' => $this->conexion->getUsersByRoleType('Administrador'),
                'vendedores' => $this->conexion->getUsersByRoleType('Vendedor'),
                'clientes' => $this->conexion->getUsersByRoleType('Cliente'),
                'roles' => $this->roles,
                'urlViews' => $this->urlViews,
                'alerta' => $this->alerta,
                'mensaje' => $this->mensaje,
                'error' => $this->error
            ];
            
            $this->renderView('UsuarioViewFinal.php', $data);
            
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    private function renderView($viewName, $data = []) {
        extract($data);
        require_once("../Views/$viewName");
    }
    
    private function handleError(Exception $e) {
        error_log("Error en UsuarioController: " . $e->getMessage());
        $_SESSION['error'] = "Error en el sistema. Por favor intente más tarde.";
        header("Location: ErrorController.php");
        exit();
    }
}


try{


// Punto de entrada
$controller = new UsuarioController();
$controller->handleRequest();
}
catch(Throwable $e) { // Captura tanto Exception como Error
    // Registrar el error en logs
    error_log("Error crítico en AuthController: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());

    // Iniciar sesión si no está iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION['mensaje'] = "Error en el sistema. Por favor intente más tarde.";
    $_SESSION['alerta'] = "alert-danger";
    header ('Location: UsuariosController.php');
    exit();
}