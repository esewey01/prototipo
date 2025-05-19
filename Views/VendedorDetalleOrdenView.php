<div class="container-fluid p-0">
    <div class="row">
        <!-- Columna de Información General -->
        <div class="col-md-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Fecha</small>
                                    <p class="mb-0 font-weight-bold"><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-tag text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Estado</small>
                                    <p class="mb-0">
                                        <span class="badge badge-pill 
                                            <?= $orden['estado'] == 'PAGADO' ? 'badge-success' : 
                                               ($orden['estado'] == 'PENDIENTE' ? 'badge-warning' : 
                                               ($orden['estado'] == 'CANCELADO' ? 'badge-danger' : 'badge-primary')) ?>">
                                            <i class="fas 
                                                <?= $orden['estado'] == 'PAGADO' ? 'fa-check-circle' : 
                                                   ($orden['estado'] == 'PENDIENTE' ? 'fa-clock' : 
                                                   ($orden['estado'] == 'CANCELADO' ? 'fa-times-circle' : 'fa-truck')) ?> mr-1"></i>
                                            <?= $orden['estado'] ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Total</small>
                                    <p class="mb-0 font-weight-bold text-success">$<?= number_format($orden['total'], 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user mr-2"></i>Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tag text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Nombre</small>
                                    <p class="mb-0 font-weight-bold"><?= htmlspecialchars($orden['cliente_nombre']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-at text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Usuario</small>
                                    <p class="mb-0">@<?= htmlspecialchars($orden['cliente_login']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Dirección</small>
                                    <p class="mb-0"><?= htmlspecialchars($orden['direccion_entrega'] ?? $orden['cliente_direccion']) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="list-group-item border-0 py-2 px-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-muted mr-3" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted">Teléfono</small>
                                    <p class="mb-0"><?= htmlspecialchars($orden['cliente_telefono']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna de Productos -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes mr-2"></i>Productos</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0">Producto</th>
                                    <th class="border-0 text-center">Cant.</th>
                                    <th class="border-0 text-right">P. Unit.</th>
                                    <th class="border-0 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $item): ?>
                                    <tr>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= URL_VIEWS . htmlspecialchars($item['imagen']) ?>"
                                                    class="img-thumbnail rounded mr-3" 
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($item['nombre_producto']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($item['nombre_categoria'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center"><?= $item['cantidad'] ?></td>
                                        <td class="align-middle text-right">$<?= number_format($item['precio_unitario'], 2) ?></td>
                                        <td class="align-middle text-right font-weight-bold">$<?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-right font-weight-bold border-0">Total:</td>
                                    <td class="text-right font-weight-bold text-success border-0">$<?= number_format($orden['total'], 2) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>