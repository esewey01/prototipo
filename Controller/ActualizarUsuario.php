<?php
session_start();
require_once('../Model/Conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    try {
        $con = new Conexion();
        
        // Actualizar estado de verificación
        $resultado = isset($_POST['verificado'], $_POST['id_usuario']) ;

        if ($resultado) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente";
            $_SESSION['alerta'] = "alert-success";
        } else {
            throw new Exception("Error al actualizar el usuario");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        $_SESSION['alerta'] = "alert-danger";
    }
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>