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
                        endif; ?>

                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                            <li><i class="icon_document"></i> Historial</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <section class="panel">

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
                        <button type="button" class="close" data-diFORMsmiss="modal">&times;</button>
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


        <!-- Modal para información del vendedor (similar al de ComprarView) -->
        <div class="modal fade" id="vendedorDetalleModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Información del Vendedor</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="loadingVendedor" class="text-center py-5">
                            <i class="fa fa-spinner fa-spin fa-3x"></i>
                            <p>Cargando información del vendedor...</p>
                        </div>
                        <div id="vendedorContent" style="display: none;">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img id="vendedorFoto" src="" class="img-thumbnail mb-3" style="width: 150px; height: 150px;">
                                    <h4 id="vendedorNombre"></h4>
                                    <p class="text-muted" id="vendedorLogin"></p>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Teléfono:</strong> <span id="vendedorTelefono"></span></p>
                                            <p><strong>Email:</strong> <span id="vendedorEmail"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Dirección:</strong> <span id="vendedorDireccion"></span></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Redes Sociales</h5>
                                    <div id="vendedorRedes"></div>
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

        <?PHP include("LibraryJs.php"); ?>
        <?php include('../Views/UsuarioDetalleModal.php'); ?>

        <script>
            $(document).ready(function() {
                var URL_VIEWS = '<?= URL_VIEWS ?>';
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
                                    <p>
                                        <a href="#" class="btn-vendedor-info" data-id="${response.orden.id_vendedor}">
                                            ${response.orden.vendedor_nombre || 'N/A'}
                                            <i class="icon_info_alt"></i>
                                        </a>
                                    </p>
                                    <p><strong>Dirección de entrega:</strong> ${response.orden.direccion_entrega || 'No especificada'}</p>
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
                                                <img src="${URL_VIEWS}${item.imagen}" width="50" height="50" class="img-thumbnail">
                                                ${item.nombre_producto}
                                                ${item.descripcion ? '<br><small class="text-muted">' + item.descripcion.substring(0, 50) + '...</small>' : ''}
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

                // Mostrar información del vendedor

                // Mostrar información del vendedor
                $(document).on('click', '.btn-vendedor-info', function(e) {
                    e.preventDefault();
                    const idUsuario = $(this).data('id');
                    console.log('ID Usuario:', idUsuario);
                    const modal = $('#usuarioDetalleModal');

                    // Mostrar loading
                    modal.find('#loadingUsuario').show();
                    modal.find('#usuarioContent').hide();
                    modal.modal('show');

                    // Obtener datos del vendedor via AJAX
                    $.ajax({
                        url: 'UsuarioController.php?action=detalle&id=' + idUsuario,
                        type: 'GET',
                        success: function(response) {
                            console.log('Respuesta Success:', response);

                            if (response.success) {
                                const usuario = response.data.usuario;
                                const redes = response.data.redes;

                                // Actualizar información básica
                                $('#usuarioFoto').attr('src', URL_VIEWS + (usuario.foto_perfil || 'fotoproducto/user.png'));
                                $('#usuarioNombre').text(usuario.nombre + (usuario.apellido ? ' ' + usuario.apellido : ''));
                                $('#usuarioLogin').text('@' + usuario.login);
                                $('#usuarioFechaNacimiento').text(usuario.fecha_nacimiento);
                                const genero = usuario.genero;
                                const mapeoGenero = {
                                    'M': 'Masculino',
                                    'F': 'Femenino'
                                };

                                $('#usuarioGenero').text(mapeoGenero[genero] || 'No definido');
                                $('#usuarioEmail').text(usuario.email || 'No proporcionado');
                                $('#usuarioTelefono').text(usuario.telefono || 'No proporcionado');
                                $('#usuarioDireccion').text(usuario.direccion || 'No proporcionada');

                                // Función para WhatsApp
                                const telefono = usuario.telefono;
                                const telefonoLimpio = telefono ? telefono.replace(/\D/g, '') : null;
                                const enlaceWhatsApp = telefonoLimpio ? `https://wa.me/${telefonoLimpio}` : null;

                                const telefonoElemento = $('#usuarioTelefono');
                                if (enlaceWhatsApp) {
                                    telefonoElemento.html(`<a href="${enlaceWhatsApp}" target="_blank">${telefono} <i class="icon_link_alt"></i></a>`);
                                } else {
                                    telefonoElemento.text(telefono || 'No proporcionado');
                                }

                                // Actualizar redes sociales
                                let redesHtml = '';
                                if (response.data.redes && response.data.redes.length > 0) {
                                    const redesObjeto = response.data.redes[0];

                                    if (redesObjeto.facebook) {
                                        redesHtml += `<a href="${redesObjeto.facebook}" target="_blank" class="btn btn-sm btn-default">
                            <i class="fa fa-facebook"></i> Facebook
                        </a> `;
                                    }
                                    if (redesObjeto.instagram) {
                                        redesHtml += `<a href="${redesObjeto.instagram}" target="_blank" class="btn btn-sm btn-default">
                            <i class="fa fa-instagram"></i> Instagram
                        </a> `;
                                    }
                                    if (redesObjeto.linkedin) {
                                        redesHtml += `<a href="${redesObjeto.linkedin}" target="_blank" class="btn btn-sm btn-default">
                            <i class="fa fa-linkedin"></i> LinkedIn
                        </a> `;
                                    }
                                    if (redesObjeto.twitter) {
                                        redesHtml += `<a href="${redesObjeto.twitter}" target="_blank" class="btn btn-sm btn-default">
                            <i class="fa fa-twitter"></i> Twitter
                        </a> `;
                                    }

                                    if (redesHtml === '') {
                                        redesHtml = '<p class="text-muted">El vendedor no ha agregado redes sociales</p>';
                                    }
                                } else {
                                    redesHtml = '<p class="text-muted">El vendedor no ha agregado redes sociales</p>';
                                }
                                $('#usuarioRedes').html(redesHtml);

                                // Mostrar contenido
                                modal.find('#loadingUsuario').hide();
                                modal.find('#usuarioContent').show();
                            } else {
                                modal.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        ${response.message || 'Error al cargar la información del vendedor'}
                    </div>
                `);
                            }
                        },
                        error: function(xhr) {
                            console.log('Error AJAX:', xhr);
                            modal.find('.modal-body').html(`
                <div class="alert alert-danger">
                    Error en la conexión: ${xhr.statusText}
                </div>
            `);
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
                // En el script de HistorialView.php, modifica el formulario de reporte
                $('#formReportar').on('submit', function(e) {
                    e.preventDefault();

                    var $form = $(this);
                    var $submitBtn = $form.find('button[type="submit"]');
                    var originalBtnText = $submitBtn.html();

                    // Validación
                    var motivo = $form.find('textarea[name="motivo"]').val();
                    if (motivo.length < 10) {
                        alert('Por favor describe el problema con más detalle (mínimo 10 caracteres)');
                        return;
                    }

                    // Deshabilitar botón y mostrar carga
                    $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

                    // Enviar reporte
                    $.ajax({
                            url: 'HistorialController.php?action=reportar',
                            type: 'POST',
                            data: $form.serialize(),
                            dataType: 'json'
                        })
                        .done(function(response) {
                            console.log('Respuesta:', response);

                            if (response.success) {
                                alert(response.message);
                                $('#reportarModal').modal('hide');

                                // Actualizar interfaz
                                $('.btn-reportar[data-id="' + $('#idOrdenReportar').val() + '"]')
                                    .replaceWith('<span class="badge badge-info">Reportado</span>');
                            } else {
                                alert('Error: ' + response.message);
                            }
                        })
                        .fail(function(xhr, status, error) {
                            console.error('Error en AJAX:', status, error);

                            try {
                                // Intentar parsear la respuesta como JSON aunque haya fallado
                                var response = JSON.parse(xhr.responseText);
                                alert('Error: ' + (response.message || error));
                            } catch (e) {
                                // Si no es JSON válido, mostrar el error crudo
                                alert('Error de conexión: ' + error + '\nRespuesta del servidor: ' + xhr.responseText);
                            }
                        })
                        .always(function() {
                            // Restaurar botón
                            $submitBtn.prop('disabled', false).html(originalBtnText);
                        });
                });
            });
        </script>
    </section>
</body>

</html>