<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>

</head>

<body>
    <section id="container" class="">
        <!-- Header -->
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom">
                    <i class="icon_menu"></i>
                </div>
            </div>
            <?php include("Logo.php") ?>
            <div class="nav search-row" id="top_menu">
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <!-- Busqueda si es necesaria -->
                        </form>
                    </li>
                </ul>
            </div>
            <?php include("DropDown.php"); ?>
        </header>

        <!-- Menú Principal -->
        <?php include("Menu.php") ?>
    </section>

    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="icon_tag_alt"></i> PRODUCTOS DISPONIBLES</h3>

                    <!--FUNCION DE ALERTA DE MENSAJES-->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong><?= $_SESSION['mensaje'] ?></strong>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>

                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                        <li><i class="icon_creditcard"></i><a href="ComprarController.php">Productos</a></li>
                        <li><i class="icon_cart_alt"></i><a href="CarritoController.php">Carrito</a></li>
                        <li><i class="icon_wallet_alt"></i><a href="PagarController.php">Pagar</a></li>
                    </ol>
                </div>
            </div>

            <!-- Filtro por categorías -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-filter"></i> FILTRAR POR CATEGORÍA
                        </div>
                        <div class="panel-body">
                            <div class="btn-group flex-wrap">
                                <a href="ComprarController.php" class="btn btn-<?= !isset($_GET['categoria']) ? 'primary' : 'default' ?>">
                                    Todos los productos
                                </a>
                                <?php foreach ($categorias as $categoria): ?>
                                    <a href="ComprarController.php?categoria=<?= $categoria['id_categoria'] ?>"
                                        class="btn btn-<?= (isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id_categoria']) ? 'primary' : 'default' ?>">
                                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listado de productos en cuadrícula -->
            <div class="row">
                <?php foreach ($productos as $producto): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="panel panel-default product-card">
                            <div class="panel-body">
                                <div class="text-center">
                                    <img src="<?= URL_VIEWS . htmlspecialchars($producto['imagen']) ?>"
                                        class="product-img img-thumbnail"
                                        alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                                        onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'">
                                </div>

                                <h4 class="text mt-2" style="max-width: 10ch; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                
                                <?= htmlspecialchars($producto['nombre_producto']) ?></h4>

                                <div class="mb-2">
                                    <span class="label label-info">
                                        <?= htmlspecialchars($producto['nombre_categoria']) ?>
                                    </span>
                                    <span class="label label-danger pull-right">
                                        <?= htmlspecialchars($producto['nombre_vendedor']) ?>
                                    </span>
                                </div>

                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...
                                </p>

                                <h4 class="text-success">$<?= number_format($producto['precio_venta'], 2) ?></h4>

                                <div class="btn-group btn-group-justified mt-2">
                                    <a href="#" class="btn btn-sm btn-primary btn-ver-detalle"
                                        data-id="<?= $producto['id_producto'] ?>" style="width:60px;">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                    <button class="btn btn-sm btn-success add-to-cart"
                                        data-id="<?= $producto['id_producto'] ?>" style="width: 80px;">
                                        <i class="fa fa-shopping-cart"></i>
                                        <span class="cart-text">Añadir</span>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($productos)): ?>
                    <div class="col-lg-12">
                        <div class="alert alert-info text-center">
                            <i class="fa fa-info-circle fa-2x"></i><br>
                            No se encontraron productos disponibles.
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <ul class="pagination">
                            <?php if ($pagina_actual > 1): ?>
                                <li><a href="ComprarController.php?pagina=<?= $pagina_actual - 1 ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">&laquo;</a></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="<?= $i == $pagina_actual ? 'active' : '' ?>">
                                    <a href="ComprarController.php?pagina=<?= $i ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagina_actual < $total_paginas): ?>
                                <li><a href="ComprarController.php?pagina=<?= $pagina_actual + 1 ?><?= isset($_GET['categoria']) ? '&categoria=' . $_GET['categoria'] : '' ?>">&raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </section>

    <?php include('../Views/ProductoDetalleModal.php'); ?>

    <?php include("LibraryJs.php"); ?>

    <script>
        $(document).ready(function() {
            // Efecto hover para las tarjetas
            $('.product-card').hover(
                function() {
                    $(this).css('box-shadow', '0 5px 15px rgba(0,0,0,0.1)');
                },
                function() {
                    $(this).css('box-shadow', 'none');
                }
            );
            // Ver detalle del producto
            $(document).on('click', '.btn-ver-detalle', function(e) {
                e.preventDefault();
                const idProducto = $(this).data('id');
                const modal = $('#productoDetalleModal');

                modal.find('.modal-body').html(`
                    <div class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Cargando detalles del producto...</p>
                    </div>
                `);

                modal.modal('show');

                $.ajax({
                    url: 'ComprarController.php?action=detalle&id=' + idProducto,
                    type: 'GET',
                    success: function(data) {
                        modal.find('.modal-body').html(data);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Error al cargar los detalles';
                        if (xhr.responseText) {
                            errorMsg += ': ' + xhr.responseText.substring(0, 100);
                        }
                        modal.find('.modal-body').html(`
                        <div class="alert alert-danger">
                            ${errorMsg}
                            <button class="btn btn-sm btn-default" onclick="window.location.reload()">
                                Recargar
                            </button>
                        </div>
                        `);
                    }
                });
            });
        });

        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            $.ajax({
                url: 'CarritoController.php?action=contador',
                type: 'GET',
                success: function(response) {
                    $('#header-cart-count').text(response.total || 0);
                },
                error: function() {
                    console.error('Error al obtener contador del carrito');
                }
            });
        }

        // Añadir producto al carrito
        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();
            const idProducto = $(this).data('id');
            const boton = $(this);

            // Guardar el estado original del botón
            const originalText = boton.find('.cart-text').text();
            const originalClass = boton.attr('class');

            // Mostrar feedback visual
            boton.prop('disabled', true);
            boton.find('.cart-text').text('Añadiendo...');

            $.ajax({
                url: 'CarritoController.php?action=agregar',
                type: 'POST',
                dataType: 'json', // Asegurarnos de recibir JSON
                data: {
                    id_producto: idProducto
                },
                success: function(response) {
                    if (response.success) {
                        boton.find('.cart-text').text('Añadido');
                        boton.removeClass('btn-success').addClass('btn-info');
                        actualizarContadorCarrito();

                        // Mostrar notificación
                        toastr.success('Producto añadido al carrito');
                    } else {
                        toastr.error(response.message || 'Error al añadir al carrito');
                        boton.find('.cart-text').text('Añadir');
                        boton.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error en la conexión');
                    boton.find('.cart-text').text('Añadir');
                    boton.prop('disabled', false);
                }
            });
        });

        // Actualizar contador al cargar la página
        $(document).ready(function() {
            actualizarContadorCarrito();
        });
    </script>
</body>

</html>