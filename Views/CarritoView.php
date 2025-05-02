<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!--Menu desplegable-->
    <section id="container" class="">

        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Menú Principal" data-placement="bottom"><i
                        class="icon_menu"></i></div>
            </div>
            <?PHP include("Logo.php") ?>

            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Search" type="text">
                        </form>
                    </li>
                </ul>
                <!--  search form end -->
            </div>
            <?PHP include("DropDown.php"); ?> <!--MENU DE USUARIO-->
        </header>

        <?PHP include("Menu.php") ?>

    </section>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="icon_cart"></i> Carrito de Compras</h3>
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
                        <li><i class="fa fa-laptop"></i> Principal</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?php if (empty($productos)): ?>
                        <div class="panel panel-default empty-cart">
                            <div class="panel-body">
                                <i class="fa fa-shopping-cart fa-4x text-muted"></i>
                                <h3>Tu carrito está vacío</h3>
                                <p>No hay productos en tu carrito de compras.</p>
                                <a href="ComprarController.php" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> Continuar comprando
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-list"></i> Productos en tu carrito
                            </div>
                            <div class="panel-body">
                                <?php foreach ($productos as $producto): ?>
                                    <div class="row producto-carrito">
                                        <div class="col-xs-3">
                                            <img src="<?= URL_VIEWS . htmlspecialchars($producto['imagen']) ?>"
                                                class="img-thumbnail producto-imagen"
                                                onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.png'">
                                        </div>
                                        <div class="col-xs-5">
                                            <h5><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                            <p class="text-muted small"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                            <p class="text-success">
                                                <strong>$<?= number_format($producto['precio_unitario'], 2) ?></strong> c/u
                                            </p>
                                        </div>
                                        <div class="col-xs-2 text-center">
                                            <input type="number" min="1" value="<?= $producto['cantidad'] ?>"
                                                class="form-control input-sm cantidad-producto"
                                                data-id="<?= $producto['id_detalle'] ?>">
                                        </div>
                                        <div class="col-xs-2 text-right">
                                            <h5>$<?= number_format($producto['subtotal'], 2) ?></h5>
                                            <button class="btn btn-danger btn-xs btn-eliminar"
                                                data-id="<?= $producto['id_detalle'] ?>">
                                                <i class="fa fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-default resumen-compra">
                        <div class="panel-heading">
                            <i class="fa fa-credit-card"></i> Resumen de compra
                        </div>
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-right">$<?= number_format($total, 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Envío:</td>
                                    <td class="text-right">$0.00</td>
                                </tr>
                                <tr class="active">
                                    <th>Total:</th>
                                    <th class="text-right">$<?= number_format($total, 2) ?></th>
                                </tr>
                            </table>

                            <?php if (!empty($productos)): ?>
                                <a href="CheckoutController.php" class="btn btn-success btn-block" id="btnPagar">
                                    <i class="fa fa-money"></i> Pagar en Efectivo
                                </a>
                                <button class="btn btn-default btn-block btn-vaciar-carrito">
                                    <i class="fa fa-trash"></i> Vaciar carrito
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <?php include("LibraryJs.php"); ?>
    <script>
        $(document).ready(function() {
            // Actualizar cantidad
            $(document).on('change', '.cantidad-producto', function() {
                const id = $(this).data('id');
                const cantidad = $(this).val();

                if (cantidad < 1) {
                    $(this).val(1);
                    return;
                }

                // Mostrar carga
                const input = $(this);
                input.prop('disabled', true);

                $.ajax({
                    url: 'CarritoController.php?action=actualizar',
                    type: 'POST',
                    data: {
                        id_detalle: id,
                        cantidad: cantidad
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Error al actualizar cantidad');
                            input.prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error en la conexión');
                        input.prop('disabled', false);
                    }
                });
            });

            // Eliminar producto
            $(document).on('click', '.btn-eliminar', function() {
                if (!confirm('¿Eliminar este producto del carrito?')) return;

                const id = $(this).data('id');
                const button = $(this);
                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: 'CarritoController.php?action=eliminar',
                    type: 'POST',
                    data: {
                        id_detalle: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Error al eliminar producto');
                            button.prop('disabled', false).html('<i class="fa fa-trash"></i> Eliminar');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error en la conexión');
                        button.prop('disabled', false).html('<i class="fa fa-trash"></i> Eliminar');
                    }
                });
            });

            // Vaciar carrito
            $('.btn-vaciar-carrito').click(function() {
                if (!confirm('¿Vaciar todo el carrito de compras?')) return;

                const button = $(this);
                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: 'CarritoController.php?action=vaciar',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Error al vaciar carrito');
                            button.prop('disabled', false).html('<i class="fa fa-trash"></i> Vaciar carrito');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error en la conexión');
                        button.prop('disabled', false).html('<i class="fa fa-trash"></i> Vaciar carrito');
                    }
                });
            });

            //FUNCION PARA PAGAR EN EFECTIVO
            $('#btnPagar').click(function(e) {
                e.preventDefault();

                // Mostrar loading
                $(this).html('<i class="fa fa-spinner fa-spin"></i> Procesando...').prop('disabled', true);

                // Opcional: Pedir dirección de entrega
                const direccion = prompt("¿Dónde deseas recibir tu pedido? (Opcional)", "");

                $.ajax({
                    url: '../Controller/CheckoutController.php?action=procesarPago',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        direccion: direccion
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.href = '../Controller/CheckoutView.php';
                            }
                        } else {
                            alert(response.message);
                            $('#btnPagar').html('<i class="fa fa-money"></i> Pagar en Efectivo').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        alert('Error de conexión: ' + xhr.responseText);
                        $('#btnPagar').html('<i class="fa fa-money"></i> Pagar en Efectivo').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>

</html>