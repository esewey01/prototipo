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
                                <?php if (!empty($clientes)): ?>
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
                                                    <td><?= htmlspecialchars($cliente['id_cliente']) ?></td>
                                                    <td><?= htmlspecialchars($cliente['nombre_cliente'] . ' ' . $cliente['apellido_cliente']) ?></td>
                                                    <td><?= htmlspecialchars($cliente['email_cliente']) ?></td>
                                                    <td><?= htmlspecialchars($cliente['telefono_cliente']) ?></td>
                                                    <td>

                                                        <a href="#" class="btn btn-info btn-sm label-danger" data-usuario-id="<?= $cliente['id_cliente'] ?>">
                                                            <i class="fa fa-eye"></i> Ver Detalle
                                                        </a>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <div class="alert alert-info">Aún no tienes clientes registrados.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>


        <?php include('../Views/UsuarioDetalleModal.php'); ?>
        <?php include("LibraryJs.php"); ?>
        <script>
            $(document).ready(function() {
                // CODIGO PARA CARGA LOS DETALLES DEL USUARIO EN EL MODAL
                $(document).on('click', '.label-danger', function(e) {
                    e.preventDefault();
                    const idUsuario = $(this).data('usuario-id');
                    console.log('ID Usuario:', idUsuario); // Para debug
                    const modal = $('#usuarioDetalleModal');
                    // Mostrar loading
                    modal.find('#loadingUsuario').show();
                    modal.find('#usuarioContent').hide();
                    modal.modal('show');
                    // Obtener datos del usuario via AJAX
                    $.ajax({
                        url: 'UsuarioController.php?action=detalle&id=' + idUsuario,
                        type: 'GET',
                        success: function(response) {
                            console.log('Respuesta Success:', response);
                            if (response.success) {
                                const usuario = response.data.usuario;
                                const redes = response.data.redes;

                                // Actualizar información básica
                                $('#usuarioFoto').attr('src', '<?= URL_VIEWS ?>' + (usuario.foto_perfil || 'fotoproducto/user.png'));
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

                                // Manejo de WhatsApp
                                const telefono = usuario.telefono;
                                const telefonoLimpio = telefono ? telefono.replace(/\D/g, '') : null;
                                const enlaceWhatsApp = telefonoLimpio ? `https://wa.me/${telefonoLimpio}` : null;
                                const telefonoElemento = $('#usuarioTelefono');

                                if (enlaceWhatsApp) {
                                    telefonoElemento.html(`<a href="${enlaceWhatsApp}" target="_blank">${telefono} <i class="icon_link_alt"></i></a>`);
                                } else {
                                    telefonoElemento.text(telefono || 'No proporcionado');
                                }

                                // Redes sociales
                                let redesHtml = '';
                                if (redes && redes.length > 0) {
                                    const redesObjeto = redes[0];
                                    if (redesObjeto.facebook) {
                                        redesHtml += `<a href="${redesObjeto.facebook}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-facebook"></i> Facebook</a>`;
                                    }
                                    if (redesObjeto.instagram) {
                                        redesHtml += `<a href="${redesObjeto.instagram}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-instagram"></i> Instagram</a>`;
                                    }
                                    if (redesObjeto.linkedin) {
                                        redesHtml += `<a href="${redesObjeto.linkedin}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-linkedin"></i> LinkedIn</a>`;
                                    }
                                    if (redesObjeto.twitter) {
                                        redesHtml += `<a href="${redesObjeto.twitter}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-twitter"></i> Twitter</a>`;
                                    }
                                } else {
                                    redesHtml = '<p class="text-muted">El usuario no ha agregado redes sociales</p>';
                                }

                                $('#usuarioRedes').html(redesHtml);

                                // Mostrar contenido
                                $('#loadingUsuario').hide();
                                $('#usuarioContent').show();

                                // Asignar ID del usuario al campo oculto del formulario de reporte
                                $('#id_usuario_reportado').val(idUsuario);
                                $('#id_administrador').val(<?= $_SESSION['usuario']['id_usuario'] ?? 'null' ?>);

                            } else {
                                modal.find('.modal-body').html(`
                        <div class="alert alert-danger">
                            ${response.message || 'Error al cargar la información del usuario'}
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


                //MANEJO DEL FORMULARIO DEL REPORTE
                $(document).on('click', '.label-danger', function(e) {
                    e.preventDefault();
                    const idUsuario = $(this).data('usuario-id');
                    console.log('ID Usuario:', idUsuario);
                    $('#id_usuario_reportado').val(idUsuario);
                    $('#id_administrador').val(<?= $_SESSION['usuario']['id_usuario'] ?? 'null' ?>); // Tu propio ID como administrador
                });

                // Enviar reporte
                $('#enviarReporteBtn').click(function() {
                    const $btn = $(this);
                    const idUsuario = $('#id_usuario_reportado').val();
                    console.log('ID Usuario Reportado:', idUsuario); // Para debug
                    const idAdmin = $('#id_administrador').val();
                    console.log('ID Administrador:', idAdmin); // Para debug
                    const motivo = $('#motivo').val();

                    if (!idUsuario || !idAdmin) {
                        showMessage('Error: No se pudo identificar al usuario o administrador', 'error');
                        return;
                    }

                    if (!motivo) {
                        showMessage('Por favor complete el motivo del reporte', 'error');
                        return;
                    }

                    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Enviando...');

                    $.ajax({
                        url: '../Controller/ReporteController.php?action=reportarUsuario',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            id_usuario_reportado: idUsuario,
                            id_administrador: idAdmin,
                            motivo: motivo,
                            comentarios: $('#comentarios').val(),
                            tipo_reporte: 'USUARIO' // Cambiado para indicar que es un cliente
                        },
                        success: function(response) {
                            $('#reportarUsuarioModal').modal('hide');
                            $('#reportarUsuarioForm')[0].reset();

                            if (response.success) {
                                showMessage('Reporte enviado correctamente', 'success');
                            } else {
                                showMessage(response.message || 'Error al enviar el reporte', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            showMessage('Error de conexión: ' + error, 'error');
                        },
                        complete: function() {
                            $btn.prop('disabled', false).html('Enviar Reporte');
                        }
                    });
                });

                function showMessage(message, type) {
                    if (typeof toastr !== 'undefined') {
                        toastr[type](message);
                    } else {
                        alert(type.toUpperCase() + ': ' + message);
                    }
                }
            });
        </script>

    </section>