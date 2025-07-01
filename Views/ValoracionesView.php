<?php
// Inicio de la vista ValoracionesView.php

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
$productosPorValorar = $viewData['productosPorValorar'] ?? [];
$productosValorados = $viewData['productosValorados'] ?? [];
$vendedoresPorValorar = $viewData['vendedoresPorValorar'] ?? [];
$vendedoresValorados = $viewData['vendedoresValorados'] ?? [];
$urlViews = $viewData['urlViews'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container" class="">
        <!-- Header -->
        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Menú Principal" data-placement="bottom">
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
                        <h3 class="page-header"><i class="fa fa-star"></i> Mis Valoraciones</h3>

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
                        endif;
                        ?>

                        <ol class="breadcrumb">
                            

                            <li><i class="fa fa-home"></i><a href="PrincipalController.php">Pagina de Inicio</a></li>
                            <li><i class="fa fa-question-circle"></i> <a href="#" data-toggle="modal" data-target="#helpValoraciones">¿Para que esta pagina?</a></li>
                            <li><i class="fa fa-user"></i><a href="PerfilController.php">Valora tus Productos Comprados</a></li>

                        </ol>
                    </div>
                </div>

                <!-- Pestañas de valoraciones -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#por-valorar" data-toggle="tab">Productos por valorar</a></li>
                                    <li><a href="#valorados" data-toggle="tab">Productos valorados</a></li>
                                    <li><a href="#vendedores-por-valorar" data-toggle="tab">Vendedores por valorar</a></li>
                                    <li><a href="#vendedores-valorados" data-toggle="tab">Vendedores valorados</a></li>
                                </ul>

                                <div class="tab-content">
                                    <!-- PRODUCTOS POR VALORAR -->
                                    <div class="tab-pane active" id="por-valorar">
                                        <?php if (empty($productosPorValorar)): ?>
                                            <div class="alert alert-info">
                                                No tienes productos pendientes de valorar.
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($productosPorValorar as $producto): ?>
                                                    <div class="col-md-12 mb-4">
                                                        <div class="card">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-3">
                                                                    <img src="../Views/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h4>
                                                                        <p class="card-text"><?= htmlspecialchars($producto['descripcion']) ?></p>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <p><strong>Precio:</strong> $<?= number_format($producto['precio_unitario'], 2) ?></p>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <p><strong>Comprado el:</strong> <?= is_string($producto['fecha_orden']) ? date('d/m/Y', strtotime($producto['fecha_orden'])) : $producto['fecha_orden']->format('d/m/Y') ?></p>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <p><strong>Vendedor:</strong> <?= htmlspecialchars($producto['nombre_vendedor']) ?></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <button class="btn btn-primary btn-valorar btn-valorar-producto"
                                                                                data-producto-id="<?= $producto['id_producto'] ?>"
                                                                                data-producto-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                                                <i class="fa fa-star"></i> Valorar Producto
                                                                            </button>
                                                                            <button class="btn btn-danger btn-reportar-producto"
                                                                                data-producto-id="<?= $producto['id_producto'] ?>"
                                                                                data-producto-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                                                <i class="fa fa-exclamation-triangle"></i> Reportar Producto
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- PRODUCTOS VALORADOS -->
                                    <div class="tab-pane" id="valorados">
                                        <?php if (empty($productosValorados)): ?>
                                            <div class="alert alert-info">
                                                No has valorado ningún producto aún.
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($productosValorados as $producto): ?>
                                                    <div class="col-md-12 mb-4">
                                                        <div class="card">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-3">
                                                                    <img src="../Views/<?= htmlspecialchars($producto['imagen']) ?>" class="card-img" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>">
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h4>
                                                                        <div class="mb-2">
                                                                            <?= str_repeat('<i class="fa fa-star text-warning"></i>', $producto['calificacion']) ?>
                                                                            <span class="ml-2">Valorado el <?= is_string($producto['fecha_valoracion']) ? date('d/m/Y', strtotime($producto['fecha_valoracion'])) : $producto['fecha_valoracion']->format('d/m/Y') ?></span>
                                                                        </div>
                                                                        <?php if (!empty($producto['comentario'])): ?>
                                                                            <div class="alert alert-light">
                                                                                <p><?= htmlspecialchars($producto['comentario']) ?></p>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <p><strong>Precio:</strong> $<?= number_format($producto['precio_unitario'], 2) ?></p>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <p><strong>Comprado el:</strong> <?= is_string($producto['fecha_orden']) ? date('d/m/Y', strtotime($producto['fecha_orden'])) : $producto['fecha_orden']->format('d/m/Y') ?></p>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <p><strong>Vendedor:</strong> <?= htmlspecialchars($producto['nombre_vendedor']) ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- VENDEDORES POR VALORAR -->
                                    <div class="tab-pane" id="vendedores-por-valorar">
                                        <?php if (empty($vendedoresPorValorar)): ?>
                                            <div class="alert alert-info">
                                                No tienes vendedores pendientes de valorar.
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($vendedoresPorValorar as $vendedor): ?>
                                                    <div class="col-md-12 mb-4">
                                                        <div class="card">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-3">
                                                                    <img src="../Views/<?= htmlspecialchars($vendedor['foto_perfil']) ?>" class="card-img" alt="<?= htmlspecialchars($vendedor['nombre']) ?>">
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title"><?= htmlspecialchars($vendedor['nombre']) ?></h4>
                                                                        <p class="card-text"><?= htmlspecialchars($vendedor['email']) ?></p>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p><strong>Productos comprados:</strong> <?= $vendedor['total_productos'] ?></p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Última compra:</strong> <?= is_string($vendedor['ultima_compra']) ? date('d/m/Y', strtotime($vendedor['ultima_compra'])) : $vendedor['ultima_compra']->format('d/m/Y') ?></p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <button class="btn btn-primary btn-valorar-vendedor"
                                                                                data-vendedor-id="<?= $vendedor['id_usuario'] ?>"
                                                                                data-vendedor-nombre="<?= htmlspecialchars($vendedor['nombre']) ?>">
                                                                                <i class="fa fa-star"></i> Valorar Vendedor
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- VENDEDORES VALORADOS -->
                                    <div class="tab-pane" id="vendedores-valorados">
                                        <?php if (empty($vendedoresValorados)): ?>
                                            <div class="alert alert-info">
                                                No has valorado ningún vendedor aún.
                                            </div>
                                        <?php else: ?>
                                            <div class="row">
                                                <?php foreach ($vendedoresValorados as $vendedor): ?>
                                                    <div class="col-md-12 mb-4">
                                                        <div class="card">
                                                            <div class="row no-gutters">
                                                                <div class="col-md-3">
                                                                    <img src="../Views/<?= htmlspecialchars($vendedor['foto_perfil']) ?>" class="card-img" alt="<?= htmlspecialchars($vendedor['nombre']) ?>">
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title"><?= htmlspecialchars($vendedor['nombre']) ?></h4>
                                                                        <div class="mb-2">
                                                                            <?= str_repeat('<i class="fa fa-star text-warning"></i>', $vendedor['calificacion']) ?>
                                                                            <span class="ml-2">Valorado el <?= is_string($vendedor['fecha_valoracion']) ? date('d/m/Y', strtotime($vendedor['fecha_valoracion'])) : $vendedor['fecha_valoracion']->format('d/m/Y') ?></span>
                                                                        </div>
                                                                        <?php if (!empty($vendedor['comentario'])): ?>
                                                                            <div class="alert alert-light">
                                                                                <p><?= htmlspecialchars($vendedor['comentario']) ?></p>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                <p><strong>Productos comprados:</strong> <?= $vendedor['total_productos'] ?></p>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <p><strong>Última compra:</strong> <?= is_string($vendedor['ultima_compra']) ? date('d/m/Y', strtotime($vendedor['ultima_compra'])) : $vendedor['ultima_compra']->format('d/m/Y') ?></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>

    <!-- Modal para valorar producto -->
    <div class="modal fade" id="modalValorarProducto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="GuardarValoracionController.php" method="POST">
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
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star star" data-value="<?= $i ?>"></i>
                                <?php endfor; ?>
                                <input type="hidden" name="calificacion" id="calificacion-producto" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comentario-producto">Comentario (opcional)</label>
                            <textarea name="comentario" id="comentario-producto" class="form-control" rows="3" placeholder="Escribe tu experiencia con este producto..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Enviar Valoración</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Ayuda sobre Valoraciones -->
    <div class="modal fade" id="helpValoraciones" tabindex="-1" role="dialog" aria-labelledby="helpValoracionesTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="helpValoracionesTitle"><i class="fa fa-question-circle"></i> Cómo funciona el sistema de valoraciones</h4>
                </div>
                <div class="modal-body">
                    <p>Nuestro sistema de valoraciones está diseñado para garantizar opiniones auténticas basadas en transacciones reales.</p>

                    <div class="alert alert-warning">
                        <strong><i class="fa fa-exclamation-triangle"></i> Requisitos para valorar:</strong>
                        <ul>
                            <li>Solo puedes valorar productos que hayas <strong>comprado y recibido</strong>.</li>
                            <li>Debes esperar hasta que el vendedor <strong>confirme la venta</strong> y el producto haya sido marcado como entregado.</li>
                            <li>Las valoraciones deben ser objetivas y basadas en tu experiencia real con el producto/vendedor.</li>
                        </ul>
                    </div>

                    <h5>Proceso paso a paso:</h5>
                    <ol>
                        <li>Realiza una compra en nuestra plataforma.</li>
                        <li>Espera a que el vendedor confirme y envíe tu pedido.</li>
                        <li>Cuando recibas el producto, el vendedor marcará la venta como completada.</li>
                        <li>En ese momento, el producto aparecerá en tu sección <strong>"Productos por valorar"</strong>.</li>
                        <li>Puedes valorar el producto y al vendedor dentro de los <strong>30 días</strong> siguientes.</li>
                    </ol>

                    <div class="alert alert-info">
                        <strong><i class="fa fa-info-circle"></i> ¿Por qué no puedo valorar?</strong>
                        <p>Si no ves un producto que compraste en esta sección, puede ser porque:</p>
                        <ul>
                            <li>El vendedor aún no ha confirmado la venta.</li>
                            <li>El producto no ha sido marcado como entregado.</li>
                            <li>Han pasado más de 30 días desde la confirmación de entrega.</li>
                            <li>Ya valoraste este producto anteriormente.</li>
                        </ul>
                    </div>

                    <p>Si crees que hay un error o necesitas ayuda, por favor <a href="#contacto">contacta a nuestro equipo de soporte</a>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para valorar vendedor -->
    <div class="modal fade" id="modalValorarVendedor" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="GuardarValoracionController.php" method="POST">
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
                            <div class="rating-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa fa-star star" data-value="<?= $i ?>"></i>
                                <?php endfor; ?>
                                <input type="hidden" name="calificacion" id="calificacion-vendedor" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comentario-vendedor">Comentario (opcional)</label>
                            <textarea name="comentario" id="comentario-vendedor" class="form-control" rows="3" placeholder="Escribe tu experiencia con este vendedor..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Enviar Valoración</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para reportar producto -->
    <div class="modal fade" id="modalReportarProducto" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="ReportarProductoController.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Reportar Producto: <span id="nombre-producto-reportar"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_producto" id="id-producto-reportar">

                        <div class="form-group">
                            <label for="motivo-reporte">Motivo del reporte</label>
                            <select name="motivo" id="motivo-reporte" class="form-control" required>
                                <option value="">Selecciona un motivo</option>
                                <option value="Contenido inapropiado">Contenido inapropiado</option>
                                <option value="Información falsa">Información falsa</option>
                                <option value="Producto prohibido">Producto prohibido</option>
                                <option value="Precio incorrecto">Precio incorrecto</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="descripcion-reporte">Descripción del problema</label>
                            <textarea name="descripcion" id="descripcion-reporte" class="form-control" rows="3" required placeholder="Describe detalladamente el problema..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Enviar Reporte</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("LibraryJs.php"); ?>

    <script>
        $(document).ready(function() {
            // Inicializar pestañas
            $('.nav-tabs a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Manejar clic en botón de valorar producto
            $('.btn-valorar-producto').click(function() {
                const productoId = $(this).data('producto-id');
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

            // Manejar clic en botón de reportar producto
            $('.btn-reportar-producto').click(function() {
                const productoId = $(this).data('producto-id');
                const productoNombre = $(this).data('producto-nombre');

                $('#id-producto-reportar').val(productoId);
                $('#nombre-producto-reportar').text(productoNombre);
                $('#modalReportarProducto').modal('show');
            });

            // Sistema de estrellas para valoración
            $('.star').hover(
                function() {
                    const value = $(this).data('value');
                    $(this).parent().find('.star').each(function() {
                        if ($(this).data('value') <= value) {
                            $(this).addClass('hover');
                        } else {
                            $(this).removeClass('hover');
                        }
                    });
                },
                function() {
                    $(this).parent().find('.star').removeClass('hover');
                }
            );

            $('.star').click(function() {
                const value = $(this).data('value');
                const inputId = $(this).parent().find('input[type="hidden"]').attr('id');

                $(this).parent().find('.star').each(function() {
                    if ($(this).data('value') <= value) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });

                $('#' + inputId).val(value);
            });
        });
    </script>
</body>

</html>