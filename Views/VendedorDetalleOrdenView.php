<div class="row">
    <div class="col-md-6">
        <h4>Información de la Orden</h4>
        <table class="table table-bordered">
            <tr>
                <th>ID Orden</th>
                <td>#<?= $orden['id_orden'] ?></td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
            </tr>
            <tr>
                <th>Estado</th>
                <td><span class="badge"><?= $orden['estado'] ?></span></td>
            </tr>
            <tr>
                <th>Cliente</th>
                <td><?= htmlspecialchars($orden['cliente_nombre']) ?> (@<?= htmlspecialchars($orden['cliente_login']) ?>)</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>$<?= number_format($orden['total'], 2) ?></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h4>Dirección de Entrega</h4>
        <p><?= htmlspecialchars($orden['direccion_entrega'] ?? 'No especificada') ?></p>
        <h4>Comentarios</h4>
        <p><?= htmlspecialchars($orden['comentarios'] ?? 'Ninguno') ?></p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <h4>Productos</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                <tr>
                    <td>
                        <img src="<?= $detalle['imagen'] ?>" width="50" height="50" class="img-thumbnail">
                        <?= htmlspecialchars($detalle['nombre_producto']) ?>
                    </td>
                    <td><?= $detalle['cantidad'] ?></td>
                    <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="active">
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td><strong>$<?= number_format($orden['total'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>