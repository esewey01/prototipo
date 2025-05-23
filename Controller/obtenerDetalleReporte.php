<?php
require_once('../Model/Conexion.php');

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    $id = $_GET['id'] ?? null;
    $tipo = strtoupper($_GET['tipo'] ?? '');
    
    if (!$id || !in_array($tipo, ['PRODUCTO', 'VENDEDOR', 'USUARIO', 'ORDEN'])) {
        throw new Exception("Parámetros inválidos");
    }

    $db = new Conexion();
    $data = $db->getDetalleReporte($id, $tipo);
    
    if (!$data) {
        throw new Exception("No se encontró el reporte");
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'debug' => [
            'params_received' => ['id' => $id, 'tipo' => $tipo],
            'reporte_type_in_db' => $data['reporte']['tipo_reporte'] ?? 'UNKNOWN'
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'params_received' => $_GET,
            'error' => $e->getTraceAsString()
        ]
    ]);
}