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
                            <li class="active"><a href="#admins" data-toggle="tab">Administradores</a></li>
                            <li><a href="#vendedores" data-toggle="tab">Vendedores</a></li>
                            <li><a href="#clientes" data-toggle="tab">Clientes</a></li>
                        </ul>

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content">
                            <!-- Tabla de Administradores -->
                            <!-- Tabla de Administradores -->
                            <div class="tab-pane active" id="admins">
                                <?php
                                $tipo_actual = 'admin';
                                $usuarios = $admins;
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
    </section>

    <!-- Modal para Agregar Usuario (se mantiene igual) -->
    <?php include("LibraryJs.php"); ?>


    
    <!-- Script para DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true,
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