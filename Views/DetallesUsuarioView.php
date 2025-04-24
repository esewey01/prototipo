<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container" class="">
        <?php include("Header.php"); ?>
        <?php include("Menu.php"); ?>

        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-user"></i> Detalles del Usuario</h3>
                        <ol class="breadcrumb">
                            <li><i class="fa fa-home"></i><a href="principal.php">Inicio</a></li>
                            <li><i class="fa fa-users"></i><a href="UsuarioViewFinal.php">Usuarios</a></li>
                            <li><i class="fa fa-user"></i>Detalles</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="profile-pic text-center">
                                    <img src="<?= URL_VIEWS . ($usuario['foto_perfil'] ?? 'fotoproducto/user.png') ?>" 
                                         class="img-circle" width="150" height="150"
                                         onerror="this.src='<?= URL_VIEWS ?>fotoproducto/user.png'">
                                    <h4><?= htmlspecialchars($usuario['nombre'] ?? '') ?></h4>
                                    <span class="label label-<?= $tipoUsuario['estilo'] ?>">
                                        <i class="fa <?= $tipoUsuario['icono'] ?>"></i>
                                        <?= $usuario['nombre_rol'] ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Información Básica</div>
                            <div class="panel-body">
                                <p><strong>Login:</strong> <?= htmlspecialchars($usuario['login'] ?? '') ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                                <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? '') ?></p>
                                <p><strong>Dirección:</strong> <?= htmlspecialchars($usuario['direccion'] ?? '') ?></p>
                                <p><strong>Fecha Registro:</strong> <?= $usuario['fecha_registro']->format('d/m/Y') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">Actividad</div>
                            <div class="panel-body">
                                <!-- Aquí puedes agregar gráficos o estadísticas del usuario -->
                                <div class="alert alert-info">
                                    <strong>Último acceso:</strong> 
                                    <?= $usuario['ultimo_acceso'] ? $usuario['ultimo_acceso']->format('d/m/Y H:i:s') : 'Nunca' ?>
                                </div>
                                
                                <?php if ($usuario['nombre_rol'] == 'VENDEDOR'): ?>
                                <h4>Productos Publicados</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($productos as $producto): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($producto['nombre_producto']) ?></td>
                                                <td>$<?= number_format($producto['precio_venta'], 2) ?></td>
                                                <td><?= $producto['cantidad'] ?></td>
                                                <td>
                                                    <span class="label label-<?= $producto['estado'] == 'ACTIVO' ? 'success' : 'danger' ?>">
                                                        <?= $producto['estado'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Reportes</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Motivo</th>
                                                <th>Acción</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reportes as $reporte): ?>
                                            <tr>
                                                <td><?= $reporte['fecha_reporte']->format('d/m/Y') ?></td>
                                                <td><?= htmlspecialchars($reporte['motivo']) ?></td>
                                                <td><?= htmlspecialchars($reporte['accion_tomada']) ?></td>
                                                <td>
                                                    <span class="label label-<?= $reporte['estado'] == 'PENDIENTE' ? 'warning' : 'info' ?>">
                                                        <?= $reporte['estado'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        <?php include("LibraryJs.php"); ?>
    </section>
</body>
</html>