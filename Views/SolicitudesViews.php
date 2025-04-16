<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container">

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

            <section id="main-content">
                <section class="wrapper">
                    <h3><i class="fa fa-users"></i> Solicitudes de Vendedores</h3>

                    <?php if (isset($_SESSION['mensaje_exito'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['mensaje_exito'] ?></div>
                        <?php unset($_SESSION['mensaje_exito']); ?>
                    <?php endif; ?>

                    <div class="row mt">
                        <div class="col-md-12">
                            <div class="content-panel">
                                <table class="table table-striped table-advance table-hover">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Boleta</th>
                                            <th>Categoría</th>
                                            <th>Descripción</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($solicitudes as $solicitud): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($solicitud['nombre']) ?></td>
                                                <td><?= htmlspecialchars($solicitud['login'])  ?></td>
                                                <td><?= htmlspecialchars($solicitud['categoria']) ?></td>
                                                <td><?= htmlspecialchars($solicitud['descripcion']) ?></td>

                                                <td><?= $solicitud['fecha_formateada'] ?></td>
                                                <td>
                                                    <span class="label label-<?=
                                                                                $solicitud['estado'] == 'APROBADA' ? 'success' : ($solicitud['estado'] == 'RECHAZADA' ? 'danger' : 'warning') ?>">
                                                        <?= $solicitud['estado'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($solicitud['estado'] == 'PENDIENTE'): ?>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="action" value="aprobar">
                                                            <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>">
                                                            <button type="submit" class="btn btn-success btn-xs">Aprobar</button>
                                                        </form>
                                                        <form method="POST" style="display:inline;">
                                                            <input type="hidden" name="action" value="rechazar">
                                                            <input type="hidden" name="id_solicitud" value="<?= $solicitud['id_solicitud'] ?>">
                                                            <button type="submit" class="btn btn-danger btn-xs">Rechazar</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </section>
        </section>

        <?php include('LibraryJs.php'); ?>
</body>

</html>