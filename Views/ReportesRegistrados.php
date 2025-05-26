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

            <!-- Modal Detalle de Reporte -->
            <div class="modal fade" id="detalleReporteModal" tabindex="-1" role="dialog" aria-labelledby="detalleReporteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title" id="detalleReporteModalLabel">Detalles del Reporte #<span id="reporte-id-modal"></span></h4>
                           
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Información del Reporte</h3>
                                    <p><strong>Tipo de reporte:</strong> <span id="reporte-tipo"></span></p>
                                    <p><strong>Motivo:</strong> <span id="reporte-motivo"></span></p>
                                    <p><strong>Comentarios:</strong> <span id="reporte-comentarios"></span></p>
                                    <p><strong>Fecha:</strong> <span id="reporte-fecha"></span></p>
                                    <p><strong>Estado:</strong> <span id="reporte-estado"></span></p>
                                    <p><strong>Acción tomada:</strong> <span id="reporte-accion-tomada"></span></p>
                                    
                                    <p><strong>Usuario que reportó:</strong> <span id="reporte-administrador"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <div id="detalle-producto-seccion" style="display:none;">
                                        <h3>Información del Producto</h3>
                                        <p><strong>Nombre:</strong> <span id="producto-nombre"></span></p>
                                        <p><strong>Descripción:</strong> <span id="producto-descripcion"></span></p>
                                        <p><strong>Precio:</strong> <span id="producto-precio"></span></p>
                                        <p><strong>Stock:</strong> <span id="producto-stock"></span></p>
                                        <p><strong>Vendedor:</strong> <span id="producto-vendedor"></span></p>
                                        <p><strong>Imagen:</strong> <br><img id="producto-imagen" src="" alt="Imagen Producto" style="max-width: 150px; border: 1px solid #ddd;"></p>
                                    </div>
                                    <div id="detalle-usuario-seccion" style="display:none;">
                                        <h3>Información del Usuario/Vendedor Reportado</h3>
                                        <p><strong>Login:</strong> <span id="usuario-login"></span></p>
                                        <p><strong>Nombre Completo:</strong> <span id="usuario-nombre"></span></p>
                                        <p><strong>Email:</strong> <span id="usuario-email"></span></p>
                                        <p><strong>Teléfono:</strong> <span id="usuario-telefono"></span></p>
                                        <p><strong>Rol:</strong> <span id="usuario-rol"></span></p>
                                    </div>
                                    <div id="detalle-orden-seccion" style="display:none;">
                                        <h3>Información de la Orden</h3>
                                        <p><strong>ID Orden:</strong> <span id="orden-id"></span></p>
                                        <p><strong>Cliente:</strong> <span id="orden-cliente"></span></p>
                                        <p><strong>Fecha Orden:</strong> <span id="orden-fecha"></span></p>
                                        <p><strong>Total:</strong> <span id="orden-total"></span></p>
                                        <p><strong>Estado:</strong> <span id="orden-estado"></span></p>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h3>Historial de Acciones</h3>
                            <div id="historial-reporte-content">
                                <p class="text-muted">Cargando historial...</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-inline">
                                <label for="accionSeleccionada" class="mr-2">Acción a Tomar:</label>
                                <select class="form-control mr-2" id="accionSeleccionada">
                                    <option value="">Selecciona una acción</option>
                                    <option value="SUSPENDER_PRODUCTO">Suspender Producto</option>
                                    <option value="SUSPENDER_CUENTA_VENDEDOR">Suspender Cuenta Vendedor</option>
                                    <option value="SUSPENDER_CUENTA_USUARIO">Suspender Cuenta Usuario</option>
                                    <option value="ENVIAR_AVISO">Enviar Aviso</option>
                                    <option value="BORRAR_REPORTE">Borrar Reporte</option>
                                    <option value="MARCAR_RESUELTO">Marcar como Resuelto</option>
                                    <option value="RECHAZAR_REPORTE">Rechazar Reporte</option>
                                </select>
                                <button type="button" class="btn btn-primary" id="btn-aplicar-accion">Aplicar Acción</button>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-info" id="btn-descargar-pdf">Descargar PDF</button>
                        </div>
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
                    "zeroRecords": "No se encontraron resultados :(",
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

            // Abrir modal y cargar detalles del reporte
            $(document).on('click', '.btn-ver-detalle', function() { // Cambié a .btn-ver-detalle para tu HTML
                const idReporte = $(this).data('id');
                const tipoReporte = $(this).data('tipo');

                // Limpiar contenido previo y ocultar secciones
                $('#detalleReporteContent').empty();
                $('#detalle-producto-seccion').hide();
                $('#detalle-usuario-seccion').hide();
                $('#detalle-orden-seccion').hide();
                $('#historial-reporte-content').html('<p class="text-muted">Cargando historial...</p>');
                $('#reporte-id-modal').text(''); // Limpiar el ID en el título del modal

                $.ajax({
                    url: 'ReportesController.php',
                    type: 'GET',
                    data: {
                        action: 'getDetalleReporte',
                        id: idReporte,
                        tipo: tipoReporte
                    },
                    dataType: 'json', // Es crucial especificar que esperas JSON
                    success: function(response) {
                        if (response.success) {
                            const reporte = response.reporte;
                            const producto = response.producto;
                            const usuario = response.usuario;
                            const orden = response.orden;
                            const historial = response.historial;

                            // Rellenar información del reporte
                            $('#reporte-id-modal').text(reporte.id_reporte);
                            $('#reporte-tipo').text(reporte.tipo_reporte || 'N/A');
                            $('#reporte-motivo').text(reporte.motivo || 'N/A');
                            $('#reporte-fecha').text(reporte.fecha_reporte||'N/A');
                            $('#reporte-estado').text(reporte.estado || 'N/A');
                            $('#reporte-accion-tomada').text(reporte.accion_tomada || 'N/A');
                            $('#reporte-comentarios').text(reporte.comentarios || 'N/A');
                            $('#reporte-administrador').text((reporte.nombre_administrador || '') + ' ' + (reporte.apellido_administrador || ''));

                            // Mostrar información específica según el tipo de reporte
                            if (reporte.tipo_reporte === 'PRODUCTO' && producto) {
                                $('#detalle-producto-seccion').show();
                                $('#producto-nombre').text(producto.nombre_producto || 'N/A');
                                $('#producto-descripcion').text(producto.descripcion || 'N/A');
                                $('#producto-precio').text(parseFloat(producto.precio || 0).toFixed(2));
                                $('#producto-stock').text(producto.stock || 'N/A');
                                $('#producto-vendedor').text((producto.vendedor_nombre || '') + ' ' + (producto.vendedor_apellido || ''));
                                $('#producto-imagen').attr('src', '../Public/img/' + (producto.imagen || 'default.jpg')); // Asegúrate de tener una imagen por defecto
                            } else if ((reporte.tipo_reporte === 'VENDEDOR' || reporte.tipo_reporte === 'USUARIO') && usuario) {
                                $('#detalle-usuario-seccion').show();
                                $('#usuario-login').text(usuario.login || 'N/A');
                                $('#usuario-nombre').text((usuario.nombre || '') + ' ' + (usuario.apellido || ''));
                                $('#usuario-email').text(usuario.email || 'N/A');
                                $('#usuario-telefono').text(usuario.telefono || 'N/A');
                                $('#usuario-rol').text(usuario.nombre_rol || 'N/A');
                            } else if (reporte.tipo_reporte === 'ORDEN' && orden) {
                                $('#detalle-orden-seccion').show();
                                $('#orden-id').text(orden.id_orden || 'N/A');
                                $('#orden-cliente').text((orden.cliente_nombre || '') + ' ' + (orden.cliente_apellido || ''));
                                $('#orden-fecha').text(orden.fecha_orden ? new Date(orden.fecha_orden).toLocaleString() : 'N/A');
                                $('#orden-total').text(parseFloat(orden.total || 0).toFixed(2));
                                $('#orden-estado').text(orden.estado || 'N/A');
                            }

                            // Cargar historial de acciones
                            let historialHtml = '';
                            if (historial && historial.length > 0) {
                                historialHtml += '<table class="table table-bordered table-striped table-sm"><thead><tr><th>Fecha</th><th>Acción</th><th>Comentarios</th><th>Administrador</th></tr></thead><tbody>';
                                historial.forEach(function(h) {
                                    historialHtml += `<tr>
                                        <td>${h.fecha_accion ? new Date(h.fecha_accion).toLocaleString() : 'N/A'}</td>
                                        <td>${h.accion_tomada || 'N/A'}</td>
                                        <td>${h.comentarios || 'N/A'}</td>
                                        <td>${(h.nombre_administrador || '') + ' ' + (h.apellido_administrador || '')}</td>
                                    </tr>`;
                                });
                                historialHtml += '</tbody></table>';
                            } else {
                                historialHtml = '<p class="text-muted">No hay historial de acciones para este reporte.</p>';
                            }
                            $('#historial-reporte-content').html(historialHtml);

                            $('#detalleReporteModal').modal('show');
                        } else {
                            alert('Error al cargar detalles del reporte: ' + (response.message || 'Error desconocido'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error al cargar detalles del reporte: ' + error);
                    }
                });
            });

            // Aplicar acción al reporte
            $('#btn-aplicar-accion').click(function() {
                const idReporte = $('#reporte-id-modal').text(); // Obtener el ID del reporte desde el modal
                const accion = $('#accionSeleccionada').val();
                const comentarios = prompt("Ingrese comentarios adicionales para esta acción (opcional):");

                if (!accion) {
                    alert('Por favor selecciona una acción');
                    return;
                }

                let confirmacion = confirm(`¿Estás seguro de aplicar la acción "${$('#accionSeleccionada option:selected').text()}" al reporte #${idReporte}?`);

                if (confirmacion) {
                    $.ajax({
                        url: 'ReportesController.php',
                        type: 'POST',
                        data: {
                            action: 'aplicarAccion',
                            id_reporte: idReporte,
                            accion: accion,
                            comentarios: comentarios
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert('Acción aplicada correctamente');
                                $('#detalleReporteModal').modal('hide');
                                location.reload(); // Recargar la página para ver los cambios
                            } else {
                                alert('Error al aplicar la acción: ' + (response.message || 'Error desconocido'));
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Error al aplicar la acción: ' + error);
                        }
                    });
                }
            });

            // Botón para descargar PDF
            $('#btn-descargar-pdf').click(function() {
                const idReporte = $('#reporte-id-modal').text();
                if (idReporte) {
                    window.open('ReportesController.php?action=generarPdfReporte&id=' + idReporte, '_blank');
                } else {
                    alert('No se pudo obtener el ID del reporte para descargar el PDF.');
                }
            });
        });

        // Asegúrate de que esta función exista si la llamas directamente desde el HTML (aunque ya no es necesario con el botón JS)
        function descargarReporte(idReporte) {
            // Esta función ahora es redundante si usas el $('#btn-descargar-pdf').click()
            // Pero la mantengo por si hay alguna llamada residual en otro lugar
            window.open('ReportesController.php?action=generarPdfReporte&id=' + idReporte, '_blank');
        }
    </script>
</body>

</html>