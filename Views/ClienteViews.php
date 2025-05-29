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

        <?php include("LibraryJs.php"); ?>
        <?php include("../Views/UsuarioDetalleModal.php"); ?>
        <script>
            function verDetalleCliente(idCliente) {
                console.log('ID Cliente:', idCliente);
                const modal = $('#usuarioDetalleModal');
                var URL_VIEWS = '<?= URL_VIEWS ?>';

                // Mostrar loading
                modal.find('#loadingCliente').show();
                modal.find('#clienteContent').hide();
                modal.modal('show');

                // Obtener datos del cliente vía AJAX
                $.ajax({
                    url: 'UsuarioController.php?action=detalle&id=' + idCliente,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Respuesta Success:', response);

                        if (response.success) {
                            const usuario = response.data.usuario;
                            const redes = response.data.redes;

                           //información básica
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

                            //FUNCION PARA WHASTAPP
                            const telefono = usuario.telefono;
                            const telefonoLimpio = telefono ? telefono.replace(/\D/g, '') : null; // Elimina caracteres no numéricos
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
                                const redesObjeto = response.data.redes[0]; // Accedemos al primer (y único) objeto dentro del array

                                if (redesObjeto.facebook) {
                                    redesHtml += `
                                <a href="${redesObjeto.facebook}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-facebook"></i> Facebook
                                </a> `;
                                }
                                if (redesObjeto.instagram) {
                                    redesHtml += `
                                <a href="${redesObjeto.instagram}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-instagram"></i> Instagram
                                </a> `;
                                }
                                if (redesObjeto.linkedin) {
                                    redesHtml += `
                                <a href="${redesObjeto.linkedin}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-linkedin"></i> LinkedIn
                                </a> `;
                                }
                                if (redesObjeto.twitter) {
                                    redesHtml += `
                                <a href="${redesObjeto.twitter}" target="_blank" class="btn btn-sm btn-default">
                                    <i class="fa fa-twitter"></i> Twitter
                                </a> `;
                                }

                                if (redesHtml === '') {
                                    redesHtml = '<p class="text-muted">El usuario no ha agregado redes sociales</p>';
                                }
                            } else {
                                redesHtml = '<p class="text-muted">El usuario no ha agregado redes sociales</p>';
                            }
                            $('#usuarioRedes').html(redesHtml);

                            // Mostrar contenido
                            $('#loadingUsuario').hide();
                            $('#usuarioContent').show();
                        } else {
                            modal.find('.modal-body').html(`
                    <div class="alert alert-danger">
                        ${response.message || 'Error al cargar la información del cliente'}
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
            }

            $(document).ready(function() {
                $('#clienteDetalleModal').on('show.bs.modal', function() {
                    $('#loadingCliente').show();
                    $('#clienteContent').hide();
                });
            });
        </script>
    </section>
</body>
</html>