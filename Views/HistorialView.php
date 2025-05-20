<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Historial de Compras</title>
    <style>
        .badge-pendiente {
            background-color: #ffc107;
            color: #000;
        }

        .badge-pagado {
            background-color: #28a745;
        }

        .badge-entregado {
            background-color: #17a2b8;
        }

        .badge-cancelado {
            background-color: #dc3545;
        }

        .nav-tabs .nav-link.active {
            font-weight: bold;
        }

        .tab-content {
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <section id="container">
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Menú Principal" data-placement="bottom">
                    <i class="icon_menu"></i>
                </div>
            </div>
            <?PHP include("Logo.php") ?>
            <div class="nav search-row" id="top_menu">
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Buscar..." type="text">
                        </form>
                    </li>
                </ul>
            </div>
            <?PHP include("DropDown.php"); ?>
        </header>

        <?PHP include("Menu.php") ?>

        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="icon_document"></i> Mi Historial de Compras</h3>

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
                        endif;
                        ?>

                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                            <li><i class="icon_document"></i> Historial</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">
                            <header class="panel-heading">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link <?= (!isset($_GET['estado'])) ? 'active' : '' ?>"
                                            href="HistorialController.php">Todos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($_GET['estado'] ?? '') == 'PENDIENTE' ? 'active' : '' ?>"
                                            href="HistorialController.php?estado=PENDIENTE">Pendientes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($_GET['estado'] ?? '') == 'PAGADO' ? 'active' : '' ?>"
                                            href="HistorialController.php?estado=PAGADO">Pagados</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($_GET['estado'] ?? '') == 'ENTREGADO' ? 'active' : '' ?>"
                                            href="HistorialController.php?estado=ENTREGADO">Entregados</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?= ($_GET['estado'] ?? '') == 'CANCELADO' ? 'active' : '' ?>"
                                            href="HistorialController.php?estado=CANCELADO">Cancelados</a>
                                    </li>
                                </ul>
                            </header>
                            <div class="panel-body">
                                <?php if (empty($ordenes)): ?>
                                    <div class="alert alert-info text-center">
                                        <i class="fa fa-info-circle fa-2x"></i><br>
                                        No se encontraron órdenes en este estado.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Fecha</th>
                                                    <th>Vendedor</th>
                                                    <th>Productos</th>
                                                    <th>Total</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($ordenes as $orden): ?>
                                                    <tr>
                                                        <td>#<?= $orden['id_orden'] ?></td>
                                                        <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                        <td><?= htmlspecialchars($orden['vendedor_nombre'] ?? 'N/A') ?></td>
                                                        <td><?= $orden['total_productos'] ?></td>
                                                        <td>$<?= number_format($orden['total'], 2) ?></td>
                                                        <td>
                                                            <span class="badge 
                                                                <?= $orden['estado'] == 'PAGADO' ? 'badge-pagado' : ($orden['estado'] == 'PENDIENTE' ? 'badge-pendiente' : ($orden['estado'] == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado')) ?>">
                                                                <?= $orden['estado'] ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-info btn-ver-detalle"
                                                                data-id="<?= $orden['id_orden'] ?>">
                                                                <i class="fa fa-eye"></i> Detalles
                                                            </button>

                                                            <?php if ($orden['estado'] == 'PENDIENTE'): ?>
                                                                <button class="btn btn-sm btn-warning btn-reportar"
                                                                    data-id="<?= $orden['id_orden'] ?>">
                                                                    <i class="fa fa-exclamation-triangle"></i> Reportar
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
                </div>
            </section>
        </section>

        <!-- Modal para detalles -->
        <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Detalles de Orden #<span id="modalOrdenId"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="detallesContenido">
                        <!-- Contenido cargado por AJAX -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para reportar -->
        <div class="modal fade" id="reportarModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Reportar Orden #<span id="reportarOrdenId"></span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="formReportar">
                        <div class="modal-body">
                            <input type="hidden" id="idOrdenReportar" name="id_orden">
                            <div class="form-group">
                                <label>Motivo del reporte</label>
                                <textarea class="form-control" name="motivo" rows="4" required
                                    placeholder="Describe el problema con esta orden (ej. El vendedor no ha actualizado el estado, no he recibido mi pedido, etc.)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Enviar Reporte</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?PHP include("LibraryJs.php"); ?>

        <script>
            $(document).ready(function() {
                // Ver detalles de la orden
                $(document).on('click', '.btn-ver-detalle', function() {
                    var id = $(this).data('id');

                    $.ajax({
                        url: 'HistorialController.php?action=ver&id=' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $('#modalOrdenId').text(response.orden.id_orden);

                            var html = `
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Información de la Orden</h5>
                                    <p><strong>Fecha:</strong> ${response.fecha_formateada}</p>
                                    <p><strong>Estado:</strong> 
                                        <span class="badge ${response.orden.estado == 'PAGADO' ? 'badge-pagado' : 
                                            response.orden.estado == 'PENDIENTE' ? 'badge-pendiente' : 
                                            response.orden.estado == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado'}">
                                            ${response.orden.estado}
                                        </span>
                                    </p>
                                    <p><strong>Total:</strong> $${response.orden.total.toFixed(2)}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Vendedor</h5>
                                    <p>${response.orden.vendedor_nombre || 'N/A'}</p>
                                </div>
                            </div>
                            
                            <h5 class="mt-4">Productos</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            response.detalles.forEach(function(item) {
                                html += `
                                <tr>
                                    <td>
                                        <img src="<?= URL_VIEWS ?>${item.imagen}" width="50" height="50" class="img-thumbnail">
                                        ${item.nombre_producto}
                                    </td>
                                    <td>${item.cantidad}</td>
                                    <td>$${item.precio_unitario.toFixed(2)}</td>
                                    <td>$${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                                </tr>`;
                            });

                            html += `
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total:</th>
                                            <th>$${response.orden.total.toFixed(2)}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>`;

                            $('#detallesContenido').html(html);
                            $('#detalleModal').modal('show');
                        },
                        error: function() {
                            alert('Error al cargar los detalles');
                        }
                    });
                });

                // Reportar orden
                $(document).on('click', '.btn-reportar', function() {
                    var id = $(this).data('id');
                    $('#idOrdenReportar').val(id);
                    $('#reportarOrdenId').text(id);
                    $('#reportarModal').modal('show');
                });

                // Enviar reporte
                // En el script de HistorialView.php
                $('#formReportar').on('submit', function(e) {
                    e.preventDefault();

                    // Validación adicional
                    if ($('#formReportar textarea').val().length < 10) {
                        alert('Por favor describe el problema con más detalle (mínimo 10 caracteres)');
                        return;
                    }

                    $.ajax({
                        url: 'HistorialController.php?action=reportar',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        beforeSend: function() {
                            $('#formReportar button[type="submit"]')
                                .prop('disabled', true)
                                .html('<i class="fa fa-spinner fa-spin"></i> Enviando...');
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                $('#reportarModal').modal('hide');
                                // Opcional: actualizar solo la fila afectada
                                $('.btn-reportar[data-id="' + $('#idOrdenReportar').val() + '"]')
                                    .replaceWith('<span class="badge badge-info">Reportado</span>');
                            } else {
                                toastr.error(response.message || 'Error al enviar reporte');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Error de conexión: ' + xhr.statusText);
                        },
                        complete: function() {
                            $('#formReportar button[type="submit"]')
                                .prop('disabled', false)
                                .html('Enviar Reporte');
                        }
                    });
                });
            });
        </script>
    </section>
</body>

</html>