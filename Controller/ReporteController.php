<?php
require_once('../Model/Conexion.php');
session_start();

// Configurar cabeceras para respuesta JSON
header('Content-Type: application/json');

try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido', 405);
    }

    // Obtener acción
    $action = $_GET['action'] ?? '';
    
    if ($action === 'reportarUsuario') {
        // Obtener datos del POST
        $data = $_POST; // Usamos directamente $_POST en lugar de json_decode
        
        // Validar datos requeridos
        $required = ['id_usuario_reportado', 'id_administrador', 'motivo', 'tipo_reporte'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Campo requerido faltante: $field", 400);
            }
        }

        // Conectar a la base de datos
        $conexion = new Conexion();
        
        // Insertar reporte con valores por defecto
        $result = $conexion->newReport(
            null, // id_producto (null para reportes de usuario)
            $data['id_usuario_reportado'],
            $data['id_administrador'],
            $data['motivo'],
            'PENDIENTE', // accion_tomada por defecto
            $data['comentarios'] ?? '',
            $data['tipo_reporte'],
            'PENDIENTE' // estado
        );

        if (!$result) {
            throw new Exception('Error al guardar el reporte en la base de datos', 500);
        }

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => 'Reporte guardado correctamente'
        ]);
        exit;
    }
    
    throw new Exception('Acción no válida', 400);
    
} catch (Exception $e) {
    // Manejo de errores
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
    exit;
}