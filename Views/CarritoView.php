<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    
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
                            <input class="form-control" placeholder="Buscar productos..." type="text">
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
                    <h3 class="page-header"><i class="icon_cart_alt"></i> MI CARRITO</h3>
                    
                    <!-- Mensajes de alerta -->
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
                        <li><i class="icon_cart_alt"></i> Carrito</li>
                        <li><i class="icon_document"></i><a href="HistorialController.php">Historial</a></li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="icon_cart"></i> PRODUCTOS EN TU CARRITO
                        </div>
                        <div class="panel-body">
                            <?php if (empty($items)): ?>
                                <div class="alert text-center">
                                    <i class="fa fa-shopping-cart fa-3x mb-3"></i>
                                    <h4>Tu carrito está vacío</h4>
                                    <p>Explora nuestros productos y encuentra lo que necesitas</p>
                                    <a href="ComprarController.php" class="btn btn-primary">
                                        <i class="fa fa-arrow-right"></i> Ir a productos
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-cart">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%">Producto</th>
                                                <th class="text-center">Precio Unitario</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-center">Subtotal</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0;
                                            foreach ($items as $item):
                                                $subtotal = $item['precio_unitario'] * $item['cantidad'];
                                                $total += $subtotal;
                                            ?>
                                                <tr id="item-<?= $item['id_item'] ?>">
                                                    <td>
                                                        <div class="media">
                                                            <img src="<?= URL_VIEWS . htmlspecialchars($item['imagen']) ?>"
                                                                class="cart-item-img mr-3"
                                                                alt="<?= htmlspecialchars($item['nombre_producto']) ?>"
                                                                onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'">
                                                            <div class="media-body">
                                                                <h5 class="mt-0"><?= htmlspecialchars($item['nombre_producto']) ?></h5>
                                                                <small class="text-muted">Vendedor: <?= htmlspecialchars($item['nombre_vendedor']) ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        $<?= number_format($item['precio_unitario'], 2) ?>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <div class="input-group quantity-control mx-auto">
                                                            <button class="btn btn-outline-secondary update-qty"
                                                                data-id="<?= $item['id_item'] ?>"
                                                                data-action="minus">
                                                                <i class="fa fa-minus"></i>
                                                            </button>
                                                            <input type="number" class="form-control text-center"
                                                                value="<?= $item['cantidad'] ?>" min="1"
                                                                id="qty-<?= $item['id_item'] ?>">
                                                            <button class="btn btn-outline-secondary update-qty"
                                                                data-id="<?= $item['id_item'] ?>"
                                                                data-action="plus">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <strong>$<?= number_format($subtotal, 2) ?></strong>
                                                    </td>
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-danger btn-sm remove-item"
                                                            data-id="<?= $item['id_item'] ?>"
                                                            title="Eliminar producto">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card cart-totals">
                                            <div class="card-body">
                                                <h5 class="card-title">Resumen del Pedido</h5>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td>Subtotal:</td>
                                                        <td class="text-right">$<?= number_format($total, 2) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Envío:</td>
                                                        <td class="text-right">$0.00</td>
                                                    </tr>
                                                    <tr class="font-weight-bold">
                                                        <td>Total:</td>
                                                        <td class="text-right">$<?= number_format($total, 2) ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 cart-actions">
                                        <div class="d-flex justify-content-between">
                                            <a href="productos.php" class="btn btn-outline-secondary">
                                                <i class="fa fa-arrow-left"></i> Seguir comprando
                                            </a>
                                            <a href="checkout.php" class="btn btn-primary">
                                                Proceder al pago <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fa fa-lock"></i> Tus datos de pago están protegidos
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <?php include('LibraryJS.php'); ?>

    <script>
        $(document).ready(function() {
            // Actualizar cantidad
            $('.update-qty').click(function() {
                var id = $(this).data('id');
                var action = $(this).data('action');
                var input = $('#qty-' + id);
                var currentVal = parseInt(input.val());
                var newVal = action == 'plus' ? currentVal + 1 : Math.max(1, currentVal - 1);

                input.val(newVal);
                updateCartItem(id, newVal);
            });

            // Eliminar item
            $('.remove-item').click(function() {
                var id = $(this).data('id');
                var itemRow = $('#item-' + id);
                
                bootbox.confirm({
                    title: "Confirmar eliminación",
                    message: "¿Estás seguro de eliminar este producto de tu carrito?",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancelar'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Eliminar',
                            className: 'btn-danger'
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            removeCartItem(id, itemRow);
                        }
                    }
                });
            });

            // Función para actualizar cantidad en el carrito
            function updateCartItem(id, quantity) {
                $.ajax({
                    url: 'CarritoController.php?action=actualizar',
                    method: 'POST',
                    data: {
                        id_item: id,
                        cantidad: quantity
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#qty-' + id).prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // Mostrar notificación de éxito
                            $.notify({
                                icon: 'fa fa-check',
                                message: 'Cantidad actualizada correctamente'
                            }, {
                                type: 'success',
                                delay: 2000,
                                placement: {
                                    from: "top",
                                    align: "right"
                                }
                            });
                            
                            // Actualizar la página si hay cambios importantes
                            if (response.reload) {
                                setTimeout(function() {
                                    location.reload();
                                }, 500);
                            }
                        } else {
                            // Mostrar error
                            $.notify({
                                icon: 'fa fa-exclamation-triangle',
                                message: response.message || 'Error al actualizar la cantidad'
                            }, {
                                type: 'danger',
                                delay: 3000
                            });
                            
                            // Revertir el valor si hay error
                            location.reload();
                        }
                    },
                    error: function() {
                        $.notify({
                            icon: 'fa fa-exclamation-circle',
                            message: 'Error de conexión'
                        }, {
                            type: 'danger',
                            delay: 3000
                        });
                    },
                    complete: function() {
                        $('#qty-' + id).prop('disabled', false);
                    }
                });
            }

            // Función para eliminar item del carrito
            function removeCartItem(id, itemRow) {
                $.ajax({
                    url: 'CarritoController.php?action=eliminar',
                    method: 'POST',
                    data: {
                        id_item: id
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        itemRow.css('opacity', '0.5');
                    },
                    success: function(response) {
                        if (response.success) {
                            // Animación para eliminar la fila
                            itemRow.fadeOut(300, function() {
                                $(this).remove();
                                
                                // Mostrar notificación
                                $.notify({
                                    icon: 'fa fa-check',
                                    message: 'Producto eliminado del carrito'
                                }, {
                                    type: 'success',
                                    delay: 2000
                                });
                                
                                // Actualizar contador del carrito
                                updateCartCount();
                                
                                // Si no quedan items, recargar la página
                                if ($('.table-cart tbody tr').length === 0) {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 500);
                                }
                            });
                        } else {
                            itemRow.css('opacity', '1');
                            $.notify({
                                icon: 'fa fa-exclamation-triangle',
                                message: response.message || 'Error al eliminar el producto'
                            }, {
                                type: 'danger',
                                delay: 3000
                            });
                        }
                    },
                    error: function() {
                        itemRow.css('opacity', '1');
                        $.notify({
                            icon: 'fa fa-exclamation-circle',
                            message: 'Error de conexión'
                        }, {
                            type: 'danger',
                            delay: 3000
                        });
                    }
                });
            }

            // Función para actualizar contador del carrito
            function updateCartCount() {
                $.get('CarritoController.php?action=count', function(response) {
                    $('#cart-count').text(response.count || 0);
                }).fail(function() {
                    console.error('Error al actualizar contador del carrito');
                });
            }
        });
    </script>
</body>
</html>