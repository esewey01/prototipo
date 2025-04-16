<?php
session_start();
require('../Model/Conexion.php');

try {
    $con = new Conexion();
    
    // Recoger datos del formulario
    $nombre = $_POST['nombre'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $id_rol = $_POST['id_rol'];
    $email = $_POST['email'];
    
    // Procesar imagen
    $foto_perfil = 'user.png'; // Valor por defecto
    
    if(isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $extensiones_permitidas = ['jpg', 'jpeg', 'png'];
        
        if(in_array(strtolower($extension), $extensiones_permitidas)) {
            $nombre_archivo = uniqid().'.'.$extension;
            $ruta_destino = '../fotoproducto/'.$nombre_archivo;
            
            if(move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_destino)) {
                $foto_perfil = $nombre_archivo;
            }
        }
    }
    
    // Insertar en tabla USUARIOS
    $sql_usuario = "INSERT INTO USUARIOS 
                    (nombre, login, password, foto_perfil, email, fecha_registro) 
                    VALUES (?, ?, ?, ?, ?, GETDATE())";
    
    $params = [$nombre, $login, $password, $foto_perfil, $email];
    $con->executeNonQuery($sql_usuario, $params);
    
    // Obtener ID del nuevo usuario
    $id_usuario = $con->getLastInsertId();
    
    // Insertar en ROLES_USUARIO
    $sql_rol = "INSERT INTO ROLES_USUARIO (id_usuario, id_rol) VALUES (?, ?)";
    $con->executeNonQuery($sql_rol, [$id_usuario, $id_rol]);
    
    $_SESSION['mensaje'] = "Usuario agregado correctamente";
    $_SESSION['alerta'] = "alert-success";
    
} catch (Exception $e) {
    $_SESSION['mensaje'] = "Error al agregar usuario: ".$e->getMessage();
    $_SESSION['alerta'] = "alert-danger";
}

header("Location: ../Views/UsuarioViewFinal.php");
exit();