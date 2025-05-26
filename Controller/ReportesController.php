<?php
session_start();
require('../Model/Conexion.php');
require('Constants.php');

$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$db = new Conexion();

// Verificar permisos (descomentar cuando esté listo)

if ($_SESSION['usuario']['rol']['id_rol'] >= 2) {
    $_SESSION['error'] = "NO POSEES PERMISOS DE ADMINISTRADOR";
    header("Location: PrincipalController.php");
    exit();
}


try {

    // Manejar acciones AJAX
    if ($action === 'getDetalleReporte') {
        $id_reporte = $_GET['id'] ?? 0;
        $tipo_reporte = $_GET['tipo'] ?? '';

        $detalle = $db->getDetalleReporte($id_reporte, $tipo_reporte);
        $historial = $db->getHistorialReporte($id_reporte);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'reporte' => $detalle['reporte'] ?? null,
            'producto' => $detalle['producto'] ?? null,
            'usuario' => $detalle['usuario'] ?? null,
            'orden' => $detalle['orden'] ?? null,
            'historial' => $historial
        ]);
        exit();
    }

    if ($action === 'aplicarAccion' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_reporte = $_POST['id_reporte'] ?? 0;
        $accion = $_POST['accion'] ?? '';
        $id_admin = $_SESSION['usuario']['id_usuario'] ?? 1; // Cambiar por el ID real del admin

        $reporte = $db->getReporteById($id_reporte);

        if (!$reporte) {
            throw new Exception("Reporte no encontrado");
        }

        $resultado = false;
        $mensaje = '';

        switch ($accion) {
            case 'suspender_cuenta':
                if ($reporte['id_usuario_reportado']) {
                    $resultado = $db->suspenderUser($reporte['id_usuario_reportado']);
                    $mensaje = 'Cuenta suspendida temporalmente';
                    $db->actualizarReporte($id_reporte, 'SUSPENSIÓN DE CUENTA', 'RESUELTO', $mensaje);
                    
                    $db->registrarAccionReporte($id_reporte, $id_admin, 'SUSPENSIÓN', 'Cuenta suspendida por reporte');
                }
                break;

            case 'suspender_producto':
                if ($reporte['id_producto']) {
                    $resultado = $db->desactivarProd($reporte['id_producto']);
                    $mensaje = 'Producto suspendido temporalmente';
                    $db->actualizarReporte($id_reporte, 'SUSPENSIÓN DE PRODUCTO', 'RESUELTO', $mensaje);
                    $db->registrarAccionReporte($id_reporte, $id_admin, 'SUSPENSIÓN', 'Producto suspendido por reporte');
                }
                break;

            case 'enviar_aviso':
                $mensaje = 'Aviso enviado al usuario';
                $resultado = $db->actualizarReporte($id_reporte, 'AVISO ENVIADO', 'PENDIENTE', $mensaje);
                if($resultado){
                    console.log('Reporte Actualizado');
                }
                else {
                    console.log('Reporte no actualizado');
                }
                $db->registrarAccionReporte($id_reporte, $id_admin, 'AVISO', 'Aviso enviado al usuario');
                break;

            case 'resolver':
                $mensaje = 'Reporte marcado como resuelto';
                $resultado = $db->actualizarReporte($id_reporte, 'RESUELTO MANUALMENTE', 'RESUELTO', $mensaje);
                $db->registrarAccionReporte($id_reporte, $id_admin, 'RESOLUCIÓN', 'Reporte marcado como resuelto');
                break;

            case 'eliminar':
                // Aquí deberías implementar la lógica para eliminar el reporte si es necesario
                $mensaje = 'Reporte eliminado del sistema';
                $resultado = true; // Cambiar por la función real de eliminación
                $db->registrarAccionReporte($id_reporte, $id_admin, 'ELIMINACIÓN', 'Reporte eliminado del sistema');
                break;

            default:
                throw new Exception("Acción no válida");
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $resultado,
            'message' => $mensaje
        ]);
        exit();
    }

    $reportes = $db->getReportes();

    foreach ($reportes as &$reporte) {
        $detalle_reporte  = $db->getDetalleReporte($reporte['id_reporte']);
    }

    // reportes relacionados por prodcutos
    $reportesProductos = array_filter($reportes, function ($r) {
        return $r['tipo_reporte'] == 'PRODUCTO';
    });
    // reportes relacionados hacia vendedores
    $reportesVendedores = array_filter($reportes, function ($r) {
        return $r['tipo_reporte'] == 'VENDEDOR';
    });
    // reportes relacionados hacia usuarios
    $reportesUsuarios = array_filter($reportes, function ($r) {
        return $r['tipo_reporte'] == 'USUARIO';
    });
    //reportes relacionados hacia ordenes de clientes
    $reportesOrdenes = array_filter($reportes, function ($r) {
        return $r['tipo_reporte'] == 'ORDEN';
    });

    $data = [
        'reportesProductos' => $reportesProductos,
        'reportesVendedores' => $reportesVendedores,
        'reportesUsuarios' => $reportesUsuarios,
        'reportesOrdenes' => $reportesOrdenes,
        'usuario' => $_SESSION['usuario']['login']
    ];



    // Limpiar mensajes
    unset($_SESSION['mensaje']);
    unset($_SESSION['error']);

    require("../Views/ReportesRegistrados.php");
} catch (Exception $e) {
    if ($action === 'getDetalleReporte' || $action === 'aplicarAccion') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        exit();
    } else {
        $_SESSION['error'] = "ERROR AL OBTENER SOLICITUD: " . $e->getMessage();
        header("Location: ../Index.php");
        exit();
    }
}
