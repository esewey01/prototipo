<?php
require_once('../Model/Conexion.php');
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_GET['id']) || !isset($_GET['tipo'])) {
        throw new Exception('Parámetros faltantes', 400);
    }

    $idReporte = $_GET['id'];
    $tipoReporte = $_GET['tipo'];
    
    $conexion = new Conexion();
    $detalle = $conexion->getDetalleReporte($idReporte, $tipoReporte);
    
    if (!$detalle) {
        throw new Exception('Reporte no encontrado', 404);
    }
    
    // Formatear datos para la plantilla
    $response = [
        'success' => true,
        'data' => [
            'reporte' => [
                'id' => $detalle['reporte']['id_reporte'],
                'fecha_reporte' => $detalle['reporte']['fecha_reporte'],
                'estado' => $detalle['reporte']['estado'],
                'motivo' => $detalle['reporte']['motivo'],
                'comentarios' => $detalle['reporte']['comentarios'],
                'administrador' => [
                    'id' => $detalle['reporte']['id_administrador'],
                    'nombre' => $detalle['reporte']['nombre_administrador']
                ]
            ]
        ]
    ];
    
    // Agregar datos específicos según el tipo de reporte
    switch ($tipoReporte) {
        case 'PRODUCTO':
            if (isset($detalle['producto'])) {
                $response['data']['producto'] = [
                    'id_producto' => $detalle['producto']['id_producto'],
                    'nombre_producto' => $detalle['producto']['nombre_producto'],
                    'codigo' => $detalle['producto']['codigo'],
                    'descripcion' => $detalle['producto']['descripcion'],
                    'cantidad' => $detalle['producto']['cantidad'],
                    'precio_venta' => $detalle['producto']['precio_venta'],
                    'precio_compra' => $detalle['producto']['precio_compra'],
                    'imagen' => $detalle['producto']['imagen'],
                    'vendedor' => [
                        'id' => $detalle['producto']['id_usuario'],
                        'nombre' => $detalle['producto']['nombre_vendedor']
                    ],
                    'categoria' => [
                        'nombre' => $detalle['producto']['nombre_categoria']
                    ]
                ];
            }
            break;
            
        case 'USUARIO':
        case 'VENDEDOR':
            if (isset($detalle['usuario'])) {
                $response['data']['usuario'] = [
                    'id_usuario' => $detalle['usuario']['id_usuario'],
                    'nombre' => $detalle['usuario']['nombre'],
                    'email' => $detalle['usuario']['email'],
                    'telefono' => $detalle['usuario']['telefono'],
                    'foto_perfil' => $detalle['usuario']['foto_perfil'],
                    'fecha_registro' => $detalle['usuario']['fecha_registro']
                ];
            }
            break;
            
        case 'ORDEN':
            if (isset($detalle['orden'])) {
                $response['data']['orden'] = [
                    'id_orden' => $detalle['orden']['id_orden'],
                    'fecha_orden' => $detalle['orden']['fecha_orden'],
                    'total' => $detalle['orden']['total'],
                    'estado' => $detalle['orden']['estado'],
                    'vendedor' => [
                        'id' => $detalle['orden']['id_vendedor'],
                        'nombre' => $detalle['orden']['nombre_vendedor']
                    ],
                    'cliente' => [
                        'id' => $detalle['orden']['id_usuario'],
                        'nombre' => $detalle['orden']['nombre_cliente']
                    ],
                    'detalles' => isset($detalle['orden']['detalles']) ? $detalle['orden']['detalles'] : []
                ];
            }
            break;
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}