<?php
session_start();
require_once('../Model/Conexion.php');


try {
    $con = new Conexion();
    
    // Validación básica de datos
    $required_fields = ['nombre', 'login', 'password', 'email', 'id_rol'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("El campo $field es requerido");
        }
    }

    // Asignación de datos
    $nombre = trim($_POST['nombre']);
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $id_rol = $_POST['id_rol'];
    
    // Procesamiento de imagen
    $foto_perfil = 'user.png';
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $file_info = pathinfo($_FILES['foto_perfil']['name']);
        if (in_array(strtolower($file_info['extension']), ['jpg', 'jpeg', 'png'])) {
            $nombre_archivo = uniqid() . '.' . $file_info['extension'];
            $ruta_destino = '../fotoproducto/' . $nombre_archivo;
            
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_destino)) {
                $foto_perfil = $nombre_archivo;
            }
        }
    }

    // Llamada al método del modelo
    $id_usuario = $con->createUserWithRole($nombre, $login, $password, $foto_perfil, $email, $id_rol);

    $_SESSION['mensaje'] = "Usuario registrado correctamente (ID: $id_usuario)";
    $_SESSION['alerta'] = "alert-success";

} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error: " . $e->getMessage();
    $_SESSION['alerta'] = "alert-danger";
}
require("UsuariosController.php");
exit();