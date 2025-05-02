<?php include('../Views/Head.php'); ?>

<div class="container-fluid">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h2 class="mt-4"><i class="fas fa-file-invoice"></i> Detalle de Orden #<?= $orden['id_orden'] ?></h2>
        <a href="VendedorOrdenesController.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <p><strong>Fecha:</strong> <?= $orden['fecha_orden']->format('d/m/Y H:i') ?></p>
                    <p><strong>Estado:</strong>
                        <span class="badge 
                            <?= $orden['estado'] == 'PAGADO' ? 'badge-pagado' : 
                               ($orden['estado'] == 'PENDIENTE' ? 'badge-pendiente' : 
                               ($orden['estado'] == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado')) ?>">
                            <?= $orden['estado'] ?>
                        </span>
                    </p>
                    <p><strong>Total:</strong> $<?= number_format($orden['total'], 2) ?></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5>Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($orden['cliente_nombre']) ?></p>
                    <p><strong>Usuario:</strong> <?= htmlspecialchars($orden['cliente_login']) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($orden['direccion_entrega'] ?? $orden['cliente_direccion']) ?></p>
                    <p><strong>Teléfono:</strong> <?= htmlspecialchars($orden['cliente_telefono']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Productos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $item): ?>
                                    <tr>
                                        <td>
                                            <img src="<?= URL_VIEWS . htmlspecialchars($item['imagen']) ?>"
                                                class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?= htmlspecialchars($item['nombre_producto']) ?>
                                        </td>
                                        <td><?= $item['cantidad'] ?></td>
                                        <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                                        <td>$<?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>$<?= number_format($orden['total'], 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../Views/LibraryJs.php'); ?>