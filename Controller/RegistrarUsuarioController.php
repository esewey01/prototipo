<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

class UserRegistrationController {
    private $conexion;
    private $errors = [];
    private $messages = [];
    private $defaultPhoto = 'fotoproducto/user.png';
    
    public function __construct(Conexion $conexion) {
        $this->conexion = $conexion;
    }
    
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Método no permitido', '../Views/RegistrarUsuarioView.php');
            return;
        }
        
        try {
            $this->processRegistration();
        } catch (Exception $e) {
            $this->redirectWithError($e->getMessage(), '../Views/RegistrarUsuarioView.php');
        }
    }
    
    private function processRegistration() {
        $this->validateConnection();
        
        $userData = $this->getUserDataFromPost();
        $this->validateInputs($userData);
        
        if (!empty($this->errors)) {
            $this->showMessages(false);
            return;
        }
        
        $this->processPhotoUpload($userData);
        $registrationResult = $this->registerUser($userData);
        
        if ($registrationResult === 'REGISTRADO') {
            $this->storePendingRegistration($userData);
            $this->messages[] = "Registro completado";
            $this->showMessages(true);
        } else {
            $this->errors[] = $registrationResult;
            $this->showMessages(false);
        }
    }
    
    private function getUserDataFromPost() {
        return [
            'nombre' => $_POST['nombre'] ?? '',
            'login' => trim($_POST['login'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password2' => $_POST['password2'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'foto' => $_FILES['foto']['name'] ?? '',
            'tipo' => 'CLIENTE',
            'fotoPath' => $this->defaultPhoto
        ];
    }
    
    private function validateConnection() {
        if (!$this->conexion) {
            throw new Exception("Error de conexión a la base de datos");
        }
    }
    
    private function validateInputs($userData) {
        $this->validateRequiredFields($userData);
        $this->validatePhone($userData['telefono']);
        $this->validateLogin($userData['login']);
        $this->validatePasswords($userData['password'], $userData['password2']);
    }
    
    private function validateRequiredFields($userData) {
        $requiredFields = ['login', 'password', 'password2', 'nombre', 'telefono'];
        
        foreach ($requiredFields as $field) {
            if (empty($userData[$field])) {
                $this->errors[] = "Ningún campo debe estar vacío";
                break;
            }
        }
    }
    
    private function validatePhone($phone) {
        if (preg_match('/^[0-9]\d{10}$/', $phone) === false) {
            $this->errors[] = "El teléfono debe tener 10 dígitos y solo contener números";
            return;
        }
        
        if (!$this->validatePhoneNumber($phone)) {
            $this->errors[] = "El teléfono no es válido";
        } else {
            $this->messages[] = "Teléfono válido";
        }
    }
    
    private function validatePhoneNumber($phone) {
        $URL = "http://apilayer.net/api/validate";
        $access_key = "a6df017f3f16d30cdde0ec268fe259ec";
        $country_code = "MX";
        $format = 1;
        
        $query = http_build_query([
            'access_key' => $access_key,
            'number' => $phone,
            'country_code' => $country_code,
            'format' => $format
        ]);
        
        $apiUrl = $URL . "?" . $query;
        $response = @file_get_contents($apiUrl);
        
        if ($response === false) {
            return false;
        }
        
        $data = json_decode($response, true);
        return $data['valid'] ?? false;
    }
    
    private function validateLogin($login) {
        if (preg_match('/^[0-9]\d{10}$/', $login) === false) {
            $this->errors[] = "La boleta solo debe tener 10 dígitos y solo contener números";
        }
    }
    
    private function validatePasswords($password, $password2) {
        if ($password !== $password2) {
            $this->errors[] = "Las contraseñas no coinciden";
        }/*
        $minLength = 8;
        if (strlen($password) < $minLength) {
            $this->errors[] = "La contraseña debe tener al menos " . $minLength . " caracteres";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $this->errors[] = "La contraseña debe contener al menos una letra mayúscula";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $this->errors[] = "La contraseña debe contener al menos una letra minúscula";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $this->errors[] = "La contraseña debe contener al menos un número";
        }

        if (!preg_match('/[^a-zA-Z0-9\s]/', $password)) {
            $this->errors[] = "La contraseña debe contener al menos un carácter especial";
        }*/
    }
    
    private function processPhotoUpload(&$userData) {
        if (!isset($_FILES['foto']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return;
        }
        
        $foto = $_FILES['foto'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['image/jpeg', 'image/png'];
        
        if ($foto['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = "Error al subir la imagen";
            return;
        }
        
        if ($foto['size'] > $maxSize) {
            $this->errors[] = "La imagen es demasiado grande (máximo 2MB)";
            return;
        }
        
        if (!in_array($foto['type'], $allowedTypes)) {
            $this->errors[] = "Solo se permiten imágenes JPG o PNG";
            return;
        }
        
        $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $userData['fotoPath'] = '../Views/fotoproducto/' . uniqid() . '.' . $extension;
        
        if (!move_uploaded_file($foto['tmp_name'], $userData['fotoPath'])) {
            $this->errors[] = "Error al guardar la imagen";
            $userData['fotoPath'] = $this->defaultPhoto;
        }
    }
    
    private function registerUser($userData) {
        try {
            $existingUser = $this->conexion->searchUser($userData['login']);
            
            if (!empty($existingUser)) {
                return "El usuario ya existe";
            }
            
            $id_rol = 3; // Rol de cliente
            $registered = $this->conexion->registerUserWithRole(
                $userData['nombre'],
                $userData['login'],
                $userData['password'],
                $userData['telefono'],
                $userData['fotoPath'],
                $id_rol
            );
            
            return $registered ? "REGISTRADO" : "Error al registrar el usuario";
        } catch (Exception $e) {
            return "Error al verificar el usuario";
        }
    }
    
    private function storePendingRegistration($userData) {
        $_SESSION['registro_pendiente'] = [
            'nombre' => $userData['nombre'],
            'login' => $userData['login'],
            'password' => $userData['password'],
            'foto' => $userData['fotoPath'],
            'telefono' => $userData['telefono']
        ];
    }
    
    private function showMessages($isSuccess) {
        $_SESSION['registration_messages'] = [
            'text' => implode('<br>', $isSuccess ? $this->messages : $this->errors),
            'type' => $isSuccess ? 'success' : 'danger'
        ];
        
        $redirectUrl = $isSuccess ? '../Views/LoginView.php' : '../Views/RegistrarUsuarioView.php';
        header("Location: $redirectUrl");
        exit();
    }
    
    private function redirectWithError($message, $url) {
        $_SESSION['registration_messages'] = [
            'texto' => $message,
            'tipo' => 'danger'
        ];
        header("Location: $url");
        exit();
    }
}

// Uso del controlador
$con = new Conexion();
$controller = new UserRegistrationController($con);
$controller->handleRequest();