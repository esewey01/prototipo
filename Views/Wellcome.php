<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UPIICSAFOOD - Panel de Electromovilidad</title>
    <style>
        .electromovilidad-card {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            padding: 15px;
            margin-bottom: 20px;
        }
        .vehicle-card {
            border-left: 4px solid #4CAF50;
            margin-bottom: 10px;
            padding: 10px;
            background: #f9f9f9;
        }
        .vehicle-id {
            font-weight: bold;
            color: #2196F3;
        }
        .route-id {
            color: #FF5722;
        }
        .refresh-btn {
            margin-bottom: 15px;
        }
        #map-container {
            height: 400px;
            width: 100%;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }
    </style>
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
                            <strong><?= $_SESSION['mensaje'].': '.$_SESSION['usuario']['rol']['nombre_rol'] ?></strong>
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

            <!-- Sección de Electromovilidad -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="electromovilidad-card">
                        <h3><i class="fa fa-bus"></i> Datos de Electromovilidad en Tiempo Real</h3>
                        <button id="refresh-data" class="btn btn-primary refresh-btn">
                            <i class="fa fa-refresh"></i> Actualizar Datos
                        </button>
                        
                        <div id="electromovilidad-data">
                            <!-- Los datos se cargarán aquí mediante AJAX -->
                            <div class="alert alert-info">Cargando datos de electromovilidad...</div>
                        </div>
                        
                        <div id="map-container">
                            <!-- Aquí se mostrará el mapa con las ubicaciones -->
                            <div id="map" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resto del contenido original -->
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box blue-bg">
                        <i class="fa fa-truck"></i>
                        <div class="count"><span style="font-size: xx-small; "></span></div>
                        <div class="title"> Proveedores </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box brown-bg">
                        <i class="icon_piechart"></i>
                        <div class="count"><span style="font-size: xx-small; "></span></div>
                        <div class="title"> Reportes de Ventas </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box dark-bg">
                        <i class="fa fa-money"></i>
                        <div class="count"></div>
                        <div class="title">Gastos y Entradas</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <div class="info-box green-bg">
                        <i class="fa fa-cubes"></i>
                        <div class="count"></div>
                        <div class="title">Stock de los productos</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Contenido principal aquí -->
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
        // Inicializar el mapa
        var map = L.map('map').setView([19.4326, -99.1332], 12); // Coordenadas de CDMX
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        // Marcadores para los vehículos
        var vehicleMarkers = {};
        
        // Función para cargar datos de electromovilidad
        function loadElectromovilidadData() {
            $('#electromovilidad-data').html('<div class="alert alert-info">Cargando datos de electromovilidad...</div>');
            
            // Primero obtenemos las URLs de la API
            $.ajax({
                url: 'https://metrobus-gtfs.sinopticoplus.com/gtfs-api/partnerValidation',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    "usuario": "dmau639@gmail.com",
                    "senha": "pC}Q>mx,"
                }),
                success: function(response) {
                    if(response && response.gtfsRealtimeUrl) {
                        // Ahora obtenemos los datos en tiempo real
                        fetchRealtimeData(response.gtfsRealtimeUrl);
                    } else {
                        $('#electromovilidad-data').html('<div class="alert alert-danger">No se pudo obtener la URL de datos en tiempo real</div>');
                    }
                },
                error: function() {
                    $('#electromovilidad-data').html('<div class="alert alert-danger">Error al conectar con la API de electromovilidad</div>');
                }
            });
        }
        
        // Función para obtener datos en tiempo real
        function fetchRealtimeData(realtimeUrl) {
            $.ajax({
                url: realtimeUrl,
                type: 'GET',
                dataType: 'binary',
                processData: false,
                responseType: 'arraybuffer',
                success: function(data) {
                    try {
                        // Aquí necesitarías un decodificador de Protocol Buffers para interpretar los datos
                        // Esto es un ejemplo simplificado
                        var vehicleData = parseRealtimeData(data);
                        displayVehicleData(vehicleData);
                    } catch(e) {
                        console.error("Error al procesar datos:", e);
                        $('#electromovilidad-data').html(
                            '<div class="alert alert-danger">Error al procesar datos en tiempo real. ' + 
                            'Consulta la consola para más detalles.</div>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    $('#electromovilidad-data').html(
                        '<div class="alert alert-danger">Error al obtener datos en tiempo real: ' + 
                        error + '</div>'
                    );
                }
            });
        }
        
        // Función de ejemplo para parsear datos (necesitarías implementar el parser real)
        function parseRealtimeData(data) {
            // En una implementación real, usarías un decodificador de Protocol Buffers
            // Aquí devolvemos datos de ejemplo para demostración
            return [
                {
                    id: "VH001",
                    route: "L1",
                    latitude: 19.4326 + (Math.random() * 0.1 - 0.05),
                    longitude: -99.1332 + (Math.random() * 0.1 - 0.05),
                    speed: Math.floor(Math.random() * 50),
                    timestamp: new Date().toISOString()
                },
                {
                    id: "VH002",
                    route: "L2",
                    latitude: 19.4326 + (Math.random() * 0.1 - 0.05),
                    longitude: -99.1332 + (Math.random() * 0.1 - 0.05),
                    speed: Math.floor(Math.random() * 50),
                    timestamp: new Date().toISOString()
                },
                {
                    id: "VH003",
                    route: "L3",
                    latitude: 19.4326 + (Math.random() * 0.1 - 0.05),
                    longitude: -99.1332 + (Math.random() * 0.1 - 0.05),
                    speed: Math.floor(Math.random() * 50),
                    timestamp: new Date().toISOString()
                }
            ];
        }
        
        // Función para mostrar los datos de los vehículos
        function displayVehicleData(vehicles) {
            var html = '';
            var now = new Date();
            
            // Limpiar marcadores antiguos
            for (var id in vehicleMarkers) {
                map.removeLayer(vehicleMarkers[id]);
            }
            vehicleMarkers = {};
            
            if(vehicles.length === 0) {
                html = '<div class="alert alert-warning">No hay datos de vehículos disponibles</div>';
            } else {
                html += '<h4>Vehículos en operación: ' + vehicles.length + '</h4>';
                html += '<p>Última actualización: ' + now.toLocaleTimeString() + '</p>';
                
                vehicles.forEach(function(vehicle) {
                    // Tarjeta de información del vehículo
                    html += '<div class="vehicle-card">';
                    html += '<div><span class="vehicle-id">Vehículo: ' + vehicle.id + '</span></div>';
                    html += '<div><span class="route-id">Ruta: ' + vehicle.route + '</span></div>';
                    html += '<div>Velocidad: ' + vehicle.speed + ' km/h</div>';
                    html += '<div>Última actualización: ' + new Date(vehicle.timestamp).toLocaleTimeString() + '</div>';
                    html += '</div>';
                    
                    // Añadir marcador al mapa
                    var marker = L.marker([vehicle.latitude, vehicle.longitude]).addTo(map)
                        .bindPopup('<b>Vehículo: ' + vehicle.id + '</b><br>Ruta: ' + vehicle.route);
                    
                    vehicleMarkers[vehicle.id] = marker;
                });
                
                // Ajustar el mapa para mostrar todos los marcadores
                var group = new L.featureGroup(Object.values(vehicleMarkers));
                map.fitBounds(group.getBounds().pad(0.1));
            }
            
            $('#electromovilidad-data').html(html);
        }
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



        
        // Cargar datos al inicio
        loadElectromovilidadData();
        
        // Configurar botón de actualización
        $('#refresh-data').click(function() {
            loadElectromovilidadData();
        });
        
        // Actualizar automáticamente cada 30 segundos
        setInterval(loadElectromovilidadData, 30000);
    });
    </script>
</body>
</html>