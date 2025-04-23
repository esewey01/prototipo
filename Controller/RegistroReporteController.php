<?php
session_start();
require_once('../Model/Conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    try {
        $con = new Conexion();
        
        // 1. Registrar el reporte
        $respuesta= $con->newReport(
        $_POST['id_producto'],
        $_POST['id_usuario_reportado'],
        $_POST['id_administrador'],
        $_POST['motivo'],
        $_POST['comentarios'],
        $_POST['accion_tomada']);

        if($respuesta){
            $_SESSION['mensaje'] = "Reporte creado con exito";

        }else{
            $_SESSION['error'] = "Error al crear el reporte";
        }

        // 2. Aplicar la acción correspondiente
        switch ($_POST['accion_tomada']) {
            case 'Desactivar producto':
                $respuesta=$con ->desactivarProd($_POST['id_producto']);
                if($respuesta)
                {
                    $_SESSION['mensaje'] = "Producto desactivado correctamente";
                }
                else{
                    $_SESSION['error'] = "Error al crear el producto";
                }
                break;
                
            case 'Suspender cuenta':
            case 'Banear cuenta':
                $respuesta=$con->suspenderUser($_POST['id_usuario_reportado']);
                if ($respuesta){
                    $_SESSION['mensaje'] = "Usuario baneado correctamente";
                }
                else{
                    $_SESSION['error'] = "Error al reportar al usuario";
                }
                
                break;
        }
        
        $_SESSION['mensaje'] = "Reporte registrado y acciones aplicadas correctamente";
        $_SESSION['alerta']="alert-success";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al procesar el reporte: " . $e->getMessage();
        $_SESSION['alerta']="alert-danger";
    }
}

header("Location: ProductoController.php");
exit();