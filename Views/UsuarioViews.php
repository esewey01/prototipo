<!DOCTYPE html>
<html lang="es">

    <?php include('Head.php'); ?>

<!--------------------------------ENCABEZADOO ------------------>

<body>
    <section id="container" clas="">
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
                        <h3 class="page-header"><i class="fa fa-laptp"></i> ADMINISTRACIÓN DE USUARIOS</h3>
                        <div class="<?PHP echo $alerta; ?>" role="alert">´
                            <strong><?PHP echo $mensaje; ?></strong>
                            <!-- Breadcrumb/Menú de Opciones -->
                            <ol class="breadcrumb">
                                <?php include("MenuOpcionesConfiguracion.php"); ?>
                            </ol>
                        </div>
                    </div>

                    <!-- Panel de Usuarios -->
                    <div class="row">
                        <div class="col-lg-12">
                            <section class="panel">
                                <header class="panel-heading">Lista de Usuarios del Sistema</header>
                                <header class="panel-heading">
                                    <div class="panel-body">
                                        <!-- Botón Agregar -->
                                        <div align="right">
                                            <button href="#addUser" title="" data-placement="left" data-toggle="modal"
                                                class="btn btn-primary tooltips" type="button"
                                                data-original-title="Nuevo Usuario">
                                                <span class="fa fa-plus"> </span>
                                                AGREGAR NUEVO USUARIO
                                            </button>
                                        </div>

                                        <!--FUNCION AGREGAR USUARIO-->


                                        <div id="addUser" class="modal fade" tabindex="-1" role="dialog"
                                            aria-labelledby="myModalLabel" aria-hidden="true">
                                            <form class="form-validate form-horizontal" name="form2" action="Registros.php"
                                                method="POST" enctype="multipart/form-data">
                                                <input name="usuarioLogin" value="<?PHP echo $usuario; ?>" type="hidden">
                                                <input name="passwordLogin" value="<?PHP echo $password; ?>" type="hidden">

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true"> x
                                                            </button>
                                                            <h3 id=myModalLabel align="center"> Registrar Nuevo Usuario </h3>
                                                        </div>

                                                        <div class="modal-body">

                                                            <section class="panel" class="col-lg-6">
                                                                <div>
                                                                    <strong>
                                                                        Agregar Imagem del Usuario
                                                                    </strong>
                                                                </div>
                                                                <?php
                                                                include("UploadViewImageCreate.php");
                                                                ?>
                                                            </section>


                                                            <label for="nombre" class="control-label col-lg-2">
                                                                Nombre :
                                                            </label>

                                                            <div class="col-lg-10">
                                                                <input class="form-control input-lg m-bot15" id="nombre"
                                                                    name="nombre" minlength="5" type="text" required>
                                                            </div>
                                                            <br><br>

                                                            <label for="tipo" class="control-label col-lg-2">
                                                                Tipo :
                                                            </label>
                                                            <div class="col-lg-10">
                                                                <select class="form-control input-lg m-bot15" name="tipo">
                                                                    <option value="ADMINISTRADOR"> ADMINISTRADOR</option>
                                                                    <option value="VENTAS"> VENTAS</option>
                                                                </select>

                                                            </div>
                                                            <br><br>
                                                            <label for="login" class="control-label col-lg-2">
                                                                Login :
                                                            </label>
                                                            <div class="col-lg-10">
                                                                <input class="form-control input-lg m-bot15" id="login"
                                                                    name="login" minlength="5" type="text" required>
                                                            </div>
                                                            <br><br>
                                                            <label for="password" class="control-label col-lg-2">
                                                                Password:
                                                            </label>
                                                            <div class="col-lg-10">
                                                                <input class="form-control input-lg m-bot15" id="password"
                                                                    name="password" minlength="5" type="text" required>
                                                            </div>
                                                            <br><br>




                                                        </div>

                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" data-dismiss="modal"
                                                                aria-hidden="true"><strong> Cerrar</strong>
                                                            </button>
                                                            <button name="nuevo_usuario" type="submit" class="btn btn-primary">
                                                                <strong> Registrar</strong>
                                                            </button>
                                                        </div>

                                                    </div>



                                                </div>

                                            </form>



                                        </div>

                                    </div>

                                </header>



                                <!-- TABLA DE USUARIOS -->
                                <div class="panel-body">
                                    <div class="table-dataTable_wrapper">
                                        <table class="table table-striped table-advance table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th><i class="icon_image"></i> Foto</th>
                                                    <th><i class="icon_profile"></i> Nombre</th>
                                                    <th><i class="icon_folder"></i> Tipo</th>                   
                                                    <th><i class="icon_number"></i> Telefono</th>
                                                    <th><i class="icon_id"></i> Boleta</th>
                                                    <!--th><i class="icon_key"></i> Contraseña</th-->
                                                    <th><i class="icon_cog"></i> Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($allUsuarios)): ?>
                                                    <?php foreach ($allUsuarios as $user): ?>
                                                        <tr>

                                                            <!--FOTO USUARIO-->
                                                            <td>
                                                                <img src="<?= URL_VIEWS . $user['foto'] ?>"
                                                                    width="40" height="40"
                                                                    class="img-circle"
                                                                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/user.png'">
                                                            </td>

                                                            <!--NOMBRE -->
                                                            <td>
                                                                <?= htmlspecialchars($user['nombre']) ?>
                                                            </td>

                                                            <!--TIPO DE USUARIO -->
                                                            <td>
                                                                <span class="label label-<?= $user['id_tipo'] == 'ADMINISTRADOR' ? 'danger' : 'info' ?>">
                                                                    <?= htmlspecialchars($user['id_tipo']) ?>
                                                                </span>
                                                            </td>

                                                            <td>
                                                                <?= htmlspecialchars($user['telefono']) ?>
                                                            </td>

                                                            <!--CREDENCIAL DE ENTRADA -->
                                                            <td>
                                                                <?= htmlspecialchars($user['login']) ?>
                                                            </td>

                                                            <!--CONTRASENA >
                                                            <td>
                                                                <--?= htmlspecialchars($user['password']) ?>
                                                            </td-->

                                                            <!--BOTONES -->
                                                            <td>
                                                                <a href="#a<?php echo $datosUsuarios[0]; ?>" role="button" class="btn btn-success" data-toggle="modal">
                                                                    <i class="icon_check_alt2"></i> </a>
                                                                <a href="Registros.php?idborrar=<?PHP echo $datosUsuarios[0]; ?>&usuarioLogin=<?PHP echo $usuario; ?>&passwordLogin=<?PHP echo $password; ?>"
                                                                    role="button" class="btn btn-danger"> <i class="icon_close_alt2"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No hay usuarios registrados</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
            </section>
            </div>
            </div>
        </section>
    </section>
    </section>

    <!-- Modal para Agregar Usuario -->
    <div id="addUser" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <!-- Contenido del modal aquí -->
    </div>

    <!-- Scripts -->
    <?php include("LibraryJs.php"); ?>
</body>

</html>