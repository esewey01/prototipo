<!DOCTYPE html>
<html lang="en">
<?php
include('Head.php');
?>

<body>
    <section id="container" class="">
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom">
                    <i class="icon_menu"></i>
                </div>
            </div>
            <?PHP include("Logo.php") ?>
            <div class="nav search-row" id="top_menu">
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <!-- Espacio para barra de búsqueda si es necesario -->
                        </form>
                    </li>
                </ul>
            </div>
            <?PHP include("DropDown.php"); ?>
        </header>
        <?PHP include("Menu.php") ?>
    </section>

    <!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <!--overview start-->
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-users"></i> SOLICITUDES DE VENDEDORES</h3>
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
                        <li><i class="fa fa-users"></i><a href="SolicitudesController.php">Solicitudes para ser Vendedores</a></li>
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
                                    <li><a href="#aprobadas" data-toggle="tab">Aprobadas</a></li>
                                    <li><a href="#rechazadas" data-toggle="tab">Rechazadas</a></li>
                                </ul>

                                <!-- Contenido de las pestañas -->
                                <div class="tab-content">
                                    <!-- Pestaña de solicitudes pendientes -->
                                    <div class="tab-pane active" id="pendientes">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaPendientes">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Usuario</th>
                                                        <th>Contacto</th>
                                                        <th>Fecha Solicitud</th>
                                                        <th>Descripción</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($solicitudes_pendientes as $solicitud): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($solicitud['id_solicitud']) ?></td>
                                                            <td>
                                                                <?= htmlspecialchars($solicitud['nombre']) ?><br>
                                                                <small class="text-muted">@<?= htmlspecialchars($solicitud['login']) ?></small>
                                                            </td>
                                                            <td>
                                                                <i class="fa fa-phone"></i> <?= htmlspecialchars($solicitud['telefono']) ?><br>
                                                                <i class="fa fa-envelope"></i> <?= htmlspecialchars($solicitud['email']) ?>
                                                            </td>
                                                            <td><?= $solicitud['fecha_solicitud']->format('d/mY H:m') ?></td>
                                                            <td><?= htmlspecialchars($solicitud['descripcion']) ?></td>
                                                            <td>
                                                                <button class="btn btn-sm btn-success btn-accion"
                                                                    data-id="<?= $solicitud['id_solicitud'] ?>"
                                                                    data-accion="aprobar"
                                                                    data-toggle="tooltip" title="Aprobar">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger btn-accion"
                                                                    data-id="<?= $solicitud['id_solicitud'] ?>"
                                                                    data-accion="rechazar"
                                                                    data-toggle="tooltip" title="Rechazar">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-info btn-detalles"
                                                                    data-id="<?= $solicitud['id_solicitud'] ?>"
                                                                    data-toggle="tooltip" title="Ver detalles">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pestaña de solicitudes aprobadas -->
                                    <div class="tab-pane" id="aprobadas">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaAprobadas">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha Solicitud</th>
                                                        <th>Fecha Aprobación</th>
                                                        <th>Revisor</th>
                                                        <th>Comentarios</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($solicitudes_aprobadas as $solicitud): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($solicitud['id_solicitud']) ?></td>
                                                            <td><?= htmlspecialchars($solicitud['nombre']) ?></td>
                                                            <td><?= $solicitud['fecha_solicitud']->format('d/m/Y H:m') ?></td>
                                                            <td><?= $solicitud['fecha_revision']->format('d/m/Y H:m') ?></td>
                                                            <td><?= htmlspecialchars($solicitud['nombre_revisor']) ?></td>
                                                            <td><?= htmlspecialchars($solicitud['comentarios']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Pestaña de solicitudes rechazadas -->
                                    <div class="tab-pane" id="rechazadas">
                                        <br>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover" id="tablaRechazadas">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Usuario</th>
                                                        <th>Fecha Solicitud</th>
                                                        <th>Fecha Rechazo</th>
                                                        <th>Revisor</th>
                                                        <th>Motivo</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($solicitudes_rechazadas as $solicitud): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($solicitud['id_solicitud']) ?></td>
                                                            <td><?= htmlspecialchars($solicitud['nombre']) ?></td>
                                                            <td><?= $solicitud['fecha_solicitud']->format('d/m/Y H:m') ?></td>
                                                            <td><?= $solicitud['fecha_revision']->format('d/m/Y H:m') ?></td>
                                                            <td><?= htmlspecialchars($solicitud['nombre_revisor']) ?></td>
                                                            <td><?= htmlspecialchars($solicitud['comentarios']) ?></td>
                                                        </tr>
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

            <!-- Modal para detalles de solicitud -->
            <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Detalles de la Solicitud</h4>
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

            <!-- Modal para comentarios al aprobar/rechazar -->
            <div class="modal fade" id="modalComentarios" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="modalAccionTitulo">Procesar Solicitud</h4>
                        </div>
                        <form id="formProcesar">
                            <div class="modal-body">
                                <input type="hidden" id="idSolicitud" name="id_solicitud">
                                <input type="hidden" id="tipoAccion" name="accion">
                                <div class="form-group">
                                    <label for="comentarios">Comentarios:</label>
                                    <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Confirmar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

    <?PHP include("LibraryJs.php"); ?>

    <script>
        $(document).ready(function() {
            // Manejar clic en botones de acción
            $(document).on('click', '.btn-accion', function() {
                var id = $(this).data('id');
                var accion = $(this).data('accion');

                $('#idSolicitud').val(id);
                $('#tipoAccion').val(accion);

                // Configurar título del modal según la acción
                if (accion == 'aprobar') {
                    $('#modalAccionTitulo').text('Aprobar Solicitud');
                } else {
                    $('#modalAccionTitulo').text('Rechazar Solicitud');
                }

                $('#modalComentarios').modal('show');
            });

            // Manejar clic en botón de detalles
            $(document).on('click', '.btn-detalles', function() {
                var id = $(this).data('id');

                if (!id) {
                    alert('ID de solicitud no disponible');
                    return;
                }

                $.ajax({
                    url: 'SolicitudesController.php',
                    type: 'POST',
                    data: {
                        action: 'detalles',
                        id_solicitud: id
                    },
                    success: function(response) {
                        $('#detallesContenido').html(response);
                        $('#modalDetalles').modal('show');
                    },
                    error: function() {
                        alert('Error al cargar los detalles');
                    }
                });
            });

            // Manejar envío del formulario
            $('#formProcesar').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'SolicitudesController.php',
                    type: 'POST',
                    data: $(this).serialize() + '&action=procesar',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error al procesar la solicitud');
                    }
                });
            });

            // Inicializar DataTables
            if ($.fn.DataTable) {
                $('.table').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    }
                });
            }
        });
    </script>

</body>

</html>