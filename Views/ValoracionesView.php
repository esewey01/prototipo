<!DOCTYPE html>
<html lang="en">
<?php
include('Head.php');
?>
<body>
<section id="container" class="">
    <header class="header dark-bg">
        <div class="toggle-nav">
            <div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i
                        class="icon_menu"></i></div>
        </div>
        <?PHP include("Logo.php") ?>
        <div class="nav search-row" id="top_menu">
            <!--  search form start -->
            <ul class="nav top-menu">
                <li>
                    <form class="navbar-form">
                        <!--                              <input class="form-control" placeholder="Search" type="text">-->
                    </form>
                </li>
            </ul>
            <!--  search form end -->
        </div>
        <?PHP include("DropDown.php"); ?>
    </header>
    <?PHP include("Menu.php") ?>

</section>


<section id="main-content">



  <section class="wrapper">
        <!--overview start-->
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"><i class="fa fa-laptop"></i> PRINCIPAL</h3>
                <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert <?= $_SESSION['alerta'] ?>"><?= $_SESSION['mensaje'] ?></div>
            <?php unset($_SESSION['mensaje']);
                unset($_SESSION['alerta']);
            endif; ?>

                <ol class="breadcrumb">
                    <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                    <li><i class="fa fa-flag"></i><a href="ReportesController.php">Reportes</a></li>
                </ol>
            </div>
        </div>




        <div class="container mt-5">
            <h2><i class="fa fa-star text-warning"></i> Valoraciones de <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></h2>

            

            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>Promedio: <?= $promedio['promedio'] ?>/5 (<?= $promedio['total'] ?> valoraciones)</h4>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalValorar">Agregar Valoración</button>
                </div>
            </div>

            <!-- Lista de valoraciones -->
            <div class="list-group">
                <?php foreach ($valoraciones as $val): ?>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><?= htmlspecialchars($val['nombre_cliente']) ?></h5>
                            <small><?= date('d/m/Y H:i', strtotime($val['fecha_valoracion'])) ?></small>
                        </div>
                        <p class="mb-1"><?= str_repeat('<i class="fa fa-star text-warning"></i>', $val['calificacion']) ?></p>
                        <p class="mb-1"><?= htmlspecialchars($val['comentario']) ?></p>
                        <small>Producto: <?= htmlspecialchars($val['nombre_producto']) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Modal para valorar -->
            <div class="modal fade" id="modalValorar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="ValoracionesController.php" method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Valorar a <?= htmlspecialchars($vendedor['nombre']) ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id_vendedor" value="<?= $vendedor['id_usuario'] ?>">
                                <input type="hidden" name="id_producto" value="<?= $vendedor['id_producto'] ?? 1 ?>"> <!-- Cambiar esto dinámicamente -->

                                <div class="form-group">
                                    <label for="calificacion">Calificación (1 a 5)</label>
                                    <select name="calificacion" id="calificacion" class="form-control" required>
                                        <option value="">Selecciona</option>
                                        <option value="1">1 Estrella</option>
                                        <option value="2">2 Estrellas</option>
                                        <option value="3">3 Estrellas</option>
                                        <option value="4">4 Estrellas</option>
                                        <option value="5">5 Estrellas</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="comentario">Comentario (opcional)</label>
                                    <textarea name="comentario" id="comentario" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Enviar Valoración</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?PHP include("LibraryJs.php"); ?>
</body>

</html>