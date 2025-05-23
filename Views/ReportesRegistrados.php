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
                    <li><a href="#reportes-vendedores" data-toggle="tab">Vendedores Reportados</a></li>
                    <li><a href="#reportes-usuarios" data-toggle="tab">Clientes Reportados</a></li>
                    <li><a href="#reportes-ordenes" data-toggle="tab">Reportes por Órdenes</a></li>
                </ul>

                <
                    <!-- Contenido de las pestañas -->
                    <div class="tab-content">
                        <!-- Tabla de Reportes de Productos -->
                        <div class="tab-pane active" id="reportes-productos">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    REPORTES DE PRODUCTOS
                                    <div class="pull-right">
                                        <button href="#" title="" data-placement="left" data-toggle="modal"
                                            class="btn btn-primary tooltips" type="button"
                                            data-original-title="Exportar PDF">
                                            <span class="fa fa-file-pdf-o"> </span>
                                            EXPORTAR A PDF
                                        </button>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                     $reportes = $reportesProductos;
                                    $tipo_reporte = 'PRODUCTO';
                                    include("_partials/tabla_reportes.php");
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Reportes de Vendedores -->
                        <div class="tab-pane" id="reportes-vendedores">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    VENDEDORES REPORTADOS
                                    <div class="pull-right">
                                        <button href="#" title="" data-placement="left" data-toggle="modal"
                                            class="btn btn-primary tooltips" type="button"
                                            data-original-title="Exportar PDF">
                                            <span class="fa fa-file-pdf-o"> </span>
                                            EXPORTAR A PDF
                                        </button>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $reportes = $reportesVendedores;
                                    $tipo_reporte = 'VENDEDOR';
                                    include("_partials/tabla_reportes.php");
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Reportes de Clientes -->
                        <div class="tab-pane" id="reportes-usuarios">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    USUARIOS REPORTADOS
                                    <div class="pull-right">
                                        <button href="#" title="" data-placement="left" data-toggle="modal"
                                            class="btn btn-primary tooltips" type="button"
                                            data-original-title="Exportar PDF">
                                            <span class="fa fa-file-pdf-o"> </span>
                                            EXPORTAR A PDF
                                        </button>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $reportes = $reportesUsuarios;
                                    $tipo_reporte = 'USUARIO';
                                    include("_partials/tabla_reportes.php");
                                    ?>
                                </div>
                            </div>
                        </div>

                        <!-- Nueva Tabla de Reportes por Órdenes -->
                        <div class="tab-pane" id="reportes-ordenes">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    REPORTES POR ÓRDENES
                                    <div class="pull-right">
                                        <button href="#" title="" data-placement="left" data-toggle="modal"
                                            class="btn btn-primary tooltips" type="button"
                                            data-original-title="Exportar PDF">
                                            <span class="fa fa-file-pdf-o"> </span>
                                            EXPORTAR A PDF
                                        </button>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php
                                    $reportes = $reportesOrdenes;
                                    $tipo_reporte = 'ORDEN';
                                    include("_partials/tabla_reportes.php");
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
        </section>
    </section>

    <!-- Modal para Detalles del Reporte -->
    <!-- Modal para Detalles del Reporte -->
    <div class="modal fade" id="detalleReporteModal" tabindex="-1" role="dialog" aria-labelledby="detalleReporteModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title text-white" id="detalleReporteModalLabel">
                        Detalles del Reporte #<span id="reporte-id"></span>
                        <small id="reporte-tipo" class="text-light"></small>
                    </h4>
                </div>
                <div class="modal-body" id="detalleReporteContenido">
                    <!-- Contenido dinámico se cargará aquí -->
                    <div class="text-center py-5" id="cargando-reporte">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p>Cargando detalles del reporte...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <i class="fa fa-times"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-success" id="btnResolverReporte">
                        <i class="fa fa-check"></i> Marcar como Resuelto
                    </button>
                    <button type="button" class="btn btn-danger" id="btnRechazarReporte">
                        <i class="fa fa-ban"></i> Rechazar Reporte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plantillas para los diferentes tipos de reporte (hidden) -->
    <div id="templates" style="display:none;">

        <!-- Plantilla para reporte de CLIENTE -->
        <div id="template-cliente">
            <div class="row">
                <div class="col-md-6">
                    <h4><i class="fa fa-user"></i> Información del Cliente Reportado</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="<?= URL_VIEWS ?>${usuario.foto_perfil}"
                                        class="img-thumbnail img-circle" width="120" height="120"
                                        onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.jpg'">
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>ID:</th>
                                            <td>${usuario.id_usuario}</td>
                                        </tr>
                                        <tr>
                                            <th>Nombre:</th>
                                            <td>${usuario.nombre}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>${usuario.email}</td>
                                        </tr>
                                        <tr>
                                            <th>Teléfono:</th>
                                            <td>${usuario.telefono || 'N/A'}</td>
                                        </tr>
                                        <tr>
                                            <th>Registrado:</th>
                                            <td>${new Date(usuario.fecha_registro).toLocaleDateString()}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4><i class="fa fa-flag"></i> Detalles del Reporte</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <th>Fecha:</th>
                                    <td>${new Date(reporte.fecha_reporte).toLocaleString()}</td>
                                </tr>
                                <tr>
                                    <th>Estado:</th>
                                    <td>
                                        <span class="label label-${reporte.estado == 'PENDIENTE' ? 'warning' : reporte.estado == 'RESUELTO' ? 'success' : 'danger'}">
                                            ${reporte.estado}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Reportado por:</th>
                                    <td>${reporte.administrador.nombre} (ID: ${reporte.id_administrador})</td>
                                </tr>
                                <tr>
                                    <th>Motivo:</th>
                                    <td>${reporte.motivo}</td>
                                </tr>
                                <tr>
                                    <th>Comentarios:</th>
                                    <td>${reporte.comentarios || 'Sin comentarios adicionales'}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fa fa-history"></i> Historial del Cliente</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Aquí puedes mostrar historial de compras,
                                reportes anteriores, etc.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plantilla para reporte de VENDEDOR -->
        <div id="template-vendedor">
            <!-- Similar al de cliente pero con datos específicos de vendedor -->
        </div>

        <!-- Plantilla para reporte de PRODUCTO -->
        <div id="template-producto">
            <div class="row">
                <div class="col-md-4">
                    <h4><i class="fa fa-cube"></i> Producto Reportado</h4>
                    <div class="panel panel-default">
                        <div class="panel-body text-center">
                            <img src="<?= URL_VIEWS ?>${producto.imagen}"
                                class="img-thumbnail" style="max-height: 200px;"
                                onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.jpg'">
                            <h4>${producto.nombre_producto}</h4>
                            <p>Código: ${producto.codigo}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <h4><i class="fa fa-info-circle"></i> Detalles del Reporte</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Información del Producto</h5>
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>ID:</th>
                                            <td>${producto.id_producto}</td>
                                        </tr>
                                        <tr>
                                            <th>Precio:</th>
                                            <td>$${producto.precio_venta.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Stock:</th>
                                            <td>${producto.cantidad}</td>
                                        </tr>
                                        <tr>
                                            <th>Categoría:</th>
                                            <td>${producto.categoria.nombre}</td>
                                        </tr>
                                        <tr>
                                            <th>Vendedor:</th>
                                            <td>${producto.vendedor.nombre} (ID: ${producto.id_usuario})</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Detalles del Reporte</h5>
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>Fecha:</th>
                                            <td>${new Date(reporte.fecha_reporte).toLocaleString()}</td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td><span class="label label-${reporte.estado == 'PENDIENTE' ? 'warning' : 'success'}">${reporte.estado}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Motivo:</th>
                                            <td>${reporte.motivo}</td>
                                        </tr>
                                        <tr>
                                            <th>Comentarios:</th>
                                            <td>${reporte.comentarios || 'N/A'}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <h5><i class="fa fa-exclamation-triangle"></i> Descripción del Producto</h5>
                                <p>${producto.descripcion || 'Sin descripción disponible'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plantilla para reporte de ORDEN -->
        <div id="template-orden">
            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fa fa-shopping-cart"></i> Información de la Orden #${orden.id_orden}</h4>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Detalles de la Orden</h5>
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>Fecha:</th>
                                            <td>${new Date(orden.fecha_orden).toLocaleString()}</td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td><span class="label label-${orden.estado.toLowerCase()}">${orden.estado}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Total:</th>
                                            <td>$${orden.total.toFixed(2)}</td>
                                        </tr>
                                        <tr>
                                            <th>Vendedor:</th>
                                            <td>${orden.vendedor.nombre} (ID: ${orden.id_vendedor})</td>
                                        </tr>
                                        <tr>
                                            <th>Cliente:</th>
                                            <td>${orden.cliente.nombre} (ID: ${orden.id_usuario})</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Detalles del Reporte</h5>
                                    <table class="table table-condensed">
                                        <tr>
                                            <th>Fecha Reporte:</th>
                                            <td>${new Date(reporte.fecha_reporte).toLocaleString()}</td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td><span class="label label-${reporte.estado == 'PENDIENTE' ? 'warning' : 'success'}">${reporte.estado}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Motivo:</th>
                                            <td>${reporte.motivo}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <h5 class="mt-3"><i class="fa fa-list"></i> Productos en la Orden</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${orden.detalles.map(item => `
                                        <tr>
                                            <td>
                                                <img src="<?= URL_VIEWS ?>${item.imagen}" width="50"
                                                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/default.jpg'">
                                                ${item.nombre_producto}
                                            </td>
                                            <td>${item.cantidad}</td>
                                            <td>$${item.precio_unitario.toFixed(2)}</td>
                                            <td>$${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                                        </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mt-3">
                                <h5><i class="fa fa-comment"></i> Comentarios del Reporte</h5>
                                <p>${reporte.comentarios || 'No hay comentarios adicionales'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Acciones sobre Reportes -->
    <div class="modal fade" id="accionReporteModal" tabindex="-1" role="dialog"
        aria-labelledby="accionReporteModalLabel">
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
                order: [
                    [0, 'desc']
                ]
            });


        });

        
        

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

        // Función para cargar los detalles del reporte
        function verDetalleReporte(idReporte, tipoReporte) {
            // Mostrar carga
            $('#detalleReporteContenido').html($('#cargando-reporte').html());
            $('#reporte-id').text(idReporte);
            $('#reporte-tipo').text('(' + tipoReporte + ')');

            // Mostrar/ocultar botones según tipo
            if (tipoReporte === 'ORDEN' || tipoReporte === 'PRODUCTO') {
                $('#btnResolverReporte').show();
                $('#btnRechazarReporte').show();
            } else {
                $('#btnResolverReporte').hide();
                $('#btnRechazarReporte').hide();
            }

            // Obtener datos via AJAX
            $.ajax({
                url: 'obtenerDetalleReporte.php',
                type: 'GET',
                data: {
                    id: idReporte,
                    tipo: tipoReporte
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let templateId = 'template-' + tipoReporte.toLowerCase();
                        let template = $('#' + templateId).html();

                        // Reemplazar variables en el template
                        let html = template.replace(/\${([^}]+)}/g, (match, p1) => {
                            return eval('response.data.' + p1) || match;
                        });

                        $('#detalleReporteContenido').html(html);
                    } else {
                        $('#detalleReporteContenido').html(
                            '<div class="alert alert-danger">Error al cargar los detalles: ' + response.message + '</div>'
                        );
                    }
                },
                error: function(xhr) {
                    $('#detalleReporteContenido').html(
                        '<div class="alert alert-danger">Error de conexión: ' + xhr.statusText + '</div>'
                    );
                }
            });

            $('#detalleReporteModal').modal('show');
        }

        // Manejar el botón de resolver reporte
        $('#btnResolverReporte').click(function() {
            let idReporte = $('#reporte-id').text();
            let tipoReporte = $('#reporte-tipo').text().replace(/[()]/g, '');

            if (confirm('¿Estás seguro de marcar este reporte como resuelto?')) {
                $.post('resolverReporte.php', {
                    id: idReporte,
                    tipo: tipoReporte
                }, function(response) {
                    if (response.success) {
                        alert('Reporte marcado como resuelto');
                        $('#detalleReporteModal').modal('hide');
                        location.reload(); // Recargar para actualizar la lista
                    } else {
                        alert('Error: ' + response.message);
                    }
                }, 'json');
            }
        });

        // Manejar el botón de rechazar reporte
        $('#btnRechazarReporte').click(function() {
            let idReporte = $('#reporte-id').text();
            let tipoReporte = $('#reporte-tipo').text().replace(/[()]/g, '');

            if (confirm('¿Estás seguro de rechazar este reporte? Esto no tomará ninguna acción sobre el elemento reportado.')) {
                $.post('rechazarReporte.php', {
                    id: idReporte,
                    tipo: tipoReporte
                }, function(response) {
                    if (response.success) {
                        alert('Reporte rechazado');
                        $('#detalleReporteModal').modal('hide');
                        location.reload(); // Recargar para actualizar la lista
                    } else {
                        alert('Error: ' + response.message);
                    }
                }, 'json');
            }
        });
    </script>
</body>

</html>