<?php
if (!isset($producto)) {
    die('Datos del producto no disponibles');
}
?>
<?php include('Head.php'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- Columna de la imagen -->
        <div class="col-md-5">
            <div class="sticky-top" style="top: 20px;">
                <img src="<?= URL_VIEWS . htmlspecialchars($producto['imagen']) ?>"
                    class="img-fluid rounded mb-3"
                    alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'">

                <?php if (!empty($producto['imagenes_adicionales'])): ?>
                    <div class="row mt-2">
                        <?php foreach (json_decode($producto['imagenes_adicionales']) as $img): ?>
                            <div class="col-3 mb-2">
                                <img src="<?= URL_VIEWS . htmlspecialchars($img) ?>"
                                    class="img-thumbnail"
                                    style="cursor: pointer; height: 80px; object-fit: cover;"
                                    onclick="$('#productoDetalleModal .img-fluid').attr('src', this.src)">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Columna de la información -->
        <div class="col-md-7">
            <h2 class="mb-5"><?= htmlspecialchars($producto['nombre_producto']) ?></h2>

            <div class="d-flex align-items-center mb-3">
                <span class="badge bg-<?= $producto['cantidad'] > 0 ? 'success' : 'danger' ?> mr-2">
                    <?= $producto['cantidad'] > 0 ? 'Disponible' : 'Agotado' ?>
                </span>
            </div>

            <div class="mb-4">
                <h4 class="d-inline-block">Precio: 
                    <span class="ml-auto h4 text">$<?= number_format($producto['precio_venta'], 2) ?></span>
                </h4>
                <h5 class="d-inline-block">Vendedor :
                    <span class="label label-danger"><?= htmlspecialchars($producto['nombre_vendedor']) ?></span>
                </h5>
                <h5 class="d-inline-block">Categoria :
                    <span class="badge bg-secondary"><?= htmlspecialchars($producto['nombre_categoria']) ?></span>
                </h5>
            </div>

            <div class="mb-4">
                <h5>Descripción:</h5>
                <p class="text-justify"><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
            </div>

            <form class="add-to-cart-form mb-4">
                <input type="hidden" name="id_producto" value="<?= $producto['id_producto'] ?>">
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <div class="input-group quantity-control" style="max-width: 150px;">
                        <button class="btn btn-outline-secondary minus-btn" type="button">-</button>
                        <input type="number" name="cantidad" class="form-control text-center"
                            value="1" min="1" max="<?= $producto['cantidad'] ?>">
                        <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"
                        <?= $producto['cantidad'] <= 0 ? 'disabled' : '' ?>>
                        <i class="fa fa-cart-plus"></i> Añadir al carrito
                    </button>
                    <a href="CarritoController.php" class="btn btn-success">
                        <i class="fa fa-shopping-cart"></i> Ver Carrito
                    </a>
                </div>
            </form>

            <hr>

            <!-- Valoraciones -->
            <div class="mt-4">
                <h4><i class="fa fa-star text-warning"></i> Valoraciones</h4>

                <?php if (empty($valoraciones)): ?>
                    <div class="alert alert-info">
                        Este producto no tiene valoraciones aún.
                    </div>
                <?php else: ?>
                    <div class="valoraciones-container" style="max-height: 300px; overflow-y: auto;">
                        <?php foreach ($valoraciones as $valoracion): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><?= htmlspecialchars($valoracion['usuario_nombre']) ?></h5>
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star<?= $i <= $valoracion['calificacion'] ? '' : '-o' ?> text-warning"></i>
                                            <?php endfor; ?>
                                            <small class="text-muted ml-2"><?= $valoracion['fecha_valoracion']->format('d/m/Y') ?></small>
                                        </div>
                                    </div>

                                    <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($valoracion['comentario'])) ?></p>

                                    <?php if (!empty($valoracion['respuesta_vendedor'])): ?>
                                        <div class="bg-light p-3 mt-2 rounded">
                                            <strong class="text-primary">Respuesta del vendedor:</strong>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars($valoracion['respuesta_vendedor'])) ?></p>
                                            <small class="text-muted"><?= $valoracion['fecha_respuesta']->format('d/m/Y H:i') ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Control de cantidad
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

        // Enviar formulario via AJAX
        $('.add-to-cart-form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');

            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');

            $.ajax({
                url: 'CarritoController.php?action=agregar',
                method: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $.notify({
                            icon: 'fa fa-check',
                            message: response.message
                        }, {
                            type: 'success',
                            delay: 3000,
                            placement: {
                                from: "top",
                                align: "right"
                            }
                        });
                        updateCartCount();
                    } else {
                        $.notify({
                            icon: 'fa fa-exclamation-triangle',
                            message: response.message
                        }, {
                            type: 'danger',
                            delay: 5000
                        });

                        if (response.message.includes('sesión')) {
                            setTimeout(function() {
                                window.location.href = 'login.php';
                            }, 1500);
                        }
                    }
                },
                error: function() {
                    $.notify({
                        icon: 'fa fa-exclamation-circle',
                        message: 'Error al procesar la solicitud'
                    }, {
                        type: 'danger',
                        delay: 3000
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-cart-plus"></i> Añadir al carrito');
                }
            });
        });

        function updateCartCount() {
            $.get('CarritoController.php?action=count', function(response) {
                $('#cart-count').text(response.count || 0);
            }).fail(function() {
                console.error('Error al actualizar contador del carrito');
            });
        }
    });
</script>