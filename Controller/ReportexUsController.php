<?php
session_start();
require_once('../Model/Conexion.php');

// Configuración de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log personalizado
file_put_contents('reportes.log', date('Y-m-d H:i:s')." - Inicio\n", FILE_APPEND);

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$db = new Conexion();

try {
    switch ($action) {
        case 'reportarProducto':
            // Registrar datos recibidos
            $input = file_get_contents('php://input');
            file_put_contents('reportes.log', "Raw input: ".$input."\n", FILE_APPEND);
            
            // Decodificar datos (tanto para JSON como FormData)
            if(strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
                $data = json_decode($input, true) ?? [];
            } else {
                $data = $_POST;
            }
            
            file_put_contents('reportes.log', "Datos decodificados: ".print_r($data, true)."\n", FILE_APPEND);

            // Verificar sesión
            if (!isset($_SESSION['usuario'])) {
                echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para reportar']);
                exit;
            }

            

            // Obtener datos
            $id_producto = $data['id_producto'] ?? 0;
            $motivo = $data['motivo'] ?? '';
            $comentarios = $data['comentarios'] ?? '';

            file_put_contents('reportes.log', "ID Producto: $id_producto\n", FILE_APPEND);
            // Verificar si el usuario ya reportó este producto
            if ($db->verificarReporteExistente($_SESSION['usuario']['id_usuario'], $id_producto)) {
                echo json_encode(['success' => false, 'message' => 'Ya has reportado este producto anteriormente']);
                exit;
            }

            if ($id_producto <= 0) {
                echo json_encode(['success' => false, 'message' => 'Producto inválido (ID: '.$id_producto.')']);
                exit;
            }

            if (empty($motivo) || empty($comentarios)) {
                echo json_encode(['success' => false, 'message' => 'Debes completar todos los campos']);
                exit;
            }

            // Obtener ID del vendedor
            $id_vendedor = $db->idVendedor($id_producto);
            file_put_contents('reportes.log', "ID Vendedor: ".($id_vendedor ?? 'No encontrado')."\n", FILE_APPEND);
            
            if (!$id_vendedor) {
                echo json_encode(['success' => false, 'message' => 'No se encontró el vendedor del producto']);
                exit;
            }

            

            $success = $db->newReportforUser(
                $id_producto,
                $id_vendedor,
                $_SESSION['usuario']['id_usuario'],
                $motivo,
                $comentarios,
                "Reporte creado por usuario"
            );

            if(!$success) {
                $lastError = print_r(sqlsrv_errors(), true);
                file_put_contents('reportes.log', "Error SQL: ".$lastError."\n", FILE_APPEND);
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Error en base de datos',
                    'sql_error' => $lastError // Solo para desarrollo
                ]);
                exit;
            }

            file_put_contents('reportes.log', "Resultado operación: ".($success ? 'Éxito' : 'Falló')."\n", FILE_APPEND);
            
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Reporte enviado correctamente' : 'Error al enviar reporte'
            ]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
} catch (Exception $e) {
    file_put_contents('reportes.log', "Excepción: ".$e->getMessage()."\n", FILE_APPEND);
    echo json_encode([
        'success' => false, 
        'message' => 'Error en el servidor',
        'error' => $e->getMessage()
    ]);
}
?>