<?php
session_start();
require('../Model/Conexion.php');

if (!isset($_SESSION['pre_registro'])) {
    header("Location: RegistrarUsuarioView.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $con = new Conexion();
    $errores = [];

    // Validar teléfono con API (ejemplo usando NumVerify)
    $telefono = $_POST['telefono'] ?? '';
    if (!validarTelefono($telefono)) {
        $errores[] = "Número de teléfono inválido";
    }

    // Validar nombre
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre) || strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 caracteres";
    }

    // Validar imagen
    $foto = $_FILES['foto'] ?? null;
    $nombreFoto = null;
    
    if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
        if ($foto['size'] > 2 * 1024 * 1024) { // 2MB máximo
            $errores[] = "La imagen no debe pesar más de 2MB";
        } else {
            $extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $errores[] = "Formato de imagen no válido (solo JPG, PNG o GIF)";
            } else {
                $nombreFoto = 'user_' . $_SESSION['pre_registro']['login'] . '.' . $extension;
                move_uploaded_file($foto['tmp_name'], '../uploads/' . $nombreFoto);
            }
        }
    }

    if (empty($errores)) {
        if ($con->getRegisterNewUser(
            $_SESSION['pre_registro']['login'],
            $_SESSION['pre_registro']['tipo'],
            $_SESSION['pre_registro']['password'],
            $nombre,
            $telefono,
            $nombreFoto
        )) {
            unset($_SESSION['pre_registro']);
            $_SESSION['registro_exitoso'] = true;
            header("Location: LoginView.php");
            exit;
        } else {
            $errores[] = "Error al completar el registro";
        }
    }

    $_SESSION['errores_completar'] = $errores;
    header("Location: CompletarRegistroView.php");
    exit;
}

function validarTelefono($numero) {
    // Implementación básica - reemplazar con API real
    if (preg_match('/^[0-9]{10,15}$/', $numero)) {
        // Opción 1: Validación local (solo formato)
        return true;
        
        // Opción 2: Usar API como NumVerify (requiere API key)
        // $apiKey = 'TU_API_KEY';
        // $url = "http://apilayer.net/api/validate?access_key=$apiKey&number=$numero";
        // $response = file_get_contents($url);
        // $data = json_decode($response, true);
        // return $data['valid'] ?? false;
    }
    return false;
}