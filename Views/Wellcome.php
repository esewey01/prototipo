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
                    <div class="title has-help" data-toggle="modal" data-target="#helpPaginaPrincipal" style="cursor: pointer;">
                        <h3 class="page-header">
                            <i class="fa fa-laptop"></i> PAGINA PRINCIPAL
                        </h3>
                    </div>
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
                        <div class="title has-help" data-toggle="modal" data-target="#helpPagos">
                            Pagos pendientes
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box brown-bg">
                        <i class="icon_cart"></i>
                        <div class="count"><?= $cantidad_carrito ?></div>
                        <div class="title">
                            <div class="title has-help" data-toggle="modal" data-target="#helpCarrito">
                                Productos en carrito
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box dark-bg">
                        <i class="fa fa-money"></i>
                        <div class="count">$<?= number_format($gastos_totales, 2) ?></div>
                        <div class="title">

                            <div class="title has-help" data-toggle="modal" data-target="#helpGastos">
                                Total gastado
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box green-bg">
                        <i class="fa fa-cubes"></i>
                        <div class="count"><?= count($ordenes_pagadas) ?></div>
                        <div class="title">
                            <div class="title has-help" data-toggle="modal" data-target="#helpCompras">
                                Compras realizadas
                            </div>
                        </div>
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
        <!-- Modal para Pagina Principal -->
        <div class="modal fade help-modal" id="helpPaginaPrincipal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="helpModalTitle">Bienvenido a la Tienda Oficial UPIICSA - ¡Tu Espacio Politécnico!</h4>

                    </div>
                    <div class="modal-body">
                        <p>¡Hola, <strong>Politécnico</strong>! Te damos la bienvenida a la plataforma exclusiva de la <strong>UPIICSA</strong>, diseñada para facilitar la <strong>compra y venta de productos</strong> dentro de nuestro campus. Aquí encontrarás todo lo que necesitas, ¡directamente de politécnicos para politécnicos!</p>
                        <p>En esta página principal, podrás:</p>
                        <ul>
                            <li><strong>Explorar productos:</strong> Descubre una amplia variedad de artículos disponibles, desde comida, material de estudio hasta productos de vendedores UPIICSA.</li>
                            <li><strong>Comprar fácilmente:</strong> Añade tus productos favoritos al carrito y realiza tus compras de forma segura y sencilla.</li>
                            <li><strong>Vender tus artículos:</strong> ¿Tienes algo que ofrecer a la comunidad? Publica tus productos y llega a miles de compañeros.</li>
                            <li><strong>Filtrar y buscar:</strong> Encuentra rápidamente lo que necesitas usando nuestras opciones de búsqueda y filtros por categoría o precio.</li>
                            <li><strong>Gestionar tus pedidos:</strong> Revisa el estado de tus compras y ventas desde tu perfil.</li>

                        </ul>
                        <p>Nuestro objetivo es crear un espacio de <strong>comercio seguro y eficiente</strong> para todos los miembros de la <strong>UPIICSA</strong>. ¡Esperamos que disfrutes tu experiencia!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">¡Comenzar a Explorar!</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal para Pagos pendientes -->
        <div class="modal fade help-modal" id="helpPagos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Información sobre Pagos Pendientes</h4>
                    </div>
                    <div class="modal-body">
                        <p>Los <strong>Pagos Pendientes</strong> son transacciones que has realizado pero que aún no han sido confirmadas por el <strong>Vendedor Correspondiente</strong>.</p>
                        <ul>
                            <li>Este número se actualiza automáticamente</li>
                            <li>Los pagos pueden tardar hasta 24 horas en procesarse</li>
                            <li>Si un pago permanece más de 48 horas, puedes mandar un reporte</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Productos en carrito -->
        <div class="modal fade help-modal" id="helpCarrito" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Información sobre el Carrito</h4>

                    </div>
                    <div class="modal-body">
                        <p>El <strong>Carrito de Compras</strong> contiene los productos que has seleccionado pero aún no has comprado.</p>
                        <ul>
                            <li>Puedes tener productos en el carrito por tiempo ilimitado</li>
                            <li>Los precios pueden cambiar si no finalizas la compra</li>
                            <li>Haz clic en "Ver carrito" para gestionar tus productos</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Total gastado -->
        <div class="modal fade help-modal" id="helpGastos" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Información sobre Gastos Totales</h4>

                    </div>
                    <div class="modal-body">
                        <p>El <strong>Total Gastado</strong> representa la suma de todas tus compras realizadas en nuestra plataforma.</p>
                        <ul>
                            <li>Solamente incluye las ventas confirmadas por vendedores</li>
                            <li>Se actualiza automáticamente con cada compra</li>
                            <li>Puedes ver el desglose en tu historial de compras</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Compras realizadas -->
        <div class="modal fade help-modal" id="helpCompras" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Información sobre Compras Realizadas</h5>

                    </div>
                    <div class="modal-body">
                        <p>Las <strong>Compras Realizadas</strong> son pedidos que has completado y pagado satisfactoriamente.</p>
                        <ul>
                            <li>Cada compra tiene un número de seguimiento único</li>
                            <li>Puedes ver el detalle de cada compra en tu historial</li>
                            <li>Recibirás un correo de confirmación por cada compra (futuro jijiji)</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?PHP include("LibraryJs.php"); ?>

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