<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

class AuthController {
    private $conexion;
    
    private $alerta;
    private $mensaje;
    
    public function __construct(Conexion $conexion) {
        $this->conexion = $conexion;
        $this->alerta = $_SESSION['alerta'] ?? '';
        $this->mensaje = $_SESSION['mensaje'] ?? '';
       
    }
    
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setErrorSession("Acceso no autorizado");
            $this->redirectToLogin();
            return;
        }
        
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);
        
        if (!$this->validateInputs($login, $password)) {
            return;
        }
        
        try {
            $this->authenticateUser($login, $password);
        } catch (Exception $e) {
            $this->setErrorSession($e->getMessage());
            $this->redirectToLogin();
        }
    }
    
    private function validateInputs($login, $password) {
        if (empty($login) || empty($password)) {
            $this->setErrorSession("Ningún campo debe estar vacío");
            $this->redirectToLogin();
            return false;
        }
        return true;
    }
    
    private function authenticateUser($login, $password) {
        $usuario = $this->conexion->getUserWithRole($login);
        
        if (!$usuario) {
            throw new Exception("Usuario o contraseña incorrectos");
        }
        
        $this->validatePassword($usuario, $password);
        $this->checkAccountStatus($usuario);
        $this->setupUserSession($usuario);
        $this->redirectBasedOnRole($usuario);
    }
    
    private function validatePassword($usuario, $password) {
        if ($usuario['password'] !== $password) {
            throw new Exception("Usuario o Password incorrectos, por favor intenta de nuevo");
        }
    }
    
    private function checkAccountStatus($usuario) {
        if (!$usuario['activo']) {
            throw new Exception("Tu cuenta está desactivada. Contacta al administrador");
        }
    }
    
    private function setupUserSession($usuario) {
        $_SESSION['usuario'] = [
            'id_usuario' => $usuario['id_usuario'],
            'login' => $usuario['login'],
            'password' => $usuario['password'],
            'email' => $usuario['email'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'telefono' => $usuario['telefono'],
            'direccion' => $usuario['direccion'],
            'genero' => $usuario['genero'],
           'fecha_nacimiento' => ($usuario['fecha_nacimiento'] instanceof DateTime) ? $usuario['fecha_nacimiento']->format('Y-m-d') : '',
            'foto' => $usuario['foto_perfil'],
            'fecha_registro' => $usuario['fecha_registro']->format('Y-m-d H:i:s'),
            'ultimo_registro' => $usuario['ultimo_registro'] ?? ' ',
            'rol' => [
                'id_rol' => $usuario['id_rol'],
                'nombre_rol' => $usuario['nombre_rol']
            ],
            'menu' => $this->conexion->getMenuByRol($usuario['id_rol'])
        ];
    }
    
    private function redirectBasedOnRole($usuario) {
        $roleName = strtolower($usuario['nombre_rol']);
        $this->mensaje = strtoupper($roleName) . ": " . $usuario['nombre'];
        $_SESSION['mensaje'] = "Conexion exitosa, bienvenido a la plataforma";
        $_SESSION['alerta'] = "alert-success";
        
        $viewMap = [
            'superuser' => '../Views/Wellcome.php',
            'administrador' => '../Views/Wellcome.php',
            'vendedor' => '../Views/Wellcome.php',
            'cliente' => '../Views/Wellcome.php'
        ];
        
        if (!isset($viewMap[$roleName])) {
            throw new Exception("Rol no reconocido");
        }
        
        require($viewMap[$roleName]);
        unset($_SESSION['mensaje']);
        unset($_SESSION['alerta']);
    
        exit();
        

    }
    
    private function setErrorSession($message) {
        $_SESSION['mensaje'] = $message;
        $_SESSION['alerta'] = "alert-danger";
    }
    
    private function redirectToLogin() {
       
        header('Location: ../Views/LoginView.php');
        exit();
    }
}

// Uso del controlador
$con = new Conexion();
$authController = new AuthController($con);
$authController->handleRequest();