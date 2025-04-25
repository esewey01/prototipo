<?php
require_once('../Model/Conexion.php');

$id = $_GET['id'] ?? 0;
$tipo = $_GET['tipo'] ?? 'PRODUCTO';

$con = new Conexion();

if ($tipo == 'PRODUCTO') {
    $query = "SELECT r.*, p.nombre_producto, u.nombre as nombre_usuario, a.nombre as nombre_administrador 
              FROM REPORTES r
              JOIN PRODUCTOS p ON r.id_producto = p.id_producto
              JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
              JOIN USUARIOS a ON r.id_administrador = a.id_usuario
              WHERE r.id_reporte = ?";
} else {
    $query = "SELECT r.*, u.nombre as nombre_usuario, a.nombre as nombre_administrador 
              FROM REPORTES r
              JOIN USUARIOS u ON r.id_usuario_reportado = u.id_usuario
              JOIN USUARIOS a ON r.id_administrador = a.id_usuario
              WHERE r.id_reporte = ?";
}

$reporte = $con->getRow($query, [$id]);

if (!$reporte) {
    die("<div class='alert alert-danger'>Reporte no encontrado</div>");
}
?>

<div class="row">
    <div class="col-md-6">
        <h4>Información del Reporte</h4>
        <p><strong>ID:</strong> <?= $reporte['id_reporte'] ?></p>
        <p><strong>Fecha:</strong> <?=$reporte['fecha_reporte']->format('d/m/Y H:i')?></p>
        <p><strong>Estado:</strong> 
            <span class="label label-<?= 
                ($reporte['estado'] == 'PENDIENTE') ? 'warning' : 
                (($reporte['estado'] == 'PROCESADO') ? 'info' : 'success') 
            ?>">
                <?= $reporte['estado'] ?>
            </span>
        </p>
        <p><strong>Motivo:</strong> <?= htmlspecialchars($reporte['motivo']) ?></p>
        <p><strong>Acción Tomada:</strong> <?= htmlspecialchars($reporte['accion_tomada']) ?></p>
    </div>
    
    <div class="col-md-6">
        <h4>Información del <?= $tipo == 'PRODUCTO' ? 'Producto' : 'Usuario' ?></h4>
        <?php if ($tipo == 'PRODUCTO'): ?>
            <p><strong>Producto:</strong> <?= htmlspecialchars($reporte['nombre_producto']) ?></p>
        <?php else: ?>
            <p><strong>Usuario:</strong> <?= htmlspecialchars($reporte['nombre_usuario']) ?></p>
        <?php endif; ?>
        
        <h4>Información del Administrador</h4>
        <p><strong>Reportado por:</strong> <?= htmlspecialchars($reporte['nombre_administrador']) ?></p>
        
        <h4>Comentarios</h4>
        <div class="well"><?= nl2br(htmlspecialchars($reporte['comentarios'])) ?></div>
    </div>
</div>