<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');

$db = new Conexion();
$action = $_GET['action'] ?? '';

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    header('Location: LoginController.php');
    exit;
}

$usuario = $_SESSION['usuario'];

try {
    switch ($action) {
        case 'ver':
            $id_orden = $_GET['id'] ?? 0;
            $orden = $db->getOrdenById($id_orden);

            // Verificar que la orden pertenece al cliente
            if ($orden['id_usuario'] != $usuario['id_usuario']) {
                throw new Exception("No tienes permiso para ver esta orden");
            }

            $detalles = $db->getDetallesOrdenCompleto($id_orden);

            // Asegurar que los valores numéricos sean float
            $orden['total'] = (float)$orden['total'];
            foreach ($detalles as &$detalle) {
                $detalle['precio_unitario'] = (float)$detalle['precio_unitario'];
            }

            // Devolver JSON para el modal
            header('Content-Type: application/json');
            echo json_encode([
                'orden' => $orden,
                'detalles' => $detalles,
                'fecha_formateada' => $orden['fecha_orden']->format('d/m/Y H:i')
            ]);
            exit;

        case 'eliminar':
            $id_orden = $_POST['id_orden'] ?? 0;
            $orden = $db->getOrdenById($id_orden);

            // Verificar que la orden pertenece al cliente y está pagada
            if ($orden['id_usuario'] != $usuario['id_usuario'] || $orden['estado'] != 'PAGADO') {
                throw new Exception("No puedes eliminar esta orden");
            }

            // Marcar como cancelada
            $db->executeNonQuery(
                "UPDATE ORDENES SET estado = 'CANCELADO' WHERE id_orden = ?",
                [$id_orden]
            );

            $resultado = ['success' => true, 'message' => 'Orden cancelada correctamente'];
            header('Content-Type: application/json');
            echo json_encode($resultado);
            exit;

        case 'reportar':
            header('Content-Type: application/json'); // Mover al inicio para asegurar que todas las respuestas son JSON

            try {
                $id_orden = $_POST['id_orden'] ?? 0;
                $motivo = $_POST['motivo'] ?? '';

                if (empty($motivo)) {
                    throw new Exception("Debes especificar un motivo");
                }

                $orden = $db->getOrdenById($id_orden);
                if (!$orden) {
                    throw new Exception("Orden no encontrada");
                }

                if ($orden['id_usuario'] != $usuario['id_usuario']) {
                    throw new Exception("No tienes permiso para reportar esta orden");
                }

                if ($orden['estado'] != 'PENDIENTE') {
                    throw new Exception("Solo puedes reportar órdenes pendientes");
                }
/*
                $admin = $db->obtenerAdministradorActivo();
                if (!$admin) {
                    throw new Exception("No hay administradores disponibles");
                }*/

                $success = $db->crearReporte(
                    tipo: 'ORDEN',
                    id_orden: $id_orden,
                    id_usuario_reportado: $orden['id_vendedor'],
                    id_administrador: $_SESSION['usuario']['id_usuario'],
                    motivo: "Problema con orden #$id_orden - Vendedor no actualizó estado",
                    comentarios: "El cliente reportó: " . $motivo . "\n\nDetalles:\n" .
                        "ID: " . $orden['id_orden'] . "\n" .
                        "Fecha: " . $orden['fecha_orden']->format('d/m/Y H:i') . "\n" .
                        "Vendedor: " . ($orden['vendedor_nombre'] ?? 'N/A') . "\n" .
                        "Total: $" . number_format($orden['total'], 2)
                );

                if (!$success) {
                    throw new Exception("Error al registrar el reporte");
                }

                echo json_encode([
                    'success' => true,
                    'message' => 'Reporte enviado correctamente'
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        default:
            // Solo mostrar órdenes del cliente
            $filtro = $_GET['estado'] ?? null;
            $ordenes = $db->getHistorialCliente($usuario['id_usuario'], $filtro);

            include('../Views/HistorialView.php');
            break;
    }
} catch (Exception $e) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
         $_SESSION['error'] = $e->getMessage();
        //header('Content-Type: application/json');
        //echo json_encode(['error' => $e->getMessage()]);
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: PrincipalController.php');
    }
    exit;
}
