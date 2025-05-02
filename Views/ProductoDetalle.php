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
                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'"
                    style="width: 100%; height: auto; object-fit: cover;">

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


            <div class="mt-4" style="padding-top:10px">
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

        <!-- Columna de la información -->
        <div class="col-md-7">
            <h2 class="mb-5">
                <?= htmlspecialchars($producto['nombre_producto']) ?>
            </h2>

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
                    <span class="label label-danger"><?= htmlspecialchars($producto['nombre_vendedor']) ?> <?= htmlspecialchars($producto['login_vendedor']) ?></span>
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
                        <input type="number" id="cantidad-producto" name="cantidad" class="form-control text-center"
                            value="1" min="1" max="<?= $producto['cantidad'] ?>">
                        <button class="btn btn-outline-secondary plus-btn" type="button">+</button>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-sm btn-success add-to-cart"
                        data-id="<?= $producto['id_producto'] ?>" style="width: 80px;padding-bottom: 7px;padding-top:7px;">
                        <i class="fa fa-shopping-cart"></i>
                        <span class="cart-text">Añadir</span>
                    </button>


                    <a href="CarritoController.php" class="btn btn-warning">
                        <i class="fa fa-shopping-cart"></i> Ver Carrito
                    </a>
                </div>
            </form>


            <div class="mt-4">
                <h4 style="padding-top:10px"><i class="fa fa-flag text-danger"></i> Reportar Producto</h4>

                <button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#reportarProductoModal">
                    <i class="fa fa-exclamation-triangle"></i> Reportar este producto
                </button>

                <div class="small text-muted mt-1">
                    Solo reporta si el producto viola las políticas de la plataforma.
                </div>
            </div>

            <!-- Modal para reportar producto -->
            <div class="modal fade" id="reportarProductoModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reportar Producto</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="formReportarProducto">
                            <div class="modal-body">
                                <input type="hidden" name="id_producto" id="id_producto"
                                    value="<?= htmlspecialchars($producto['id_producto']) ?>">

                                <div class="form-group">
                                    <label>Motivo del reporte:</label>
                                    <select class="form-control" name="motivo" required>
                                        <option value="">Selecciona un motivo</option>
                                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                                        <option value="Información falsa">Información falsa</option>
                                        <option value="Producto prohibido">Producto prohibido</option>
                                        <option value="Precio incorrecto">Precio incorrecto</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Descripción del problema:</label>
                                    <textarea class="form-control" name="comentarios" rows="3"
                                        placeholder="Describe el problema con este producto..." required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-exclamation-triangle"></i> Enviar Reporte
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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


        // EJECUCION PARA AGRAGAR AL CARRITO JUNTO CON LA CANTIDAD ELEGIDA 
        $(document).on('click', '.add-to-cart-modal', function(e) {
            e.preventDefault();
            const idProducto = $(this).data('id');
            const cantidad = $('#cantidad-producto').val();
            const boton = $(this);

            // Guardar estado original
            const originalText = boton.find('.cart-text').text();
            const originalClass = boton.attr('class');

            // Mostrar feedback visual
            boton.prop('disabled', true).find('.cart-text').text('Añadiendo...');

            $.ajax({
                url: 'CarritoController.php?action=agregar',
                type: 'POST',
                dataType: 'json',
                data: {
                    id_producto: idProducto,
                    cantidad: cantidad
                },
                success: function(response) {
                    if (response && response.success) {
                        boton.find('.cart-text').text('¡Añadido!');
                        boton.removeClass('btn-success').addClass('btn-info');
                        actualizarContadorCarrito();
                        alert(response.message || 'Producto añadido al carrito'); // Cambiado
                        //toastr.success(response.message || 'Producto añadido al carrito');

                        // Restaurar después de 2 segundos
                        setTimeout(function() {
                            boton.prop('disabled', false)
                                .find('.cart-text').text(originalText);
                            boton.attr('class', originalClass);
                        }, 2000);
                    } else {
                        alert(response.message || 'Error al añadir al carrito'); // Cambiado
                        //toastr.error(response.message || 'Error al añadir al carrito');
                        boton.prop('disabled', false)
                            .find('.cart-text').text(originalText);
                        boton.attr('class', originalClass);
                    }
                },
                error: function(xhr) {
                    alert('Error en la conexión'); // Cambiado
                    //toastr.error('Error en la conexión');
                    boton.prop('disabled', false)
                        .find('.cart-text').text(originalText);
                    boton.attr('class', originalClass);
                }
            });
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


        //MANEJO DE ENVIO DE REPORTES
        $('#formReportarProducto').submit(function(e) {
            e.preventDefault();

            // Verificar que el formulario existe
            if (!$(this).length) {
                console.error("Error: No se encontró el formulario");
                return;
            }

            const form = $(this);
            const submitBtn = form.find('button[type="submit"]');
            const idProducto = form.find('[name="id_producto"]').val();

            // Debug en consola
            console.log("Formulario encontrado:", form);
            console.log("ID Producto encontrado:", idProducto);
            console.log("Datos del formulario:", form.serialize());

            if (!idProducto || isNaN(idProducto) || idProducto <= 0) {
                alert('Error: ID de producto inválido (' + idProducto + ')');
                return;
            }

            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

            // Opción 1: Enviar como JSON
            const datos = {
                id_producto: idProducto,
                motivo: form.find('[name="motivo"]').val(),
                comentarios: form.find('[name="comentarios"]').val()
            };

            // Opción 2: Enviar como FormData
            const formData = new FormData(form[0]);
            formData.append('id_usuario', <?= $_SESSION['usuario']['id_usuario'] ?? 0 ?>);

            $.ajax({
                url: 'ReportexUsController.php?action=reportarProducto',
                type: 'POST',
                dataType: 'json',
                // Usar solo UNA de estas opciones:
                data: JSON.stringify(datos), // Opción JSON
                // data: formData, // Opción FormData
                contentType: 'application/json', // Solo para JSON
                // processData: false, // Solo para FormData
                // contentType: false, // Solo para FormData
                success: function(response) {
                    console.log("Respuesta recibida:", response);
                    if (response && response.success) {
                        alert('Reporte enviado: ' + response.message);
                        $('#reportarProductoModal').modal('hide');
                        form[0].reset();
                    } else {
                        alert('Error: ' + (response.message || 'Respuesta inválida del servidor'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en AJAX:", status, error, xhr.responseText);
                    alert('Error de conexión. Ver consola para detalles.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('Enviar Reporte');
                }
            });
        });
    });
</script>