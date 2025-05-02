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
                <h2 class="mt-4"><i class="icon_bag"></i> Gestión de Órdenes</h2>

                <!-- Mensajes de éxito/error -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Filtros -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="get" action="VendedorOrdenesController.php">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Filtrar por estado:</label>
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
                                    <a href="VendedorOrdenesController.php" class="btn btn-secondary">Limpiar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Listado de órdenes -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($ordenes)): ?>
                            <div class="alert alert-info">No hay órdenes registradas</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID Orden</th>
                                            <th>Fecha</th>
                                            <th>Cliente</th>
                                            <th>Productos</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ordenes as $orden): ?>
                                            <tr>
                                                <td>#<?= $orden['id_orden'] ?></td>
                                                <td><?= $orden['fecha_orden']->format('d/m/Y H:i') ?></td>
                                                <td>
                                                    <?= htmlspecialchars($orden['cliente_nombre']) ?>
                                                    <small class="text-muted d-block"><?= $orden['cliente_login'] ?></small>
                                                </td>
                                                <td><?= $orden['total_productos'] ?></td>
                                                <td>$<?= number_format($orden['total'], 2) ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?= $orden['estado'] == 'PAGADO' ? 'badge-pagado' : 
                                                           ($orden['estado'] == 'PENDIENTE' ? 'badge-pendiente' : 
                                                           ($orden['estado'] == 'CANCELADO' ? 'badge-cancelado' : 'badge-entregado')) ?>">
                                                        <?= $orden['estado'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="post" action="VendedorOrdenesController.php?action=actualizar_estado" class="d-inline">
                                                        <input type="hidden" name="id_orden" value="<?= $orden['id_orden'] ?>">
                                                        <select name="estado" class="form-select estado-select" 
                                                            onchange="this.form.submit()" 
                                                            <?= $orden['estado'] != 'PENDIENTE' ? 'disabled' : '' ?>>
                                                            <option value="PAGADO" <?= $orden['estado'] == 'PAGADO' ? 'selected' : '' ?>>Pagado</option>
                                                            <option value="ENTREGADO" <?= $orden['estado'] == 'ENTREGADO' ? 'selected' : '' ?>>Entregado</option>
                                                            <option value="CANCELADO" <?= $orden['estado'] == 'CANCELADO' ? 'selected' : '' ?>>Cancelado</option>
                                                        </select>
                                                    </form>
                                                    
                                                    <a href="VendedorDetalleOrdenController.php?id=<?= $orden['id_orden'] ?>" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="icon_search"></i> Detalle
                                                    </a>
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




    <?PHP include("LibraryJs.php"); ?>
</body>
</html>