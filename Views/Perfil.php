<?php
// Inicio de la vista Perfil.php

    // Iniciar sesión si no está iniciada
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener datos del controlador
$viewData = isset($viewData) ? $viewData : [];

// Extraer variables para facilitar el acceso
$user_data = $viewData['user_data'] ?? [];
$social_data = $viewData['social_data'] ?? ['facebook' => '', 'instagram' => '', 'linkedin' => '', 'twitter' => ''];
$categories = $viewData['categories'] ?? [];
$solicitudes = $viewData['solicitudes'] ?? [];
$urlViews = $viewData['urlViews'] ?? '';
$alerta = $viewData['alerta'] ?? '';
$mensaje = $viewData['mensaje'] ?? '';

// Datos de sesión
$id_usuario = $_SESSION['usuario']['id_usuario'];
$id_rol = $_SESSION['usuario']['rol']['id_rol'];
?>

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
                <div class="row ">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-user"></i> MI PERFIL</h3>

                        <!--FUNCION DE ALERTA DE MENSAJES-->
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <strong><?= $_SESSION['ultima_actualizacion_perfil'][$id_usuario] ?? '' ?></strong>
                                <strong><?= $_SESSION['mensaje'] ?></strong>
                            </div>
                            <?php
                            unset($_SESSION['mensaje']);
                            unset($_SESSION['alerta']);
                        endif; ?>

                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <strong>¡Éxito!</strong> Los cambios se guardaron correctamente.
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Error:</strong> <?= htmlspecialchars($_GET['error']) ?>
                            </div>
                        <?php endif; ?>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                            <li><i class="fa fa-user"></i>Perfil</li>
                        </ol>
                    </div>
                </div>

                <!-- Pestañas del perfil -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#info" data-toggle="tab">Información</a></li>
                                    <li><a href="#password" data-toggle="tab">Contraseña</a></li>
                                    <li><a href="#social" data-toggle="tab">Redes Sociales</a></li>
                                    <?php if ($id_rol == 3 || $id_rol == 2): ?>
                                        <li><a href="#seller" data-toggle="tab">Ser Vendedor</a></li>
                                    <?php endif; ?>
                                </ul>

                                <div class="tab-content">
                                    <!-- Información Básica -->
                                    <div class="tab-pane active" id="info">
                                        <form method="POST" enctype="multipart/form-data" class="form-horizontal">
                                            <input type="hidden" name="action" value="update_profile">

                                            <div class="form-group text-center">
                                                <div class="col-sm-12">
                                                    <img src="<?= URL_VIEWS . ($user_data['foto_perfil'] ?? 'fotoproducto/user.png') ?>"
                                                        alt="Usuario"
                                                        class="img-circle profile-pic"
                                                        id="profileImage"
                                                        style="width: 150px; height: 150px;"
                                                        onerror="this.onerror=null; this.src='<?= URL_VIEWS . 'fotoproducto/user.png' ?>'">
                                                    <div class="mt-2">
                                                        <label class="btn btn-primary">
                                                            Cambiar foto <input type="file" name="foto" id="fotoInput" style="display: none;" onchange="previewImage(this)">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Login</label>
                                                <div class="col-sm-10">
                                                    <p class="form-control-static"><?= htmlspecialchars($user_data['login'] ?? '') ?></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nombre</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($user_data['nombre'] ?? '') ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Apellido</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($user_data['apellido'] ?? '') ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Teléfono</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($user_data['telefono'] ?? '') ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Dirección</label>
                                                <div class="col-sm-10">
                                                    <textarea name="direccion" class="form-control"><?= htmlspecialchars($user_data['direccion'] ?? '') ?></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Fecha Nacimiento</label>
                                                <div class="col-sm-10">
                                                    <input type="date" name="fecha_nacimiento" class="form-control"
                                                        value="<?= $user_data['fecha_nacimiento'] ?? '' ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Género</label>
                                                <div class="col-sm-10">
                                                    <select name="genero" class="form-control">
                                                        <option value="">Seleccionar...</option>
                                                        <option value="M" <?= ($user_data['genero'] ?? '') == 'M' ? 'selected' : '' ?>>Masculino</option>
                                                        <option value="F" <?= ($user_data['genero'] ?? '') == 'F' ? 'selected' : '' ?>>Femenino</option>
                                                        <option value="O" <?= ($user_data['genero'] ?? '') == 'O' ? 'selected' : '' ?>>Otro</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Campos de solo lectura -->
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Fecha Registro</label>
                                                <div class="col-sm-10">
                                                    <p class="form-control-static"><?= $user_data['fecha_registro'] ?? '' ?></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Último Acceso</label>
                                                <div class="col-sm-10">
                                                    <p class="form-control-static"><?= $user_data['ultimo_acceso'] ?? '' ?></p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Estado</label>
                                                <div class="col-sm-10">
                                                    <p class="form-control-static">
                                                        <?= ($user_data['activo'] ?? 0) ? '<span class="label label-success">Activo</span>' : '<span class="label label-danger">Inactivo</span>' ?>
                                                        <?= ($user_data['verificado'] ?? 0) ? '<span class="label label-info">Verificado</span>' : '<span class="label label-warning">No verificado</span>' ?>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Cambio de Contraseña -->
                                    <div class="tab-pane" id="password">
                                        <form method="POST" class="form-horizontal" style="margin-top: 20px;">
                                            <input type="hidden" name="action" value="update_password">

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Contraseña actual</label>
                                                <div class="col-sm-9">
                                                    <input type="password" name="current_password" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Nueva contraseña</label>
                                                <div class="col-sm-9">
                                                    <input type="password" name="new_password" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Confirmar contraseña</label>
                                                <div class="col-sm-9">
                                                    <input type="password" name="confirm_password" class="form-control" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Redes Sociales -->
                                    <div class="tab-pane" id="social">
                                        <form method="POST" class="form-horizontal" style="margin-top: 20px;">
                                            <input type="hidden" name="action" value="update_social">

                                            <div class="form-group">
                                                
                                                <label class="col-sm-2 control-label"><i class="fa fa-facebook"></i> Facebook</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="facebook" class="form-control" value="<?= htmlspecialchars($social_data['facebook'])?? 'Url de tu perfil'?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><i class="fa fa-instagram"></i> Instagram</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="instagram" class="form-control" value="<?= htmlspecialchars($social_data['instagram']) ?>" placeholder="URL de tu perfil">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><i class="social_linkedin"></i> LinkedIn</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($social_data['linkedin']) ?>" placeholder="URL de tu perfil">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label"><i class="fa fa-twitter"></i> Twitter</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="twitter" class="form-control" value="<?= htmlspecialchars($social_data['twitter']) ?>" placeholder="URL de tu perfil">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Guardar redes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Solicitud para ser Vendedor (solo para clientes) -->
                                    <?php if ($id_rol == 3 || $id_rol == 2): ?>
                                        <div class="tab-pane" id="seller">
                                            <form method="POST" class="form-horizontal" style="margin-top: 20px;">
                                                <input type="hidden" name="action" value="seller_request">

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Categoría de productos</label>
                                                    <div class="col-sm-9">
                                                        <select name="categoria" class="form-control" required>
                                                            <option value="">Seleccione una categoría</option>
                                                            <?php foreach ($categories as $cat): ?>
                                                                <option value="<?= $cat['id_categoria'] ?>"><?= htmlspecialchars($cat['nombre_categoria']) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">Descripción</label>
                                                    <div class="col-sm-9">
                                                        <textarea name="descripcion" class="form-control" rows="5" required placeholder="Describe los productos que planeas vender"></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-9">
                                                        <button type="submit" class="btn btn-success">Enviar solicitud</button>
                                                    </div>
                                                </div>

                                                <!-- SOLICITUD DE EVENTO -->
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">ESTADO DE SOLICITUD</label>
                                                    <div class="col-sm-10">
                                                        <?php if ($id_rol == 2): ?>
                                                            <p class="form-control-static text-success">
                                                                <i class="fa fa-check"></i> Eres vendedor
                                                            </p>
                                                        <?php elseif (!empty($solicitudes)): ?>
                                                            <?php $ultimaSolicitud = $solicitudes[0]; ?>
                                                            <p class="form-control-static">
                                                                <strong>ESTADO:</strong>
                                                                <span class="label label-<?=
                                                                    $ultimaSolicitud['estado'] == 'APROBADA' ? 'success' : 
                                                                    ($ultimaSolicitud['estado'] == 'RECHAZADA' ? 'danger' : 'warning') ?>">
                                                                    <?= $ultimaSolicitud['estado'] ?>
                                                                </span><br>
                                                                <small>Categoría: <?= $ultimaSolicitud['categoria'] ?? '' ?></small><br>
                                                                <small>Solicitudes: <?= count($solicitudes) ?></small>
                                                            </p>
                                                        <?php else: ?>
                                                            <p class="form-control-static text-muted">No has solicitado ser vendedor</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>

    <?php include("LibraryJs.php"); ?>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            // Inicializar pestañas
            $('.nav-tabs a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Mostrar mensajes de error/exito por 5 segundos
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>