<?php
session_start();
require_once('../Model/Conexion.php');
require('Constants.php');

$db = new Conexion();
$action = $_GET['action'] ?? '';

// Verificar autenticación y rol de vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol']['id_rol'] != 2) {
    header('Location: LoginController.php');
    exit;
}

$vendedor_id = $_SESSION['usuario']['id_usuario'];

try {
    switch ($action) {
        case 'actualizar_estado':
            // Verificar que sea una solicitud POST
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception("Método no permitido");
            }

            $id_orden = $_POST['id_orden'] ?? 0;
            $nuevo_estado = $_POST['estado'] ?? '';
            
            // Validar estados permitidos
            $estados_permitidos = ['PAGADO', 'ENTREGADO', 'CANCELADO'];
            if (!in_array($nuevo_estado, $estados_permitidos)) {
                throw new Exception("Estado no válido");
            }

            // Verificar que la orden pertenece a este vendedor
            $orden = $db->getOrdenById($id_orden);
            if (!$orden || $orden['id_vendedor'] != $vendedor_id) {
                throw new Exception("No tienes permiso para modificar esta orden");
            }

            // Actualizar el estado
            $sql = "UPDATE ORDENES SET estado = ?, fecha_actualizacion = GETDATE() WHERE id_orden = ?";
            $result = $db->executeNonQuery($sql, [$nuevo_estado, $id_orden]);

            if ($result) {
                $_SESSION['success'] = "Estado de la orden actualizado correctamente";
            } else {
                throw new Exception("Error al actualizar el estado");
            }

            header('Location: VentasController.php');
            exit;

        default:
            // Obtener órdenes asignadas a este vendedor
            $filtro = $_GET['estado'] ?? null;
            $ordenes = $db->getHistorialVendedor($vendedor_id, $filtro);
            
            include('../Views/VentasView.php');
            break;
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: VentasController.php');
    exit;
}
?>