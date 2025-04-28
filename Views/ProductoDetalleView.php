<?php include('../includes/header_cliente.php'); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($producto['imagen']) ?>" 
                 class="img-fluid rounded" 
                 alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
        </div>
        <div class="col-md-6">
            <h2><?= htmlspecialchars($producto['nombre_producto']) ?></h2>
            <p class="text-muted">Vendedor: <?= htmlspecialchars($producto['nombre_vendedor']) ?></p>
            <h4 class="text-primary">$<?= number_format($producto['precio_venta'], 2) ?></h4>
            
            <div class="mb-3">
                <span class="badge bg-<?= $producto['cantidad'] > 0 ? 'success' : 'danger' ?>">
                    <?= $producto['cantidad'] > 0 ? 'Disponible' : 'Agotado' ?>
                </span>
                <span class="ms-2">Stock: <?= $producto['cantidad'] ?></span>
            </div>
            
            <p><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
            
            <form class="add-to-cart-form">
                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                <div class="input-group mb-3" style="max-width: 200px;">
                    <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                    <input type="number" name="cantidad" class="form-control text-center" 
                           value="1" min="1" max="<?= $producto['cantidad'] ?>">
                    <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                </div>
                <button type="submit" class="btn btn-primary" 
                        <?= $producto['cantidad'] <= 0 ? 'disabled' : '' ?>>
                    <i class="fa fa-cart-plus"></i> Añadir al carrito
                </button>
                <a href="carrito.php" class="btn btn-success">
                    <i class="fa fa-shopping-cart"></i> Ver Carrito
                </a>
            </form>
        </div>
    </div>
    
    <!-- Valoraciones -->
    <div class="mt-5">
        <h4>Valoraciones</h4>
        
        <?php if (empty($valoraciones)): ?>
            <p>Este producto no tiene valoraciones aún.</p>
        <?php else: ?>
            <?php foreach ($valoraciones as $valoracion): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5><?= htmlspecialchars($valoracion['usuario_nombre']) ?></h5>
                        <div>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fa fa-star<?= $i <= $valoracion['calificacion'] ? '' : '-o' ?> text-warning"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <p class="text-muted">
                        <small><?= $valoracion['fecha_valoracion']->format('d/m/Y H:i') ?></small>
                    </p>
                    <p><?= nl2br(htmlspecialchars($valoracion['comentario'])) ?></p>
                    
                    <?php if (!empty($valoracion['respuesta_vendedor'])): ?>
                        <div class="bg-light p-3 mt-2 rounded">
                            <strong>Respuesta del vendedor:</strong>
                            <p><?= nl2br(htmlspecialchars($valoracion['respuesta_vendedor'])) ?></p>
                            <small class="text-muted">
                                <?= $valoracion['fecha_respuesta']->format('d/m/Y H:i') ?>
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.minus-btn').click(function() {
        var input = $(this).siblings('input');
        var value = parseInt(input.val());
        if (value > 1) input.val(value - 1);
    });
    
    $('.plus-btn').click(function() {
        var input = $(this).siblings('input');
        var value = parseInt(input.val());
        var max = parseInt(input.attr('max'));
        if (value < max) input.val(value + 1);
    });
    
    $('.add-to-cart-form').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: 'CarritoController.php?action=agregar',
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    updateCartCount();
                } else {
                    alert(response.message);
                    if (response.message.includes('sesión')) {
                        window.location.href = 'login.php';
                    }
                }
            }
        });
    });
    
    function updateCartCount() {
        $.get('CarritoController.php?action=count', function(response) {
            $('#cart-count').text(response.count || 0);
        });
    }
});
</script>

<?php include('../includes/footer_cliente.php'); ?>