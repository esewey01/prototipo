<!DOCTYPE html>
<html lang="es">
<?php include("Head.php"); ?>



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

        <!--CONTENIDO PRINCIPAL-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-users"></i> COMPORTATE BIEN</h3>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-home"></i><a href="PrincipalCSontroller.php">Inicio</a>
                            </li>
                            <li>
                                <i class="fa fa-users"></i><a href="#">Algun error? Contacta con soporte técnico!</a>
                            </li>
                        </ol>
                    </div>
                </div>



                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <h2><i class="fa fa-exclamation-triangle text-warning"></i> Mis Reportes</h2>
                            <p class="lead">Aquí puedes ver los reportes que se han hecho sobre ti.</p>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                            <?php unset($_SESSION['error']);
                            endif; ?>

                            <?php if (empty($reportes)): ?>
                                <div class="alert alert-info">No tienes ninguna incidencia registrada.</div>
                            <?php else: ?>
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tipo de Reporte</th>
                                            <th>Motivo</th>
                                            <th>Comentarios</th>
                                            <th>Administrador</th>
                                            <th>Fecha</th>
                                            <th>Estado</th>
                                            <th>Acción Tomada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($reportes as $reporte): ?>
                                            <tr>
                                                <td><?= ucfirst(strtolower($reporte['tipo_reporte'])) ?></td>
                                                <td><?= htmlspecialchars($reporte['motivo']) ?></td>
                                                <td><?= !empty($reporte['comentarios']) ? htmlspecialchars($reporte['comentarios']) : 'Sin comentarios' ?></td>
                                                <td><?= htmlspecialchars($reporte['nombre_administrador']) ?></td>
                                                <td><?= $reporte['fecha_reporte']->format('d/m/Y H:i') ?></td>
                                                <td>
                                                    <?php if ($reporte['estado'] === 'PENDIENTE'): ?>
                                                        <span class="badge badge-warning"><?= $reporte['estado'] ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-success"><?= $reporte['estado'] ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($reporte['accion_tomada']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

        </section>
        <?php include("LibraryJS.php"); ?>

</body>

</html>