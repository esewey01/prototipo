<!--?php include '../includes/header_admin.php'; ?-->

<div class="container mt-4">
    <h2 class="mb-4">Historial de Solicitudes de Vendedores</h2>
    
    <?php if (empty($solicitudes)): ?>
        <div class="alert alert-info">No hay solicitudes procesadas en el historial.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Revisión</th>
                        <th>Revisor</th>
                        <th>Descripción</th>
                        <th>Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?= htmlspecialchars($solicitud['id_solicitud']) ?></td>
                        <td><?= htmlspecialchars($solicitud['nombre']) ?></td>
                        <td>
                            <?php if ($solicitud['estado'] === 'APROBADA'): ?>
                                <span class="badge badge-success">APROBADA</span>
                            <?php else: ?>
                                <span class="badge badge-danger">RECHAZADA</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($solicitud['fecha_solicitud'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($solicitud['fecha_revision'])) ?></td>
                        <td><?= htmlspecialchars($solicitud['id_revisor']) ?></td>
                        <td><?= htmlspecialchars($solicitud['descripcion']) ?></td>
                        <td><?= htmlspecialchars($solicitud['comentarios']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    
    <a href="SolicitudesController.php" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Volver a solicitudes pendientes
    </a>
</div>

<?php include '../includes/footer_admin.php'; ?>