<?php
session_start();
require_once('../Model/Conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $con = new Conexion();
        
        /*Validar datos requeridos
        $required = ['id_producto', 'id_usuario_reportado', 'id_administrador', 
                    'motivo', 'accion_tomada', 'tipo_reporte'];
        
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }*/
        $verificar=$con->verificarReporte($_POST['id_producto']);
        if (!empty($verificar)){
            throw new Exception("Ya hay un reporte con este producto");
        }

        // 1. Registrar el reporte
        $respuesta = $con->newReport(
            $_POST['id_producto'] ??0,
            $_POST['id_usuario_reportado'],
            $_POST['id_administrador'],
            $_POST['motivo'],
            $_POST['comentarios'],
            $_POST['accion_tomada'],
            $_POST['tipo_reporte'],
            $estado='PROCESADO' // SI ES REPORTE DE PRODUCTO O DE USUARIO
        );
       

        
        if (!$respuesta) {
            throw new Exception("Error al crear el reporte");
        }

        // 2. Aplicar la acción correspondiente
        switch ($_POST['accion_tomada']) {
            case 'Advertencia':
                if ($respuesta){
                    $_SESSION['mensaje'] = "Advertencia enviada correctamente";
                }
                
                break;
            case 'Desactivar producto':
                if (!$con->desactivarProd($_POST['id_producto'])) {
                    throw new Exception("Error al desactivar el producto");
                }
                $_SESSION['mensaje'] = "Producto desactivado correctamente";
                break;
                
            case 'Suspender cuenta':
            case 'Banear cuenta':
                if (!$con->suspenderUser($_POST['id_usuario_reportado'])) {
                    throw new Exception("Error al suspender al usuario");
                }
                $_SESSION['mensaje'] = "Usuario suspendido correctamente";
                break;
        }
        
        $_SESSION['alerta'] = "alert-success";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        $_SESSION['alerta'] = "alert-danger";
    }
}

header("Location: ProductoController.php");
exit();