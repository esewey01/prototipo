<!DOCTYPE html>
<html lang="en">

<?php
include('Head.php');
?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


    <section id="container" class="">
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i
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
            <?PHP include("DropDown.php"); ?>
        </header>

        <?PHP include("Menu.php") ?>

    </section>

    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="icon_cart"></i> Carrito</h3>
                    <!--FUNCION DE ALERTA DE MENSAJES-->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong><?= $_SESSION['mensaje'] . ': ' . $_SESSION['usuario']['rol']['nombre_rol'] ?></strong>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                        <li><i class="icon_cart"></i><a href="CarritoController.php"> Carrito</a></li>
                        <li><i class="icon_document"></i><a href="HistorialController.php">Historial</a></li> 
                    </ol>
                </div>
            </div>

            <div class="container mt-4">
                <h2 class="mb-4">Tu Carrito de Compras</h2>

                <?php if (empty($items)): ?>
                    <div class="alert alert-info">
                        Tu carrito está vacío. <a href="productos.php">Explora nuestros productos</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($items as $item):
                                    $subtotal = $item['precio_unitario'] * $item['cantidad'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($item['imagen']) ?>"
                                                    alt="<?= htmlspecialchars($item['nombre_producto']) ?>"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                <div class="ms-3">
                                                    <h6><?= htmlspecialchars($item['nombre_producto']) ?></h6>
                                                    <small class="text-muted">Vendedor: <?= htmlspecialchars($item['nombre_vendedor']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                                        <td>
                                            <div class="input-group" style="max-width: 120px;">
                                                <button class="btn btn-outline-secondary update-qty"
                                                    data-id="<?= $item['id_item'] ?>"
                                                    data-action="minus">-</button>
                                                <input type="number" class="form-control text-center"
                                                    value="<?= $item['cantidad'] ?>" min="1"
                                                    id="qty-<?= $item['id_item'] ?>">
                                                <button class="btn btn-outline-secondary update-qty"
                                                    data-id="<?= $item['id_item'] ?>"
                                                    data-action="plus">+</button>
                                            </div>
                                        </td>
                                        <td>$<?= number_format($subtotal, 2) ?></td>
                                        <td>
                                            <button class="btn btn-danger btn-sm remove-item"
                                                data-id="<?= $item['id_item'] ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td colspan="2"><strong>$<?= number_format($total, 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="productos.php" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i> Seguir comprando
                        </a>
                        <a href="checkout.php" class="btn btn-primary">
                            Proceder al pago <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <script>
                $(document).ready(function() {
                    $('.update-qty').click(function() {
                        var id = $(this).data('id');
                        var action = $(this).data('action');
                        var input = $('#qty-' + id);
                        var currentVal = parseInt(input.val());
                        var newVal = action == 'plus' ? currentVal + 1 : Math.max(1, currentVal - 1);

                        input.val(newVal);
                        updateCartItem(id, newVal);
                    });

                    $('.remove-item').click(function() {
                        if (confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                            var id = $(this).data('id');
                            removeCartItem(id);
                        }
                    });

                    function updateCartItem(id, quantity) {
                        $.ajax({
                            url: 'CarritoController.php?action=actualizar',
                            method: 'POST',
                            data: {
                                id_item: id,
                                cantidad: quantity
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert(response.message);
                                }
                            }
                        });
                    }

                    function removeCartItem(id) {
                        $.ajax({
                            url: 'CarritoController.php?action=eliminar',
                            method: 'POST',
                            data: {
                                id_item: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    location.reload();
                                } else {
                                    alert(response.message);
                                }
                            }
                        });
                    }
                });
            </script>

            <?php include('../includes/footer_cliente.php'); ?>

        </section>
    </section>
    <?php include('LibraryJS.php'); ?>
</body>
</html>