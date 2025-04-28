<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar</title>
    <style>
        .product-card {
            transition: all 0.3s ease;
            margin-bottom: 20px;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            height: calc(100% - 180px);
        }
        .card-text {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
    </style>
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
                    <h3 class="page-header"><i class="fa fa-shopping-bag"></i> PRODUCTOS DISPONIBLES</h3>
                    
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
                        <li><i class="fa fa-shopping-bag"></i>Productos</li>
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
                                <a href="productos.php" class="btn btn-<?= !isset($_GET['categoria']) ? 'primary' : 'default' ?>">
                                    Todos los productos
                                </a>
                                <?php foreach ($categorias as $categoria): ?>
                                    <a href="productos.php?categoria=<?= $categoria['id_categoria'] ?>"
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
                                
                                <h4 class="text-primary mt-2"><?= htmlspecialchars($producto['nombre_producto']) ?></h4>
                                
                                <div class="mb-2">
                                    <span class="label label-info">
                                        <?= htmlspecialchars($producto['nombre_categoria']) ?>
                                    </span>
                                    <span class="label label-warning pull-right">
                                        <?= htmlspecialchars($producto['nombre_vendedor']) ?>
                                    </span>
                                </div>
                                
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...
                                </p>
                                
                                <h4 class="text-success">$<?= number_format($producto['precio_venta'], 2) ?></h4>
                                
                                <div class="btn-group btn-group-justified mt-2">
                                    <a href="producto.php?action=detalle&id=<?= $producto['id_producto'] ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Ver
                                    </a>
                                    <button class="btn btn-sm btn-success add-to-cart"
                                        data-id="<?= $producto['id_producto'] ?>">
                                        <i class="fa fa-cart-plus"></i> Añadir
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
                                <li><a href="productos.php?pagina=<?= $pagina_actual - 1 ?><?= isset($_GET['categoria']) ? '&categoria='.$_GET['categoria'] : '' ?>">&laquo;</a></li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                <li class="<?= $i == $pagina_actual ? 'active' : '' ?>">
                                    <a href="productos.php?pagina=<?= $i ?><?= isset($_GET['categoria']) ? '&categoria='.$_GET['categoria'] : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagina_actual < $total_paginas): ?>
                                <li><a href="productos.php?pagina=<?= $pagina_actual + 1 ?><?= isset($_GET['categoria']) ? '&categoria='.$_GET['categoria'] : '' ?>">&raquo;</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </section>

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

            // Función para añadir al carrito
            $('.add-to-cart').click(function() {
                var id = $(this).data('id');
                var button = $(this);
                
                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando');

                $.ajax({
                    url: 'CarritoController.php?action=agregar',
                    method: 'POST',
                    data: {
                        id_producto: id,
                        cantidad: 1
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Mostrar notificación de éxito
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
                            
                            // Actualizar contador del carrito
                            updateCartCount();
                        } else {
                            // Mostrar notificación de error
                            $.notify({
                                icon: 'fa fa-exclamation-triangle',
                                message: response.message
                            }, {
                                type: 'danger',
                                delay: 5000,
                                placement: {
                                    from: "top",
                                    align: "right"
                                }
                            });
                            
                            // Redirigir a login si es necesario
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
                        button.prop('disabled', false).html('<i class="fa fa-cart-plus"></i> Añadir');
                    }
                });
            });

            // Función para actualizar el contador del carrito
            function updateCartCount() {
                $.get('CarritoController.php?action=count', function(response) {
                    $('#cart-count').text(response.count || 0);
                }).fail(function() {
                    console.error('Error al actualizar el contador del carrito');
                });
            }
        });
    </script>
</body>
</html>