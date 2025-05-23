<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPIICSAFOOD - Panel de Electromovilidad</title>
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

            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-laptop"></i> PRINCIPAL</h3>
                    <!--FUNCION DE ALERTA DE MENSAJES-->
                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong><?= $_SESSION['mensaje'] . ': ' . $_SESSION['usuario']['rol']['nombre_rol'] ?></strong>
                        </div>
                    <?php
                        unset($_SESSION['mensaje']);
                        unset($_SESSION['alerta']);
                    endif; ?>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-home"></i><a href="PrincipalController.php">Inicio</a></li>
                        <li><i class="fa fa-laptop"></i> Principal</li>
                    </ol>
                </div>
            </div>



            <!-- Resto del contenido original -->
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box blue-bg">
                        <i class="icon_wallet_alt"></i>
                        <div class="count"><?= count($pagos_pendientes) ?></div>
                        <div class="title>¡"> Pagos pendientes </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box brown-bg">
                        <i class="icon_cart"></i>
                        <div class="count"><?= $cantidad_carrito ?></div>
                        <div class="title">Productos en carrito</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box dark-bg">
                        <i class="fa fa-money"></i>
                        <div class="count">$<?= number_format($gastos_totales,2) ?></div>
                        <div class="title">Total gastado</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box green-bg">
                        <i class="fa fa-cubes"></i>
                        <div class="count"><?= count($ordenes_pagadas) ?></div>
                        <div class="title">Compras realizadas</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Últimos productos -->
                <div class="col-md-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Nuevos Productos Disponibles</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php foreach (array_slice($productos_nuevos, 0, 4) as $producto): ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="product-card" style="display: flex; align-items: flex-start;">


                                            <!-- Columna derecha (resto de la información) -->
                                            <div class="product-info" style="flex: 1; text-align: left;">
                                                <img src="<?= URL_VIEWS . $producto['imagen'] ?>" alt="<?= $producto['nombre_producto'] ?>" class="img-responsive" style="height: 100px;">
                                                <h5><?= $producto['nombre_producto'] ?></h5>
                                                <p>$<?= number_format($producto['precio_venta'], 2) ?></p>
                                                <a href="ComprarController.php" class="btn btn-primary btn-sm">Ver más</a>
                                            </div>

                                            <!-- Columna izquierda (descripción) -->
                                            <div class="product-description" style="flex: 1; padding-right: 10px;">
                                                <p class="card-text text-muted small">
                                                    <?= htmlspecialchars(substr($producto['descripcion'], 0, 100)) ?>...
                                                </p>
                                            </div>


                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">Calendario</h3>
                    </div>
                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            </div>

        </section>
    </section>

    <?PHP include("LibraryJs.php"); ?>

    <!-- Incluir Leaflet para el mapa -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js"></script>

    <script>
        $(document).ready(function() {
            // SECCION DE NOTICIAS 

            // Configurar el calendario
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month',
                locale: 'es',
                height: 'auto',
                aspectRatio: 1.5,
                eventLimit: true,
                events: [
                    // Puedes agregar eventos aquí o cargarlos dinámicamente
                    {
                        title: 'Reunión de equipo',
                        start: moment().format('YYYY-MM-DD') + 'T10:00:00',
                        end: moment().format('YYYY-MM-DD') + 'T12:00:00',
                        color: '#257e4a'
                    },
                    {
                        title: 'Entrega de reportes',
                        start: moment().add(2, 'days').format('YYYY-MM-DD'),
                        color: '#f39c12'
                    }
                ]
            });

        });
    </script>
</body>

</html>