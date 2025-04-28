<?php include('../includes/header_cliente.php'); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Orden #<?= $orden['id_orden'] ?></h2>
        <span class="badge bg-<?= 
            $orden['estado'] == 'PENDIENTE' ? 'warning' : 
            ($orden['estado'] == 'ENTREGADO' ? 'success' : 
            ($orden['estado'] == 'CANCELADO' ? 'danger' : 'info')) 
        ?>">
            <?= $orden['estado'] ?>
        </span>
    </div>
    
    <div class="row">
        <div class="col-md-8">
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
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= htmlspecialchars($detalle['imagen']) ?>" 
                                                 alt="<?= htmlspecialchars($detalle['nombre_producto']) ?>" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6><?= htmlspecialchars($detalle['nombre_producto']) ?></h6>
                                                <?php if ($orden['estado'] == 'ENTREGADO' && !$detalle['valorado']): ?>
                                                    <button class="btn btn-sm btn-outline-primary btn-valorar" 
                                                            data-id="<?= $detalle['id_producto'] ?>"
                                                            data-orden="<?= $orden['id_orden'] ?>"
                                                            data-vendedor="<?= $orden['id_vendedor'] ?>">
                                                        Valorar Producto
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                                    <td><?= $detalle['cantidad'] ?></td>
                                    <td>$<?= number_format($detalle['precio_unitario'] * $detalle['cantidad'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Mensajes -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Comunicación con el Vendedor</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoMensajeModal">
                        <i class="fa fa-envelope"></i> Nuevo Mensaje
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($mensajes)): ?>
                        <p>No hay mensajes para esta orden.</p>
                    <?php else: ?>
                        <?php foreach ($mensajes as $mensaje): ?>
                        <div class="mb-3 border-bottom pb-3">
                            <div class="d-flex justify-content-between">
                                <strong><?= $mensaje['id_remitente'] == $_SESSION['usuario']['id_usuario'] ? 'Tú' : htmlspecialchars($mensaje['nombre_vendedor']) ?></strong>
                                <small class="text-muted"><?= $mensaje['fecha_envio']->format('d/m/Y H:i') ?></small>
                            </div>
                            <p><?= nl2br(htmlspecialchars($mensaje['mensaje'])) ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Información de la Orden</h5>
                </div>
                <div class="card-body">
                    <p><strong>Fecha:</strong> <?= $orden['fecha_orden']->format('d/m/Y H:i') ?></p>
                    <p><strong>Vendedor:</strong> <?= htmlspecialchars($orden['nombre_vendedor']) ?></p>
                    <p><strong>Dirección de envío:</strong> <?= nl2br(htmlspecialchars($orden['direccion_envio'])) ?></p>
                    <p><strong>Notas:</strong> <?= $orden['notas'] ? nl2br(htmlspecialchars($orden['notas'])) : 'Ninguna' ?></p>
                    <hr>
                    <h5 class="text-end">Total: $<?= number_format($orden['total'], 2) ?></h5>
                </div>
            </div>
            
            <?php if ($orden['estado'] == 'PENDIENTE'): ?>
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5>Cancelar Orden</h5>
                    </div>
                    <div class="card-body">
                        <p>¿Deseas cancelar esta orden?</p>
                        <button class="btn btn-outline-danger w-100" id="btn-cancelar-orden">
                            <i class="fa fa-times"></i> Cancelar Orden
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para valorar producto -->
<div class="modal fade" id="valorarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valorar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-valorar" action="ValoracionesController.php?action=agregar" method="POST">
                <input type="hidden" name="id_orden" value="<?= $orden['id_orden'] ?>">
                <input type="hidden" name="id_producto" id="valorar-id-producto">
                <input type="hidden" name="id_vendedor" id="valorar-id-vendedor">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Calificación</label>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star rating-star" data-value="<?= $i ?>"></i>
                            <?php endfor; ?>
                            <input type="hidden" name="calificacion" id="rating-value" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentario (opcional)</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para nuevo mensaje -->
<div class="modal fade" id="nuevoMensajeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="MensajesController.php?action=enviar" method="POST">
                <input type="hidden" name="destinatario" value="<?= $orden['id_vendedor'] ?>">
                <input type="hidden" name="id_orden" value="<?= $orden['id_orden'] ?>">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto">
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Valoración de producto
    $('.btn-valorar').click(function() {
        $('#valorar-id-producto').val($(this).data('id'));
        $('#valorar-id-vendedor').val($(this).data('vendedor'));
        $('#rating-value').val('');
        $('.rating-star').removeClass('text-warning').addClass('fa-star-o');
        $('#valorarModal').modal('show');
    });
    
    $('.rating-star').click(function() {
        var value = $(this).data('value');
        $('#rating-value').val(value);
        
        $('.rating-star').each(function() {
            if ($(this).data('value') <= value) {
                $(this).removeClass('fa-star-o').addClass('text-warning fa-star');
            } else {
                $(this).removeClass('fa-star text-warning').addClass('fa-star-o');
            }
        });
    });
    
    // Cancelar orden
    $('#btn-cancelar-orden').click(function() {
        if (confirm('¿Estás seguro de cancelar esta orden?')) {
            $.ajax({
                url: 'OrdenesController.php?action=cancelar',
                method: 'POST',
                data: { id_orden: <?= $orden['id_orden'] ?> },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });
});
</script>

<?php include('../includes/footer_cliente.php'); ?>