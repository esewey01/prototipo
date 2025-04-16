<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container" clas="">
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

        <!-- Contenido Principal -->
        <section id="main-content">
            <section class="wrapper">
                <!-- Pestañas para los tipos de usuario -->
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="page-header"><i class="fa fa-users"></i> ADMINISTRACIÓN DE USUARIOS</h3>
                        <div class="<?PHP echo $alerta; ?>" role="alert">´
                            <strong><?PHP echo $mensaje; ?></strong>
                            <ol class="breadcrumb">
                                <?php include("MenuOpcionesConfiguracion.php"); ?>
                            </ol>
                        </div>

                        <!-- Pestañas -->
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#administradores" data-toggle="tab">Administradores</a></li>
                            
                            <li><a href="#vendedores" data-toggle="tab">Vendedores</a></li>
                            <li><a href="#clientes" data-toggle="tab">Clientes</a></li>
                        </ul>



                        <!-- Contenido de las pestañas -->
                        <div class="tab-content">

                            <div class="panel-heading">
                                Lista de Administradores del Sistema
                                <div align="right">
                                    <button href="#addUser" title="" data-placement="left" data-toggle="modal"
                                        class="btn btn-primary tooltips" type="button"
                                        data-original-title="Nuevo <?php echo ucfirst($tipo_actual) ?>">
                                        <span class="fa fa-plus"> </span>
                                        AGREGAR NUEVO USUARIO
                                    </button>
                                </div>
                            </div>


                            <!-- Tabla de Administradores -->
                            
                            <!-- Tabla de Administradores -->
                            <div class="tab-pane active" id="administradores">
                                <?php
                                $tipo_actual = 'administrador';
                                $usuarios = $administradores;
                                include("_partials/tabla_usuarios.php");
                                ?>
                            </div>
                            <!-- Tabla de Vendedores -->
                            <div class="tab-pane" id="vendedores">
                                <?php
                                $tipo_actual = 'vendedor';
                                $usuarios = $vendedores;
                                include("_partials/tabla_usuarios.php");
                                ?>
                            </div>

                            <!-- Tabla de Clientes -->
                            <div class="tab-pane" id="clientes">
                                <?php
                                $tipo_actual = 'cliente';
                                $usuarios = $clientes;
                                include("_partials/tabla_usuarios.php");
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>

    <!-- Modal para Agregar Usuario -->
    <?php include("_partials/modal_add_user.php"); ?>

    <?php include("LibraryJs.php"); ?>

    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            // Inicializar DataTables en todas las tablas
            $('.table-usuarios').DataTable({
                responsive: true,
                dom: '<"top"lf>rt<"bottom"ip>',
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "Todos"]
                ],
                language: {
                    "search": "Buscar:",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontraron resultados",
                    "info": "Mostrando página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtrado de _MAX_ registros totales)",
                    "paginate": {
                        "first": "Primera",
                        "last": "Última",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }
            });
        });
    </script>
</body>

</html>