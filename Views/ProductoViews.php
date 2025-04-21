<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<body>
    <section id="container" clas="">
        <!-- CABECERAAAA -->
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

    <!--CONTENIDO DE LA PAGINA -->
    <section id="main-content">
        <section class="wrapper">

            <!--SUBMENU-->
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"><i class="fa fa-laptop"></i> PRINCIPAL</h3>
                    <div class="<?PHP echo $alerta; ?>" role="alert">
                        <strong><?PHP echo $mensaje; ?></strong>
                    </div>

                    <ol class="breadcrumb">
                        <li>
                            <i class="fa fa-home"></i><a href="principal.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Inicio</a>
                        </li>
                        <li>
                            <i class="fa fa-inbox"></i><a href="Producto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Producto</a>
                        </li>
                        <li>
                            <i class="fa fa-plus"></i><a href="TipoProducto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Registrar Tipo Producto</a>
                        </li>
                    </ol>
                </div>
            </div>



            <!-- ELEMENTOS -->
            <header class="panel-heading"> Lista de Productos del Sistema</header>
            <header class="panel heading">

                <!--BOTONES-->
                <div align="right">

                    <a href="ReporteProductosPdf.php?productos=productos" target="_blank"
                        class="btn btn-danger tooltips"><i
                            class="fa fa-rotate-right"></i> EXPORTAR PDF </a>

                    <button href="#add" title="" data-placement="top" data-toggle="modal"
                        class="btn btn-primary tooltips" type="button" data-original-title="Nuevo Producto">
                        <span class="icon_bag_alt"></span>AGREGAR NUEVO PRODUCTO
                    </button>
                </div>

                <!--FUNCION DE AGREGAR UN PRODUCTO -->
                <div id="add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <form action="RegistroProducto.php" method="post" enctype="multipart/form-data">
                        <input name="usuarioLogin" value="<?php echo $usuario; ?>" type="hidden">
                        <input name="passwordLogin" value="<?php echo $password; ?>" type="hidden">
                        <div class="modal-dialog" id="mdialTamanio">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x
                                    </button>
                                    <h3 id="myModalLabel" align="center">Registrar Informacion del Producto</h3>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <section class="panel">
                                                <div><strong>Agregar Imagen</strong></div>
                                                <br>
                                                <?php
                                                include("UploadViewImageCreate.php");
                                                ?>
                                            </section>
                                        </div>
                                        <div class="col-lg-8">
                                            <section class="panel">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Tipo Producto:</label>
                                                    <div class="col-sm-4">
                                                        <select class="form-control input-lg m-bot15"
                                                                name="tipoproducto">
                                                            
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <label class="col-sm-2 control-label">Codigo:</label>
                                                    <div class="col-sm-4">
                                                        <input class="form-control input-lg m-bot15" id="codigo"
                                                               name="codigo" type="text" required/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Descripcion:</label>
                                                    <div class="col-sm-10">
                                                        <input class="form-control input-lg m-bot15"
                                                               id="descripcion" name="descripcion" type="text"
                                                               required/>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label for="cantidad" class="control-label col-lg-2">Cantidad
                                                        :</label>
                                                    <div class="col-lg-4">
                                                        <input class="form-control input-lg m-bot15"
                                                               id="cantidad" name="cantidad"
                                                               placeholder="0.00" type="text" required/>
                                                    </div>
                                                    <label for="pVenta" class="control-label col-lg-2">Precio
                                                        de Venta:</label>
                                                    <div class="col-lg-4">
                                                        <input class="form-control input-lg m-bot15"
                                                               id="pventa" name="pventa"
                                                               placeholder="0.00" type="text" required/>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label for="pCompra" class="control-label col-lg-2">Precio
                                                        Compra:</label>
                                                    <div class="col-lg-4">
                                                        <input class="form-control input-lg m-bot15" id="pcompra"
                                                               name="pcompra" placeholder="0.00" type="text"
                                                               required/>
                                                    </div>
                                                    <label for="fechaRegistr"
                                                           class="control-label col-lg-2">Fecha:</label>
                                                    <div class="col-lg-4">
                                                        <input class="form-control input-lg m-bot15" type="date"
                                                               readonly name="fechaRegistro" autocomplete="off"
                                                               value="<?php echo date('Y-m-d'); ?>">
                                                    </div>

                                                </div>

                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true"><strong>Cerrar</strong>
                                    </button>
                                    <button name="nuevo_Producto" type="submit" class="btn btn-primary">
                                        <strong>Registrar Nuevo Producto</strong></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            </header>
        </section>
    </section>





    <?php include("LibraryJs.php"); ?>

</body>

</html>