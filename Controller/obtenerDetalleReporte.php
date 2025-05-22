<?php
require_once '../Model/Conexion.php';
header('Content-Type: application/json');

$idReporte = $_GET['id'] ?? 0;
$tipoReporte = $_GET['tipo'] ?? '';

try {
    $db = new Conexion();
    
    // Obtener el reporte base
    $reporte = $db->getReporteById($idReporte);
    
    if (!$reporte) {
        throw new Exception("Reporte no encontrado");
    }
    
    $response = [
        'success' => true,
        'data' => [
            'reporte' => $reporte
        ]
    ];
    
    // Añadir información específica según el tipo
    switch(strtoupper($tipoReporte)) {
        case 'CLIENTE':
        case 'VENDEDOR':
            $usuario = $db->getUserById($reporte['id_usuario_reportado']);
            $response['data']['usuario'] = $usuario;
            $response['data']['administrador'] = $db->getUserById($reporte['id_administrador']);
            break;
            
        case 'PRODUCTO':
            $producto = $db->getProductoById($reporte['id_producto']);
            $response['data']['producto'] = $producto;
            $response['data']['vendedor'] = $db->getUserById($producto['id_usuario']);
            $response['data']['categoria'] = $db->getCategoriaById($producto['id_categoria']);
            break;
            
        case 'ORDEN':
            $orden = $db->getOrdenById($reporte['id_orden']);
            $response['data']['orden'] = $orden;
            $response['data']['cliente'] = $db->getUserById($orden['id_usuario']);
            $response['data']['vendedor'] = $db->getUserById($orden['id_vendedor']);
            $response['data']['detalles'] = $db->getDetallesOrdenCompleto($orden['id_orden']);
            break;
    }
    
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);