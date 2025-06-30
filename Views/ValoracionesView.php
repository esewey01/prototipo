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
                    <h3 class="page-header"><i class="fa fa-laptop"></i> Mis valoraciones</h3>
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?>"><?= $_SESSION['mensaje'] ?></div>
                    <?php unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>

                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Pagina de Inicio</a></li>
                        <li><i class="fa fa-flag"></i><a href="ReportesController.php">Mis Valoraciones</a></li>
                    </ol>
                </div>
            </div>





            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-boy">
                        </div>
                        <!-- Pestañas para productos y vendedores -->
                        <ul class="nav nav-tabs" id="valoracionesTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="productos-tab" data-toggle="tab" href="#productos" role="tab" aria-controls="productos" aria-selected="true">
                                    <i class="icon_cart_alt"></i> Productos Valorados
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="vendedores-tab" data-toggle="tab" href="#vendedores" role="tab" aria-controls="vendedores" aria-selected="false">
                                    <i class="icon_profile"></i> Vendedores Valorados
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="valoracionesTabContent">
                            <!-- Tab de Productos -->
                            <div class="tab-pane fade show active" id="productos" role="tabpanel" aria-labelledby="productos-tab">
                                <h4 class="mt-4">Productos Comprados</h4>

                                <!-- Productos Pagados -->
                                <div class="card mt-4">
                                    <div class="card-header bg-success text-white">
                                        <h4>Productos Pagados - Listos para valorar</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($productosPagados)): ?>
                                            <p>No tienes productos pagados para valorar.</p>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($productosPagados as $producto): ?>
                                                    <div class="card mb-3">
                                                        <div class="row no-gutters">
                                                            <div class="col-md-2">
                                                                <img src= "../Views/<?= htmlspecialchars($producto['imagen']) ?>" class="img-fluid" alt="...">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card-body">
                                                                    <h5 class="card-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                                                    <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                                                    <?php if ($conexion->yaValoroProducto($_SESSION['usuario']['id_usuario'], $producto['id_producto'])): ?>
                                                                        <p><em>Ya has valorado este producto</em></p>
                                                                    <?php else: ?>
                                                                        <?php if (!empty($producto['id_producto']) && is_numeric($producto['id_producto'])): ?>
                                                                            <button class="btn btn-primary mt-3 btn-valorar-producto"
                                                                                data-producto-id="<?= $producto['id_producto'] ?>"
                                                                                data-producto-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                                                Valorar Producto
                                                                            </button>
                                                                        <?php else: ?>
                                                                            <small class="text-danger">ID inválido</small>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Productos No Pagados -->
                                <div class="card mt-4">
                                    <div class="card-header bg-warning text-dark">
                                        <h4>Productos Pendientes de Pago</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if (empty($productosNoPagados)): ?>
                                            <p>No tienes productos pendientes de pago.</p>
                                        <?php else: ?>
                                            <div class="alert alert-info">
                                                Podrás valorar estos productos una vez que hayan sido marcados como PAGADOS
                                            </div>
                                            <div class="row">
                                                <?php foreach ($productosNoPagados as $producto): ?>
                                                    <div class="col-md-4 mb-4">
                                                        <div class="card h-100">
                                                            <img src="../Views/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                            <div class="card-body">
                                                                <h5 class="card-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                                                <p class="card-text">Vendedor: <?= htmlspecialchars($producto['nombre_vendedor']) ?></p>
                                                                <p class="card-text">Estado: <?= htmlspecialchars($producto['estado_orden']) ?></p>
                                                                <button class="btn btn-secondary" disabled>Disponible después del pago</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab de Vendedores -->
                            <div class="tab-pane fade" id="vendedores" role="tabpanel">
                                <h3 class="mt-4">Vendedores</h3>

                                <?php if (empty($vendedores)): ?>
                                    <div class="alert alert-info">
                                        No tienes vendedores para valorar. Compra productos para poder valorar a los vendedores.
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($vendedores as $vendedor): ?>
                                            <div class="col-md-6 mb-4">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../<?= htmlspecialchars($vendedor['foto_perfil']) ?>" class="rounded-circle mr-3" width="80" height="80" alt="<?= htmlspecialchars($vendedor['nombre']) ?>">
                                                            <div>
                                                                <h5><?= htmlspecialchars($vendedor['nombre']) ?></h5>
                                                                <div class="mb-2">
                                                                    <?= str_repeat('<i class="fa fa-star text-warning"></i>', round($vendedor['promedio']['promedio'])) ?>
                                                                    <span class="ml-2">(<?= $vendedor['promedio']['promedio'] ?>/5 - <?= $vendedor['promedio']['total'] ?> valoraciones)</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if ($conexion->yaValoroVendedor($id_usuario_actual, $vendedor['id_usuario'])): ?>
                                                            <button class="btn btn-secondary mt-3" disabled>Ya valoraste a este vendedor</button>
                                                        <?php else: ?>
                                                            <button class="btn btn-primary mt-3 btn-valorar-vendedor"
                                                                data-vendedor-id="<?= $vendedor['id_usuario'] ?>"
                                                                data-vendedor-nombre="<?= htmlspecialchars($vendedor['nombre']) ?>">
                                                                Valorar Vendedor
                                                            </button>
                                                        <?php endif; ?>

                                                        <!-- Valoraciones existentes -->
                                                        <?php if (!empty($vendedor['valoraciones'])): ?>
                                                            <div class="mt-3">
                                                                <h6>Últimas valoraciones:</h6>
                                                                <?php foreach (array_slice($vendedor['valoraciones'], 0, 3) as $valoracion): ?>
                                                                    <div class="border-bottom pb-2 mb-2">
                                                                        <div><?= str_repeat('<i class="fa fa-star text-warning"></i>', $valoracion['calificacion']) ?></div>
                                                                        <p class="mb-1"><?= htmlspecialchars($valoracion['comentario']) ?></p>
                                                                        <small class="text-muted"><?= date('d/m/Y', strtotime($valoracion['fecha_valoracion'])) ?></small>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para valorar producto -->
                    <div class="modal fade" id="modalValorarProducto" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <<form action="GuardarValoracionController.php" method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Valorar Producto: <span id="nombre-producto-modal"></span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="tipo_valoracion" value="producto">
                                        <input type="hidden" name="id_producto" id="id-producto-modal">

                                        <div class="form-group">
                                            <label for="calificacion-producto">Calificación (1 a 5)</label>
                                            <select name="calificacion" id="calificacion-producto" class="form-control" required>
                                                <option value="">Selecciona</option>
                                                <option value="1">1 Estrella</option>
                                                <option value="2">2 Estrellas</option>
                                                <option value="3">3 Estrellas</option>
                                                <option value="4">4 Estrellas</option>
                                                <option value="5">5 Estrellas</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="comentario-producto">Comentario (opcional)</label>
                                            <textarea name="comentario" id="comentario-producto" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Enviar Valoración</button>
                                    </div>
                                </div>
                                </form>
                        </div>
                    </div>

                    <!-- Modal para valorar vendedor -->
                    <div class="modal fade" id="modalValorarVendedor" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="GuardarValoracionController.php" method="POST">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Valorar Vendedor: <span id="nombre-vendedor-modal"></span></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="tipo_valoracion" value="vendedor">
                                        <input type="hidden" name="id_vendedor" id="id-vendedor-modal">

                                        <div class="form-group">
                                            <label for="calificacion-vendedor">Calificación (1 a 5)</label>
                                            <select name="calificacion" id="calificacion-vendedor" class="form-control" required>
                                                <option value="">Selecciona</option>
                                                <option value="1">1 Estrella</option>
                                                <option value="2">2 Estrellas</option>
                                                <option value="3">3 Estrellas</option>
                                                <option value="4">4 Estrellas</option>
                                                <option value="5">5 Estrellas</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="comentario-vendedor">Comentario (opcional)</label>
                                            <textarea name="comentario" id="comentario-vendedor" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Enviar Valoración</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>




                    <?PHP include("LibraryJs.php"); ?>

                    <script>
                        $(document).ready(function() {
                            // Manejar clic en botón de valorar producto
                            $('.btn-valorar-producto').click(function() {
                                const productoId = $(this).data('producto-id');
                                console.log("ID del producto:", productoId, typeof productoId);
                                const productoNombre = $(this).data('producto-nombre');

                                $('#id-producto-modal').val(productoId);
                                $('#nombre-producto-modal').text(productoNombre);
                                $('#modalValorarProducto').modal('show');
                            });

                            // Manejar clic en botón de valorar vendedor
                            $('.btn-valorar-vendedor').click(function() {
                                const vendedorId = $(this).data('vendedor-id');
                                const vendedorNombre = $(this).data('vendedor-nombre');

                                $('#id-vendedor-modal').val(vendedorId);
                                $('#nombre-vendedor-modal').text(vendedorNombre);
                                $('#modalValorarVendedor').modal('show');
                            });
                        });
                    </script>

</body>

</html>