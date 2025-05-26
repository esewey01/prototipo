<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover table-reportes">
        <thead>
            <tr>
                <th onclick="ordenarTabla(0)">ID <i class="fa fa-sort"></i></th>
                <th onclick="ordenarTabla(1)">Fecha <i class="fa fa-sort"></i></th>

                <?php if ($tipo_reporte == 'ORDEN'): ?>
                    <th>N° Orden</th>
                <?php endif; ?>

                <?php if ($tipo_reporte == 'PRODUCTO'): ?>
                    <th>Producto</th>
                    <th>Reportado por</th>
                <?php endif; ?>

                <?php if ($tipo_reporte == 'USUARIO'): ?>
                    <th><?= ($reporte['rol_reportado'] ?? '') == 'VENDEDOR' ? 'Vendedor' : 'Usuario' ?></th>
                <?php endif; ?>

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
                        <td>
                            <a href="OrdenController.php?id=<?= $reporte['id_orden'] ?>"
                                title="Ver orden" class="text-primary">
                                #<?= $reporte['id_orden'] ?? 'N/A' ?>
                            </a>
                        </td>
                    <?php endif; ?>

                    <?php if ($tipo_reporte == 'PRODUCTO'): ?>
                        <td>
                            <?= $reporte['nombre_producto'] ?? 'N/A' ?>
                            <?php if (isset($reporte['id_producto'])): ?>
                                (ID: <?= $reporte['id_producto'] ?>)
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($reporte['nombre_administrador'] ?? 'Sistema') ?></td>
                    <?php endif; ?>

                    <?php if ($tipo_reporte == 'USUARIO'): ?>
                        <td>
                            <?= htmlspecialchars($reporte['nombre_reportado'] ?? 'N/A') ?>
                            (ID: <?= $reporte['id_usuario_reportado'] ?>)
                        </td>
                    <?php endif; ?>

                    <td>
                        <span title="<?= htmlspecialchars($reporte['motivo']) ?>">
                            <?= htmlspecialchars(substr($reporte['motivo'], 0, 50)) ?>
                            <?= strlen($reporte['motivo']) > 50 ? '...' : '' ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-<?=
                                                $reporte['estado'] == 'PENDIENTE' ? 'warning' : ($reporte['estado'] == 'RESUELTO' ? 'success' : 'danger')
                                                ?>">
                            <?= $reporte['estado'] ?>
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <button class="btn btn-info btn-xs btn-sm btn-ver-detalle"
                            onclick="verDetalleReporte(<?= $reporte['id_reporte'] ?>, '<?= addslashes($tipo_reporte) ?>')"
                            title="Ver detalles"
                            data-id="<?= $reporte['id_reporte'] ?>"
                            data-tipo="<?= $tipo_reporte ?>">
                            <i class="fa fa-eye"></i>
                        </button>

                        <button class="btn btn-secondary btn-xs btn-sm"
                            onclick="descargarReporte(<?= $reporte['id_reporte'] ?>)"
                            title="Descargar PDF">
                            <i class="fa fa-file-pdf-o"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>