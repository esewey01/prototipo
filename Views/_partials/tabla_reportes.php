<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-reportes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <?php if ($tipo_reporte == 'ORDEN'): ?>
                    <th>N° Orden</th>
                <?php endif; ?>
                <?php if ($tipo_reporte == 'PRODUCTO'): ?>
                    <th>Producto</th>
                <?php else: ?>
                    <th>Usuario Reportado</th>
                <?php endif; ?>
                <th>Reportado Por</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reportes as $reporte): ?>
                <tr>
                    <td><?= $reporte['id_reporte'] ?></td>
                    <td><?= $reporte['fecha_reporte']->format('d/m/Y H:i') ?></td>

                    <?php if ($tipo_reporte == 'ORDEN'): ?>
                        <td>#<?= $reporte['id_orden'] ?? 'N/A' ?></td>
                    <?php endif; ?>

                    <td><?= htmlspecialchars($reporte['nombre_administrador'] ?? 'Sistema') ?></td>
                    <td><?= htmlspecialchars(substr($reporte['motivo'], 0, 50) . (strlen($reporte['motivo']) > 50 ? '...' : '')) ?></td>
                    <td>
                        <span class="label label-<?=
                                                    $reporte['estado'] == 'PENDIENTE' ? 'warning' : ($reporte['estado'] == 'RESUELTO' ? 'success' : 'danger')
                                                    ?>">
                            <?= $reporte['estado'] ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-info btn-xs"
                            onclick="verDetalleReporte(<?= $reporte['id_reporte'] ?>, '<?= $tipo_reporte ?>')">
                            <i class="fa fa-eye"></i> Detalles
                        </button>

                        <?php if ($reporte['estado'] == 'PENDIENTE'): ?>
                            <button class="btn btn-warning btn-xs"
                                onclick="$('#accion_id_reporte').val(<?= $reporte['id_reporte'] ?>); 
                                         $('#accion_tipo_reporte').val('<?= $tipo_reporte ?>');
                                         $('#accionReporteModal').modal('show');">
                                <i class="fa fa-gear"></i> Acción
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>