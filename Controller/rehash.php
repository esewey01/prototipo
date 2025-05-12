<?php
require('Conexion.php');

$con = new Conexion();

// Lista de usuarios con sus contraseñas originales
$usuarios = [
    ['login' => 'usuario1', 'password' => 'clave1'],
    ['login' => 'usuario2', 'password' => 'clave2'],
    // Agrega todos tus usuarios reales aquí
];

foreach ($usuarios as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
    
    $sql = "UPDATE USUARIOS SET password = ? WHERE login = ?";
    $params = [$hashedPassword, $user['login']];

    $con->executeNonQuery($sql, $params);
}

echo "Contraseñas actualizadas correctamente.";
