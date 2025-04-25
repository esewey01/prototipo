<?php
session_start();
require_once('../Model/Conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    try {
        $con = new Conexion();
        
        // Actualizar estado de verificación
        $verificado = isset($_POST['verificado']) ? 1 : 0;
        
        $query = "UPDATE USUARIOS SET verificado = ? WHERE id_usuario = ?";
        $params = [$verificado, $_POST['id_usuario']];
        
        $resultado = $con->executeQuery($query, $params);
        
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