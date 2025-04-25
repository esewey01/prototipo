<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

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

        <!-- Contenido Principal -->
        <section id="main-content">
            <section class="wrapper">
                <!-- Título y Breadcrumb -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-flag"></i> ADMINISTRACIÓN DE REPORTES</h3>
                        <!-- Alerta de mensajes -->
                        <div class="<?php echo $alerta; ?>" role="alert">
                            <strong><?php echo $mensaje; ?></strong>
                            <strong><?php echo $error; ?></strong>
                        </div>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i><a href="principal.php">Inicio</a>
                            </li>
                            <li>
                                <i class="fa fa-flag"></i><a href="#">Reportes</a>
                            </li>
                        </ol>
                    </div>
                </div>

                <!-- Pestañas de navegación -->
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#reportes-productos" data-toggle="tab">Reportes de Productos</a></li>
                    <li><a href="#reportes-usuarios" data-toggle="tab">Reportes de Usuarios</a></li>
                </ul>

                <!-- Contenido de las pestañas -->
                <div class="tab-content">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            LISTA DE REPORTES CARGADOS AL SISTEMA
                            <div align="right">
                                <button href="#" title="" data-placement="left" data-toggle="modal"
                                    class="btn btn-primary tooltips" type="button"
                                    data-original-title="Nuevo Usuario">
                                    <span class="fa fa-file-pdf-o"> </span>
                                    EXPORTAR A PDF
                                </button>
                            </div>
                        </div>
                        
                        <div class="panel-body">
                            <!-- Tabla de Reportes de Productos -->
                            <div class="tab-pane active" id="reportes-productos">
                                <?php
                                $tipo_reporte = 'PRODUCTO';
                                include("_partials/tabla_reportes.php");
                                ?>
                            </div>

                            <!-- Tabla de Reportes de Usuarios -->
                            <div class="tab-pane" id="reportes-usuarios">
                                <?php
                                $tipo_reporte = 'USUARIO';
                                include("_partials/tabla_reportes.php");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Detalles del Reporte -->
                <div class="modal fade" id="detalleReporteModal" tabindex="-1" role="dialog" aria-labelledby="detalleReporteModalLabel">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title text-white" id="detalleReporteModalLabel">Detalles del Reporte</h4>
                            </div>
                            <div class="modal-body" id="detalleReporteContenido">
                                <!-- Cargado por AJAX -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="btnResolverReporte">Marcar como Resuelto</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para Acciones sobre Reportes -->
                <div class="modal fade" id="accionReporteModal" tabindex="-1" role="dialog" aria-labelledby="accionReporteModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="formAccionReporte" method="POST">
                                <input type="hidden" name="id_reporte" id="accion_id_reporte">
                                <input type="hidden" name="tipo_reporte" id="accion_tipo_reporte">
                                
                                <div class="modal-header bg-warning">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4 class="modal-title text-white" id="accionReporteModalLabel">Tomar Acción</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="accion_seleccionada">Acción a tomar:</label>
                                        <select class="form-control" name="accion_tomada" id="accion_seleccionada" required>
                                            <option value="">Seleccione una acción</option>
                                            <option value="ADVERTENCIA">Enviar advertencia</option>
                                            <option value="DESACTIVAR">Desactivar producto/usuario</option>
                                            <option value="SUSPENDER">Suspender temporalmente</option>
                                            <option value="BANEAR">Banear permanentemente</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="comentarios_accion">Comentarios:</label>
                                        <textarea class="form-control" name="comentarios" id="comentarios_accion" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-warning">Aplicar Acción</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <?php include("LibraryJs.php"); ?>

        <script>
            $(document).ready(function() {
                // Inicializar DataTables
                $('.table-reportes').DataTable({
                    responsive: true,
                    dom: '<"top"lf>rt<"bottom"ip>',
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Todos"]
                    ],
                    language: {
                        "search": "Buscar:",
                        "lengthMenu": "Mostrar _MENU_ registros por página",
                        "zeroRecords": "No se encontraron resultados",
                        "info": "Mostrando página _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay registros disponibles",
                        "infoFiltered": "(filtrado de _MAX_ registros totales)",
                        "paginate": {
                            "first": "Primera",
                            "last": "Última",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    },
                    order: [[0, 'desc']]
                });

                // Activar la pestaña correcta al recargar
                $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    localStorage.setItem('ultimaPestañaReportes', $(e.target).attr('href'));
                });
                
                var ultimaPestaña = localStorage.getItem('ultimaPestañaReportes');
                if (ultimaPestaña) {
                    $('[href="' + ultimaPestaña + '"]').tab('show');
                }
            });

            // Función para ver detalles del reporte
            function verDetalleReporte(idReporte, tipoReporte) {
                $.get('DetalleReporte.php?id=' + idReporte + '&tipo=' + tipoReporte, function(data) {
                    $('#detalleReporteContenido').html(data);
                    $('#accion_id_reporte').val(idReporte);
                    $('#accion_tipo_reporte').val(tipoReporte);
                    $('#detalleReporteModal').modal('show');
                }).fail(function() {
                    alert('Error al cargar los detalles del reporte');
                });
            }

            // Manejar el envío del formulario de acción
            $('#formAccionReporte').submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: 'ProcesarAccionReporte.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            alert(result.message);
                            $('#accionReporteModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    },
                    error: function() {
                        alert('Error al procesar la acción');
                    }
                });
            });

            // Botón para marcar como resuelto
            $('#btnResolverReporte').click(function() {
                var idReporte = $('#accion_id_reporte').val();
                var tipoReporte = $('#accion_tipo_reporte').val();
                
                if (confirm('¿Está seguro de marcar este reporte como resuelto?')) {
                    $.post('ResolverReporte.php', {
                        id: idReporte,
                        tipo: tipoReporte
                    }, function(response) {
                        var result = JSON.parse(response);
                        if (result.success) {
                            alert(result.message);
                            $('#detalleReporteModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    });
                }
            });
        </script>
    </body>
</html>