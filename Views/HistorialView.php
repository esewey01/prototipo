<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!--Menu desplegable-->
    <section id="container" class="">

        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Menú Principal" data-placement="bottom"><i
                        class="icon_menu"></i></div>
            </div>
            <?PHP include("Logo.php") ?>

            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Search" type="text">
                        </form>
                    </li>
                </ul>
                <!--  search form end -->
            </div>
            <?PHP include("DropDown.php"); ?> <!--MENU DE USUARIO-->
        </header>

        <?PHP include("Menu.php") ?>

    </section>


    <section id="main-content">
        <section class="wrapper">
            <div class="container-fluid">
                <h2 class="mt-4"><i class="icon_document"></i> Mi Historial de Compras</h2>

                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form id="filtroForm" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Filtrar por estado:</label>
                                <select name="estado" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="PENDIENTE" <?= ($_GET['estado'] ?? '') == 'PENDIENTE' ? 'selected' : '' ?>>Pendientes</option>
                                    <option value="PAGADO" <?= ($_GET['estado'] ?? '') == 'PAGADO' ? 'selected' : '' ?>>Pagados</option>
                                    <option value="ENTREGADO" <?= ($_GET['estado'] ?? '') == 'ENTREGADO' ? 'selected' : '' ?>>Entregados</option>
                                    <option value="CANCELADO" <?= ($_GET['estado'] ?? '') == 'CANCELADO' ? 'selected' : '' ?>>Cancelados</option>
                                </select>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Listado de órdenes -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($ordenes)): ?>
                            <div class="alert alert-info">No tienes órdenes registradas</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Orden</th>
                                            <th>Fecha</th>
                                            <th>Productos</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ordenes as $orden): ?>
                                            <tr class="order-row">
                                                <td>#<?= $orden['id_orden'] ?></td>
                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                <td><?= $orden['total_productos'] ?></td>
                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?= $orden['estado'] == 'PAGADO' ? 'badge-pagado' : ($orden['estado'] == 'PENDIENTE' ? 'badge-pendiente' : ($orden['estado'] == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado')) ?>">
                                                        <?= $orden['estado'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary btn-ver-detalle"
                                                        data-id="<?= $orden['id_orden'] ?>">
                                                        <i class="icon_gift"></i> Ver
                                                    </button>
                                                    <?php if ($orden['estado'] == 'PAGADO'): ?>
                                                        <button class="btn btn-sm btn-danger btn-eliminar"
                                                            data-id="<?= $orden['id_orden'] ?>">
                                                            <i class="icon_trash"></i> Eliminar
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <?PHP include("LibraryJs.php") ?>
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de Orden #<span id="modal-order-id"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <!-- Aquí se cargará el contenido dinámico -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    
    
</body>


<script>
    $(document).ready(function() {
        // Manejar el filtro
        $('#filtroForm').on('submit', function(e) {
            e.preventDefault();
            const estado = $(this).find('[name="estado"]').val();
            window.location.href = 'HistorialController.php?estado=' + (estado || '');
        });

        // Manejar clic en ver detalle
        $('.btn-ver-detalle').click(function() {
            const idOrden = $(this).data('id');
            
            $.ajax({
                url: 'HistorialController.php?action=ver&id=' + idOrden,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#modal-body-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
                    $('#detalleModal').modal('show');
                },
                success: function(data) {
                    // Construir el contenido del modal con los datos recibidos
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Información de la Orden</h6>
                                <p><strong>Fecha:</strong> ${data.fecha_formateada}</p>
                                <p><strong>Estado:</strong> 
                                    <span class="badge ${data.orden.estado == 'PAGADO' ? 'badge-pagado' : 
                                        data.orden.estado == 'PENDIENTE' ? 'badge-pendiente' : 
                                        data.orden.estado == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado'}">
                                        ${data.orden.estado}
                                    </span>
                                </p>
                                <p><strong>Total:</strong> $${data.orden.total.toFixed(2)}</p>
                            </div>
                        </div>
                        
                        <h6 class="mt-4">Productos</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    data.detalles.forEach(item => {
                        html += `
                            <tr>
                                <td>
                                    <img src="<?= URL_VIEWS ?>${item.imagen}" 
                                        class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    ${item.nombre_producto}
                                </td>
                                <td>${item.cantidad}</td>
                                <td>$${item.precio_unitario.toFixed(2)}</td>
                                <td>$${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                            </tr>`;
                    });
                    
                    html += `
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>$${data.orden.total.toFixed(2)}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>`;
                    
                    $('#modal-order-id').text(data.orden.id_orden);
                    $('#modal-body-content').html(html);
                },
                error: function(xhr) {
                    $('#modal-body-content').html('<div class="alert alert-danger">Error al cargar los detalles</div>');
                }
            });
        });

        // Manejar eliminación de orden
        $('.btn-eliminar').click(function() {
            const idOrden = $(this).data('id');
            
            if (confirm('¿Estás seguro de que deseas eliminar esta orden? Esta acción no se puede deshacer.')) {
                $.ajax({
                    url: 'HistorialController.php?action=eliminar',
                    type: 'POST',
                    data: { id_orden: idOrden },
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + (response.message || 'No se pudo eliminar la orden'));
                        }
                    },
                    error: function() {
                        alert('Error en la comunicación con el servidor');
                    }
                });
            }
        });
    });
    </script>
</html>