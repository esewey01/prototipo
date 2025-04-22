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
                <div class="panel-body">
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
                        <form action="ProductoController.php?action=guardar" method="POST" enctype="multipart/form-data">
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
                                                        <label class="col-sm-2 control-label">Categoria:</label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control input-lg m-bot12"
                                                                name="id_categoria" required>
                                                                <option value="">Seleccionar Categoría</option>
                                                                <?php foreach ($categorias as $categoria): ?>
                                                                    <option value="<?php echo $categoria['id_categoria']; ?>">
                                                                        <?php echo $categoria['nombre_categoria']; ?>
                                                                    </option>
                                                                <?php endforeach; ?>

                                                            </select>
                                                        </div>
                                                        <label class="col-sm-2 control-label">Codigo:</label>
                                                        <div class="col-sm-4">
                                                            <input class="form-control input-lg m-bot15" id="codigo"
                                                                name="codigo" type="text" required />
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Nombre:</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control input-lg m-bot15"
                                                                id="nombre_producto" name="nombre_producto" type="text"
                                                                required />
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Descripcion:</label>
                                                        <div class="col-sm-10">
                                                            <input class="form-control input-lg m-bot15"
                                                                id="descripcion" name="descripcion" type="text"
                                                                required />
                                                        </div>

                                                    </div>

                                                    
                                                    <div class="form-group">
                                                        <label for="cantidad" class="control-label col-lg-2">Cantidad
                                                            :</label>
                                                        <div class="col-lg-4">
                                                            <input class="form-control input-lg m-bot15"
                                                                id="cantidad" name="cantidad"
                                                                placeholder="0.00" type="text" required />
                                                        </div>


                                                        <label for="pVenta" class="control-label col-lg-2">Precio
                                                            de Venta:</label>
                                                        <div class="col-lg-4">
                                                            <input class="form-control input-lg m-bot15"
                                                                id="pventa" name="pventa"
                                                                placeholder="0.00" type="text" required />
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pCompra" class="control-label col-lg-2">Precio
                                                            Compra:</label>
                                                        <div class="col-lg-4">
                                                            <input class="form-control input-lg m-bot15" id="pcompra"
                                                                name="pcompra" placeholder="0.00" type="text"
                                                                required />
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


            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>IMAGEN</th>
                                <th>CODIGO</th>
                                <th>PRODUCTO</th>
                                <?php if ($id_rol < 3): ?>
                                    <th>VENDEDOR</th>
                                <?php endif; ?>
                                <th>DESCRIPCION</th>
                                <th>CATEGORIA</th>
                                <th>STOCK</th>
                                <th>PRECIO COMPRA</th>
                                <th>PRECIO VENTA</th>
                                <th>FECHA REGISTRO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>

                        <?php foreach ($productos as $product): ?>
                            <tr>
                                <td><img src="<?php echo URL_VIEWS . $product['imagen'] ?>" height="50"
                                        width="50"></td>
                                <td> <?PHP echo $product['codigo']; ?></td>
                                <td> <?PHP echo $product['nombre_producto']; ?></td>
                                <td> <?PHP echo $product['nombre_usuario']; ?></td>
                                <td> <?PHP echo $product['descripcion']; ?></td>
                                <td> <?PHP echo $product['nombre_categoria']; ?></td>
                                <td> <?PHP echo $product['cantidad']; ?></td>
                                <td> <?PHP echo $product['precio_compra']; ?></td>
                                <td> <?PHP echo $product['precio_venta']; ?></td>
                                <td> <?PHP echo $product['fecha_registro']->format('Y-m-d H:i:s'); ?></td>
                                <td>
                                    <a href="#a<?php echo $product[0]; ?>" role="button"
                                        class="btn btn-success" data-toggle="modal">
                                        <i class="icon_check_alt2"></i> </a>
                                    <a href="RegistroProducto.php?idborrar=<?PHP echo $product[0]; ?>&usuarioLogin=<?PHP echo $usuario; ?>&passwordLogin=<?PHP echo $password; ?>"
                                        role="button" class="btn btn-danger"> <i class="icon_close_alt2"></i>
                                    </a>
                                </td>
                            </tr>

                            <div id="a<?php echo $product[0]; ?>" class="modal fade" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel" aria-hidden="true">
                                <form class="form-validate form-horizontal" name="form2" enctype="multipart/form-data"
                                    action="RegistroProducto.php" method="POST">
                                    <input type="hidden" id="idproducto" name="idproducto"
                                        value="<?php echo $product['idproducto']; ?>">
                                    <input type="hidden" name="imagen" value="<?php echo $product['imagen']; ?>">
                                    <input name="usuarioLogin" value="<?php echo $usuario; ?>" type="hidden">
                                    <input name="passwordLogin" value="<?php echo $password; ?>" type="hidden">

                                    <div class="modal-dialog" id="mdialTamanio">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-hidden="true">x
                                                </button>
                                                <h3 id="myModalLabel" align="center">Cambiar Informacion del
                                                    Producto</h3>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <section class="panel">
                                                            <img src="<?PHP echo $urlViews . $product['imagen']; ?>" width="250"
                                                                height="250">
                                                            <br><br>
                                                            <div><strong>Cambiar Imagen</strong></div>
                                                            <?php include("UploadViewImageEdit.php"); ?>
                                                        </section>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <section class="panel">
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label">Categoria:</label>
                                                                <div class="col-sm-4">
                                                                    <select class="form-control input-lg m-bot15"
                                                                        name="tipoproducto">
                                                                        <option value="">Seleccionar Categoría</option>
                                                                        <?php foreach ($categorias as $categoria): ?>
                                                                            <option value="<?php echo $categoria['id_categoria']; ?>">
                                                                                <?php echo $categoria['nombre_categoria']; ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <label class="col-sm-2 control-label">Codigo:</label>
                                                                <div class="col-sm-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="codigo"
                                                                        name="codigo" type="text"
                                                                        value="<?php echo $product['codigo']; ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label">Descripcion:</label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="descripcion" name="descripcion"
                                                                        type="text"
                                                                        value="<?php echo $product['nombreProducto']; ?>" />
                                                                </div>

                                                            </div>
                                                            <div class="form-group">
                                                                <label for="pdistribuidor"
                                                                    class="control-label col-lg-2">Cantidad :</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="cantidad" name="cantidad"
                                                                        placeholder="0.00" type="text"
                                                                        value="<?php echo $product['cantidad']; ?>" />
                                                                </div>
                                                                <label for="pprofesional"
                                                                    class="control-label col-lg-2">Precio de
                                                                    Venta:</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="pcompra" name="pcompra"
                                                                        placeholder="0.00" type="text"
                                                                        value="<?php echo $product['precioCompra']; ?>" />
                                                                </div>

                                                            </div>


                                                            <div class="form-group">
                                                                <label for="ppublico" class="control-label col-lg-2">Precio
                                                                    de
                                                                    compra:</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="pventa"
                                                                        name="pventa" placeholder="0.00"
                                                                        type="text"
                                                                        value="<?php echo $product['precioVenta']; ?>" />
                                                                </div>
                                                                <label for="pventa"
                                                                    class="control-label col-lg-2">Fecha:</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        type="date"
                                                                        readonly name="fechaRegistro"
                                                                        autocomplete="off"
                                                                        value="<?php echo date('Y-m-d'); ?>">
                                                                </div>


                                                            </div>
                                                        </section>

                                                    </div>
                                                </div>


                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                                                    <strong>Cerrar</strong></button>
                                                <button name="update_producto" type="submit" class="btn btn-primary">
                                                    <strong>Editar</strong>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                            </div>

                        <?php endforeach; ?>
                    </table>
                </div>
            </div>







        </section>
    </section>





    <?php include("LibraryJs.php"); ?>

</body>

</html>