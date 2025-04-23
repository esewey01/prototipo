<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>

    <section id="container" class="">
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


    </section>

    <!--TITULO Y SUBMENU-->
    <SECTION id="main-content">
        <section class=wrapper>

            <!--ELEMENTOS-->
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-laptop"></i>Productos para Vender</h3>
                    <div class="<?PHP echo $alerta; ?>" role="alert">
                        <strong><?PHP echo $mensaje; ?></strong>
                        <strong><?PHP echo $error; ?></strong>
                    </div>

                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-home"></i><a href="principal.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-inbox"></i><a href="Producto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Reportes</a>
                        </li>
                        <li>
                            <i class="fa fa-plus"></i><a href="TipoProducto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Reportes por Usuario</a>
                        </li>
                    </ol>
                </div>
            </div>




            <header class="panel-heading">LISTA DE REPORTES DEL SISTEMA</header>
            <header class="panel heading">
                <div class="panel-body">
                    <!--BOTONES-->
                    <div align="right">

                        <a href="ReporteProductosPdf.php?productos=productos" target="_blank"
                            class="btn btn-danger tooltips"><i
                                class="fa fa-rotate-right"></i> EXPORTAR PDF </a>
                    </div>
                </div>
            </header>


            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Usuario Reportado</th>
                                <th>Motivo</th>
                                <th>Acción</th>
                                <th>Administrador</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reportes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        NINGUN REPORTE REGISTRADO
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reportes as $reporte): ?>

                                    <tr>
                                        <td><?= $reporte['fecha_reporte']->format('d/m/Y H:i') ?></td>
                                        <td><?= htmlspecialchars($reporte['nombre_producto']) ?></td>
                                        <td><?= htmlspecialchars($reporte['nombre_reportado']) ?></td>
                                        <td><?= htmlspecialchars($reporte['motivo']) ?></td>
                                        <td><?= htmlspecialchars($reporte['accion_tomada']) ?></td>
                                        <td><?= htmlspecialchars($reporte['nombre_administrador']) ?></td>
                                        <td>
                                            <span class="label label-<?=
                                                                        $reporte['estado'] == 'PENDIENTE' ? 'warning' : ($reporte['estado'] == 'PROCESADO' ? 'success' : 'danger')
                                                                        ?>">
                                                <?= $reporte['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs"
                                                onclick="verDetalleReporte(<?= $reporte['id_reporte'] ?>)">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>


                    <!-- Modal para Detalles -->
   
        </SECTION>
    </SECTION>

    </div>


    <div class="modal fade" id="detalleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Detalles del Reporte</h4>
                </div>
                <div class="modal-body" id="detalleContenido">
                    
                    <!-- Cargado por AJAX -->
                </div>
            </div>
        </div>
    </div>
    </div>
    






    <?php include("LibraryJs.php"); ?>

    <script>
        // Función para ver detalles del reporte
        function verDetalleReporte(idReporte) {
            $.get('DetalleReporte.php?id=' + idReporte, function(data) {
                $('#detalleContenido').html(data);
                $('#detalleModal').modal('show');
            });
        }

        // DataTable
        $(document).ready(function() {
            $('#dataTables-exemple').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                },
                "order": [
                    [0, "desc"]
                ]
            });
        });
    </script>
</body>

</html>