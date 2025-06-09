<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container">
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

        <!-- Contenido Principal -->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-users"></i> CLIENTES DEL VENDEDOR</h3>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i><a href="principal.php">Inicio</a>
                            </li>
                            <li>
                                <i class="fa fa-users"></i><a href="#">Clientes del Vendedor</a>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Clientes del Vendedor</h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Cliente</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <tr>
                                                <td><?= $cliente['id_usuario'] ?></td>
                                                <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                                                <td><?= htmlspecialchars($cliente['email']) ?></td>
                                                <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#clienteDetalleModal" onclick="verDetalleCliente(<?= $cliente['id_usuario'] ?>)">
                                                        <i class="fa fa-eye"></i> Ver Detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <!-- Modal Detalle del Cliente -->
        <div class="modal fade" id="clienteDetalleModal" tabindex="-1" role="dialog" aria-labelledby="clienteDetalleModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="clienteDetalleModalLabel">Información del Cliente</h3>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-5" id="loadingCliente">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Cargando información del cliente...</p>
                        </div>
                        <div id="clienteContent" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img id="clienteFoto" src="" class="img-thumbnail" style="width: 150px; height: 150px;">
                                    <p id="clienteLogin" class="text-muted"></p>
                                </div>
                                <div class="col-md-8">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-info-circle"></i> Información de contacto
                                        </div>
                                        <div class="panel-body">
                                            <p><strong><i class="icon_paperclip"></i> Nombre: </strong> <span id="clienteNombre"></span></p>
                                            <p><strong><i class="icon_paperclip"></i> Nacimiento: </strong> <span id="clienteFechaNacimiento"></span></p>
                                            <p><strong><i class="icon_paperclip"></i> Genero: </strong> <span id="clienteGenero"></span></p>
                                            <p><strong><i class="fa fa-envelope"></i> Email:</strong> <span id="clienteEmail"></span></p>
                                            <p><strong><i class="fa fa-phone"></i> Teléfono:</strong> <span id="clienteTelefono"></span></p>
                                            <p><strong><i class="fa fa-map-marker"></i> Dirección:</strong> <span id="clienteDireccion"></span></p>
                                        </div>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <i class="fa fa-share-alt"></i> Redes sociales
                                        </div>
                                        <div class="panel-body" id="clienteRedes">
                                            <!-- Las redes sociales se cargarán aquí -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include("LibraryJs.php"); ?>
        <script>
            function verDetalleCliente(idCliente) {
                $('#loadingCliente').show();
                $('#clienteContent').hide();

                $.ajax({
                    url: 'ClientesController.php',
                    type: 'GET',
                    data: {
                        action: 'getDetalleCliente',
                        id: idCliente
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const cliente = response.cliente;

                            $('#clienteFoto').attr('src', '../Public/img/' + (cliente.imagen || 'default.jpg'));
                            $('#clienteLogin').text(cliente.login || 'N/A');
                            $('#clienteNombre').text(cliente.nombre || 'N/A');
                            $('#clienteFechaNacimiento').text(cliente.fecha_nacimiento || 'N/A');
                            $('#clienteGenero').text(cliente.genero || 'N/A');
                            $('#clienteEmail').text(cliente.email || 'N/A');
                            $('#clienteTelefono').text(cliente.telefono || 'N/A');
                            $('#clienteDireccion').text(cliente.direccion || 'N/A');

                            $('#clienteContent').show();
                            $('#loadingCliente').hide();
                        } else {
                            alert('Error al cargar detalles del cliente: ' + (response.message || 'Error desconocido'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error al cargar detalles del cliente: ' + error);
                    }
                });
            }
            $(document).ready(function() {
                $('#clienteDetalleModal').on('show.bs.modal', function() {
                    $('#loadingCliente').show();
                    $('#clienteContent').hide();
                });
            }); 
        </script>
    </section>