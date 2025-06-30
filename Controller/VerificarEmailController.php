<?php
session_start();
require('../Model/Conexion.php');

$email = $_GET['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['available' => false, 'error' => 'Formato inválido']);
    exit;
}

$conexion = new Conexion();
$existe = $conexion->searchUserByEmail($email);

echo json_encode(['available' => !$existe]);
?>