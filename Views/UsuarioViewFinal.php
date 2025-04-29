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
    </section>

    <!-- Menú Principal -->
    <?php include("Menu.php") ?>

    <!-- Contenido Principal -->
    <section id="main-content">
        <section class="wrapper">
            <!-- Pestañas para los tipos de usuario -->
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-users"></i> ADMINISTRACIÓN DE USUARIOS</h3>
                    <!--FUNCION DE ALERTA DE MENSAJES-->
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
                    <li><i class="fa fa-users"></i><a href="UsuariosController.php">Administrar Usuarios</a></li>
                    <li><i class="fa fa-edit"></i><a href="#addUser" title="" data-toggle="modal">Agregar Usuarios</a></li>
                        <!--?php include("MenuOpcionesConfiguracion.php"); ?-->
                    </ol>
                </div>
            </div>

            <!-- Pestañas -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#administradores" data-toggle="tab">Administradores</a></li>

                <li><a href="#vendedores" data-toggle="tab">Vendedores</a></li>
                <li><a href="#clientes" data-toggle="tab">Clientes</a></li>
            </ul>



            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <div class="panel-heading">
                    LISTA DE ADMINISTRADORES DEL SISTEMA
                    <div align="right">
                        <button href="#addUser" title="" data-placement="left" data-toggle="modal"
                            class="btn btn-primary tooltips" type="button"
                            data-original-title="Nuevo Usuario">
                            <span class="fa fa-plus"> </span>
                            AGREGAR NUEVO USUARIO
                        </button>
                    </div>
                </div>

                <!-- Tabla de Administradores -->
                <div class="tab-pane active" id="administradores">
                    <?php
                    $tipo_actual = 'administrador';
                    $usuarios = $administradores;

                    include("_partials/tabla_usuarios.php");
                    ?>
                </div>
                <!-- Tabla de Vendedores -->
                <div class="tab-pane" id="vendedores">
                    <?php
                    $tipo_actual = 'vendedor';
                    $usuarios = $vendedores;

                    include("_partials/tabla_usuarios.php");
                    ?>
                </div>

                <!-- Tabla de Clientes -->
                <div class="tab-pane" id="clientes">
                    <?php
                    $tipo_actual = 'cliente';
                    $usuarios = $clientes;

                    include("_partials/tabla_usuarios.php");
                    ?>
                </div>

            </div>

        </section>

    </section>


    <!-- Modal para Reportar Usuario -->
    <div class="modal fade" id="reportarUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="reportarUsuarioModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="RegistroReporteController.php" method="POST">
                    <input type="hidden" name="tipo_reporte" value="USUARIO">
                    <input type="hidden" name="id_usuario_reportado" id="reporte_id_usuario">
                    <input type="hidden" name="id_administrador" value="<?= $_SESSION['usuario']['id_usuario'] ?>">


                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="reportarUsuarioModalLabel">Reportar Usuario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Usuario a reportar:</label>
                            <p class="form-control-static" id="reporte_nombre_usuario"></p>
                        </div>
                        <div class="form-group">
                            <label>Tipo de usuario:</label>
                            <p class="form-control-static" id="reporte_tipo_usuario"></p>
                        </div>
                        <div class="form-group">
                            <label for="motivo">Motivo del reporte:</label>
                            <select class="form-control" name="motivo" required>
                                <option value="">Seleccione un motivo</option>
                                <option value="Comportamiento inapropiado">Comportamiento inapropiado</option>
                                <option value="Publicaciones inadecuadas">Publicaciones inadecuadas</option>
                                <option value="Incumplimiento de normas">Incumplimiento de normas</option>
                                <option value="Intento de fraude">Intento de fraude</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="accion_tomada">Acción a tomar:</label>
                            <select class="form-control" name="accion_tomada" required>
                                <option value="">Seleccione una acción</option>
                                <option value="Advertencia">Enviar advertencia</option>
                                <option value="Suspender cuenta">Suspender cuenta temporalmente</option>
                                <option value="Banear cuenta">Banear cuenta permanentemente</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comentarios">Comentarios adicionales:</label>
                            <textarea class="form-control" name="comentarios" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Enviar Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal para Ver Detalles de Usuario -->
    <div class="modal fade" id="detallesUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="detallesUsuarioModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="ActualizarUsuario.php" method="POST">

                    <input type="hidden" name="id_usuario" id="detalle_id_usuario">

                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title text-white" id="detallesUsuarioModalLabel">Detalles del Usuario</h4>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <img id="detalle_foto_perfil" src=""
                                    class="img-thumbnail" width="200" height="200"
                                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/user.png'">
                                <div class="mt-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                            id="usuario_verificado" name="verificado">
                                        <label class="form-check-label" for="usuario_verificado">
                                            <i class="fa fa-check-circle text-success"></i> Usuario Verificado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nombre:</label>
                                            <p class="form-control-static" id="detalle_nombre"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Apellido:</label>
                                            <p class="form-control-static" id="detalle_apellido"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <p class="form-control-static" id="detalle_email"></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Teléfono:</label>
                                            <p class="form-control-static" id="detalle_telefono"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Dirección:</label>
                                            <p class="form-control-static" id="detalle_direccion"></p>
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha Registro:</label>
                                            <p class="form-control-static" id="detalle_fecha_registro"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Rol:</label>
                                    <p class="form-control-static" id="detalle_rol"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal para Agregar Usuario -->
    <?php include("_partials/modal_add_user.php"); ?>

    <?php include("LibraryJs.php"); ?>

    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTables en todas las tablas
            $('.table-usuarios').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
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
                }
            });
        });

        // Función para cargar datos en el modal de reporte de usuario
        function cargarDatosReporteUsuario(idUsuario, nombreUsuario, tipoUsuario) {
            $('#reporte_id_usuario').val(idUsuario);
            $('#reporte_nombre_usuario').text(nombreUsuario);
            $('#reporte_tipo_usuario').text(tipoUsuario);
            $('#reportarUsuarioModal').modal('show');
        }

        function cargarDetallesUsuario(usuario) {
            // Convertir el objeto JSON si es necesario
            if (typeof usuario === 'string') {
                usuario = JSON.parse(usuario);
            }
            console.log('DATOS DEL USUARIO: ', usuario);


            $('#detalle_id_usuario').val(usuario.id_usuario);
            $('#detalle_foto_perfil').attr('src', '<?= URL_VIEWS ?>' + (usuario.foto_perfil || 'fotoproducto/user.png'));
            $('#detalle_nombre').text(usuario.nombre || 'No especificado');
            $('#detalle_apellido').text(usuario.apellido || 'No especificado');
            $('#detalle_email').text(usuario.email || 'No especificado');
            $('#detalle_telefono').text(usuario.telefono || 'No especificado');
            $('#detalle_direccion').text(usuario.direccion || 'No especificado');


            $('#detalle_fecha_registro').text(usuario.fecha_registro || 'No especificado')
            $('#detalle_rol').text(usuario.id_rol || 'No especificado');

            // Marcar checkbox si el usuario está verificado
            $('#usuario_verificado').prop('checked', usuario.verificado == 1);

            $('#detallesUsuarioModal').modal('show');
        }
    </script>
</body>

</html>