<!DOCTYPE html>
<html lang="es">
<?php include('Head.php');?>

<body>
    <section id="container" class="">
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
                    <h3 class="page-header"><i class="icon_bag"></i> Gestión de Ventas</h3>

                    <!--FUNCION DE ALERTA DE MENSAJES-->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> 
                        alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong><?= htmlspecialchars($_SESSION['mensaje'])?></strong>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>

                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                        <li><i class="icon_bag"></i>Ventas</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-default">
                            <div class="panel-body">
                                <!-- Pestañas -->
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#pendientes" data-toggle="tab">Pendientes</a></li>
                                    <li><a href="#pagadas" data-toggle="tab">Pagadas</a></li>
                                    <li><a href="#entregadas" data-toggle="tab">Entregadas</a></li>
                                    <li><a href="#canceladas" data-toggle="tab">Canceladas</a></li>
                                </ul>

                                <!-- Contenido de las pestañas -->
                                <div class="tab-content">
                                    <!-- Pestaña de ventas pendientes -->
                                    <div class="tab-pane active" id="pendientes">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaPendientes">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Usuario</th>
                                                        <th>Productos</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ordenes as $orden): ?>
                                                        <?php if ($orden['estado'] == 'PENDIENTE'): ?>
                                                            <tr>
                                                                <td>#<?= $orden['id_orden'] ?></td>
                                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                                                                <td>@<?= htmlspecialchars($orden['cliente_login']) ?></td>
                                                                <td><?= $orden['total_productos'] ?></td>
                                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-info btn-detalles"
                                                                        data-id="<?= $orden['id_orden'] ?>"
                                                                        data-toggle="tooltip" title="Ver detalles">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pestaña de ventas pagadas -->
                                    <div class="tab-pane" id="pagadas">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaPagadas">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Usuario</th>
                                                        <th>Productos</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ordenes as $orden): ?>
                                                        <?php if ($orden['estado'] == 'PAGADO'): ?>
                                                            <tr>
                                                                <td>#<?= $orden['id_orden'] ?></td>
                                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                                                                <td>@<?= htmlspecialchars($orden['cliente_login']) ?></td>
                                                                <td><?= $orden['total_productos'] ?></td>
                                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-info btn-detalles"
                                                                        data-id="<?= $orden['id_orden'] ?>"
                                                                        data-toggle="tooltip" title="Ver detalles">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pestaña de ventas entregadas -->
                                    <div class="tab-pane" id="entregadas">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaEntregadas">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Usuario</th>
                                                        <th>Productos</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ordenes as $orden): ?>
                                                        <?php if ($orden['estado'] == 'ENTREGADO'): ?>
                                                            <tr>
                                                                <td>#<?= $orden['id_orden'] ?></td>
                                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                                                                <td>@<?= htmlspecialchars($orden['cliente_login']) ?></td>
                                                                <td><?= $orden['total_productos'] ?></td>
                                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-info btn-detalles"
                                                                        data-id="<?= $orden['id_orden'] ?>"
                                                                        data-toggle="tooltip" title="Ver detalles">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pestaña de ventas canceladas -->
                                    <div class="tab-pane" id="canceladas">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaCanceladas">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Fecha</th>
                                                        <th>Cliente</th>
                                                        <th>Usuario</th>
                                                        <th>Productos</th>
                                                        <th>Total</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ordenes as $orden): ?>
                                                        <?php if ($orden['estado'] == 'CANCELADO'): ?>
                                                            <tr>
                                                                <td>#<?= $orden['id_orden'] ?></td>
                                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                                <td><?= htmlspecialchars($orden['cliente_nombre']) ?></td>
                                                                <td>@<?= htmlspecialchars($orden['cliente_login']) ?></td>
                                                                <td><?= $orden['total_productos'] ?></td>
                                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-info btn-detalles"
                                                                        data-id="<?= $orden['id_orden'] ?>"
                                                                        data-toggle="tooltip" title="Ver detalles">
                                                                        <i class="fa fa-eye"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </header>
                    </section>
                </div>
            </div>

            <!-- Modal para detalles de venta -->
            <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Detalles de Venta #<span id="modalOrdenId"></span></h4>
                        </div>
                        <div class="modal-body" id="detallesContenido">
                            <!-- Contenido cargado por AJAX -->
                        </div>
                        <div class="modal-footer">
                            <form id="formCambiarEstado" class="form-inline pull-left">
                                <input type="hidden" id="idOrden" name="id_orden">
                                <div class="form-group">
                                    <label for="nuevoEstado" class="mr-2">Cambiar estado:</label>
                                    <select class="form-control" id="nuevoEstado" name="estado">
                                        <option value="PENDIENTE">Pendiente</option>
                                        <option value="PAGADO">Pagado</option>
                                        <option value="ENTREGADO">Entregado</option>
                                        <option value="CANCELADO">Cancelado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary ml-2">Actualizar</button>
                            </form>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <?PHP include("LibraryJs.php"); ?>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables
            if ($.fn.DataTable) {
                $('.table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "order": [
                        [1, "desc"]
                    ] // Ordenar por fecha descendente
                });
            }

            // Manejar clic en botón de detalles
            $(document).on('click', '.btn-detalles', function() {
                var id = $(this).data('id');

                if (!id) {
                    alert('ID de orden no disponible');
                    return;
                }

                // Cargar detalles de la orden
                $.ajax({
                    url: 'VendedorDetalleOrdenController.php',
                    type: 'GET',
                    data: {
                        id: id,
                        modal: true
                    },
                    success: function(response) {
                        $('#modalOrdenId').text(id);
                        $('#idOrden').val(id);
                        $('#detallesContenido').html(response);

                        // Obtener el estado actual para configurar el select
                        var estadoActual = $('#detallesContenido').find('.badge').text().trim();
                        $('#nuevoEstado').val(estadoActual);

                        $('#modalDetalles').modal('show');
                    },
                    error: function() {
                        alert('Error al cargar los detalles');
                    }
                });
            });
            // Manejar envío del formulario de cambio de estado
            $('#formCambiarEstado').on('submit', function(e) {
                e.preventDefault();

                var idOrden = $('#idOrden').val();
                var nuevoEstado = $('#nuevoEstado').val();

                $.ajax({
                    url: 'VentasController.php?action=actualizar_estado',
                    type: 'POST',
                    data: {
                        id_orden: idOrden,
                        estado: nuevoEstado
                    },
                    success: function(response) {
                        $('#modalDetalles').modal('hide');
                        location.reload(); // Recargar la página para ver los cambios
                    },
                    error: function() {
                        alert('Error al actualizar el estado');
                    }
                });
            });


        });
    </script>
</body>

</html>