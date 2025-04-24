<?php
session_start();
require_once('../Model/Conexion.php');

// 1. Validar sesión
if (!isset($_SESSION['usuario'])) {
    die(json_encode(['error' => 'Acceso no autorizado']));
}

// 2. Validar y obtener ID
$idReporte = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idReporte <= 0) {
    die(json_encode(['error' => 'ID de reporte inválido']));
}

// 3. Obtener datos
$con = new Conexion();
$reporte = $con->getDetalleReporte($idReporte);


// 4. Verificar si se encontró el reporte
if (!$reporte) {
    die(json_encode(['error' => 'Reporte no encontrado']));
}

// Función auxiliar para mostrar datos
function mostrarCampo($valor, $esFecha = false) {
    if ($valor === null) return 'N/A';
    if ($esFecha && $valor instanceof DateTime) {
        return $valor->format('d/m/Y H:i');
    }
    return htmlspecialchars($valor);
}
?>

<div class="row">
    <div class="col-md-6">
        <h4>Información del Reporte</h4>
        <p><strong>ID:</strong> <?= mostrarCampo($reporte[0]['id_reporte']) ?></p>
        <p><strong>Fecha:</strong> <?= mostrarCampo($reporte[0]['fecha_reporte'], true) ?></p>
        <p><strong>Estado:</strong> 
            <span class="label label-<?= 
                $reporte[0]['estado'] == 'PENDIENTE' ? 'warning' : 
                ($reporte[0]['estado'] == 'PROCESADO' ? 'success' : 'default')
            ?>">
                <?= mostrarCampo($reporte[0]['estado']) ?>
            </span>
        </p>
    </div>
    
    <div class="col-md-6">
        <h4>Acciones</h4>
        <p><strong>Acción tomada:</strong> <?= mostrarCampo($reporte[0]['accion_tomada']) ?></p>
        <p><strong>Administrador:</strong> <?= mostrarCampo($reporte[0]['nombre_administrador']) ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Detalles</h4>
        <div class="well">
            <p><strong>Producto:</strong> <?= mostrarCampo($reporte[0]['nombre_producto']) ?></p>
            <p><strong>Usuario reportado:</strong> <?= mostrarCampo($reporte[0]['nombre_reportado']) ?></p>
            <p><strong>Motivo:</strong> <?= mostrarCampo($reporte[0]['motivo']) ?></p>
            <?php if (!empty($reporte[0]['comentarios'])): ?>
                <p><strong>Comentarios:</strong> <?= mostrarCampo($reporte[0]['comentarios']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($reporte[0]['estado'] == 'PENDIENTE'): ?>
<div class="row">
    <div class="col-md-12 text-right">
        <button class="btn btn-success" onclick="resolverReporte(<?= $idReporte ?>)">
            <i class="fa fa-check"></i> Marcar como Resuelto
        </button>
    </div>
</div>

<script>
function resolverReporte(idReporte) {
    if (confirm('¿Estás seguro de marcar este reporte como resuelto?')) {
        $.post('ResolverReporte.php', {id: idReporte}, function(response) {
            if (response.success) {
                $('#detalleModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (response.error || 'Ocurrió un problema'));
            }
        }, 'json').fail(function() {
            alert('Error en la comunicación con el servidor');
        });
    }
}
</script>
<?php endif; ?>