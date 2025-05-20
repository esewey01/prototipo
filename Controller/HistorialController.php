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
            $this->validarAutenticacion();
            $id_orden = $_POST['id_orden'] ?? 0;
            $motivo = $_POST['motivo'] ?? '';

            if (empty($motivo)) {
                throw new Exception("Debes especificar un motivo");
            }

            $orden = $db->getOrdenById($id_orden);
            if ($orden['id_usuario'] != $usuario['id_usuario']) {
                throw new Exception("No tienes permiso para reportar esta orden");
            }

            // Obtener administrador activo (podrías implementar lógica para asignar uno)
            $admin = $db->getRow("SELECT TOP 1 id_usuario FROM USUARIOS WHERE id_rol = 1 AND activo = 1");

            if (!$admin) {
                throw new Exception("No hay administradores disponibles para procesar el reporte");
            }

            // Insertar reporte usando tu estructura existente
            // Crear reporte usando el nuevo método
            $success = $this->conexion->crearReporte(
                tipo: 'USUARIO',
                id_usuario_reportado: $orden['id_vendedor'],
                id_administrador: $admin['id_usuario'],
                motivo: "Problema con orden #$id_orden",
                comentarios: "El cliente reportó: " . $motivo . "\n\nOrden: " . json_encode($orden, JSON_PRETTY_PRINT)
            );

            if (!$success) {
                throw new Exception("Error al registrar el reporte");
            }


            $resultado = ['success' => true, 'message' => 'Reporte enviado correctamente'];
            header('Content-Type: application/json');
            echo json_encode($resultado);
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
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    } else {
        $_SESSION['error'] = $e->getMessage();
        header('Location: PrincipalController.php');
    }
    exit;
}
