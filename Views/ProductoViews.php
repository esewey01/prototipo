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
                            <i class="fa fa-inbox"></i><a href="Producto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Producto</a>
                        </li>
                        <li>
                            <i class="fa fa-plus"></i><a href="TipoProducto.php?usuario=<?php echo $usuario; ?>&password=<?php echo $password; ?>">Registrar Tipo Producto</a>
                        </li>
                    </ol>
                </div>
            </div>



            <!-- ELEMENTOS -->
            <header class="panel-heading">LISTA DE PRODUCTOS CARGADOS AL SISTEMA</header>
            <header class="panel heading">
                <div class="panel-body">
                    <!--BOTONES-->
                    <div align="right">

                        <a href="ReporteProductosPdf.php?productos=productos" target="_blank"
                            class="btn btn-danger tooltips"><i
                                class="fa fa-rotate-right"></i> EXPORTAR PDF </a>
                        <?php if ($id_rol == 2): ?>
                            <button href="#add" title="" data-placement="top" data-toggle="modal"
                                class="btn btn-primary tooltips" type="button" data-original-title="Nuevo Producto">
                                <span class="icon_bag_alt"></span>AGREGAR NUEVO PRODUCTO
                            </button>
                        <?php endif; ?>
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
                                <td> <?PHP echo htmlspecialchars($product['codigo']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['nombre_producto']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['nombre_usuario']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['descripcion']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['nombre_categoria']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['cantidad']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['precio_compra']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['precio_venta']); ?></td>
                                <td> <?PHP echo htmlspecialchars($product['fecha_registro']->format('Y-m-d H:i:s')); ?></td>
                                <td>
                                    <?php if ($id_rol == 2): // Si es vendedor 
                                    ?>
                                        <a href="#editProduct<?php echo $product['id_producto']; ?>"
                                            class="btn btn-success" data-toggle="modal">
                                            <i class="icon_check_alt2"></i>
                                        </a>
                                        <a href="RegistroProducto.php?idborrar=<?php echo $product['id_producto']; ?>&usuarioLogin=<?php echo urlencode($usuario); ?>&passwordLogin=<?php echo urlencode($password); ?>"
                                            role="button" class="btn btn-danger"
                                            onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                            <i class="icon_close_alt2"></i>
                                        </a>
                                    <?php else: // Para administradores 
                                    ?>
                                        <!-- Solo vista para admin -->
                                        <button class="btn btn-danger btn-sm"
                                            onclick="cargarDatosReporte(
                                                '<?= $product['id_producto'] ?>',
                                                '<?= htmlspecialchars($product['nombre_producto']) ?>',
                                                '<?= $product['id_usuario'] ?>',
                                                '<?= htmlspecialchars($product['nombre_usuario']) ?>'
                                            )">
                                            Reportar
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <!-- Modal de edición -->
                            <div id="editProduct<?php echo $product['id_producto']; ?>" class="modal fade">
                                <form class="form-validate form-horizontal" name="form2" enctype="multipart/form-data"
                                    action="RegistroProducto.php" method="POST">
                                    <!-- Campos ocultos necesarios -->
                                    <input type="hidden" name="id_producto" value="<?php echo $product['id_producto']; ?>">
                                    <input type="hidden" name="imagen" value="<?php echo htmlspecialchars($product['imagen']); ?>">
                                    <input type="hidden" name="usuarioLogin" value="<?php echo htmlspecialchars($usuario); ?>">
                                    <input type="hidden" name="passwordLogin" value="<?php echo htmlspecialchars($password); ?>">

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
                                                                    <select class="form-control input-lg m-bot15" id="id_categoria" name="id_categoria">
                                                                        <option value="">Seleccionar Categoría</option>
                                                                        <?php foreach ($categorias as $categoria): ?>
                                                                            <option value="<?php echo $categoria['id_categoria']; ?>"
                                                                                <?php echo ($categoria['id_categoria'] == $product['id_categoria']) ? 'selected="selected"' : ''; ?>>
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
                                                                <label class="col-sm-2 control-label">Nombre:</label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="nombre_producto" name="nombre_producto"
                                                                        type="text"
                                                                        value="<?php echo $product['nombre_producto']; ?>" />
                                                                </div>

                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-sm-2 control-label">Descripcion:</label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="descripcion" name="descripcion"
                                                                        type="text"
                                                                        value="<?php echo $product['descripcion']; ?>" />
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
                                                                        id="precio_venta" name="precio_venta"
                                                                        placeholder="0.00" type="text"
                                                                        value="<?php echo $product['precio_venta']; ?>" />
                                                                </div>

                                                            </div>


                                                            <div class="form-group">
                                                                <label for="ppublico" class="control-label col-lg-2">Precio
                                                                    de
                                                                    compra:</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        id="precio_compra"
                                                                        name="precio_compra" placeholder="0.00"
                                                                        type="text"
                                                                        value="<?php echo $product['precio_compra']; ?>" />
                                                                </div>
                                                                <label for="fecha_registro"
                                                                    class="control-label col-lg-2">Fecha:</label>
                                                                <div class="col-lg-4">
                                                                    <input class="form-control input-lg m-bot15"
                                                                        type="date"
                                                                        readonly name="fecha_registro"
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

            <!--MODAL PARA REPORTES-->
            <!-- Modal para Reportar -->
            <div class="modal fade" id="reportarModal" tabindex="-1" role="dialog" aria-labelledby="reportarModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="RegistroReporteController.php" method="POST">
                            <input type="hidden" name="id_producto" id="reporte_id_producto">
                            <input type="hidden" name="id_usuario_reportado" id="reporte_id_usuario">
                            <input type="hidden" name="id_administrador" value="<?= $_SESSION['usuario']['id_usuario'] ?>">

                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="reportarModalLabel">Reportar Producto</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Producto:</label>
                                    <p class="form-control-static" id="reporte_nombre_producto"></p>
                                </div>
                                <div class="form-group">
                                    <label>Vendedor:</label>
                                    <p class="form-control-static" id="reporte_nombre_vendedor"></p>
                                </div>
                                <div class="form-group">
                                    <label for="motivo">Motivo del reporte:</label>
                                    <select class="form-control" name="motivo" required>
                                        <option value="">Seleccione un motivo</option>
                                        <option value="Contenido inapropiado">Contenido inapropiado</option>
                                        <option value="Información falsa">Información falsa</option>
                                        <option value="Precio incorrecto">Precio incorrecto</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="accion_tomada">Acción a tomar:</label>
                                    <select class="form-control" name="accion_tomada" required>
                                        <option value="">Seleccione una acción</option>
                                        <option value="Advertencia">Enviar advertencia</option>
                                        <option value="Desactivar producto">Desactivar producto</option>
                                        <option value="Suspender cuenta">Suspender cuenta temporalmente</option>
                                        <option value="Banear cuenta">Banear cuenta permanentemente</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comentarios">Comentarios adicionales:</label>
                                    <textarea class="form-control" name="comentarios" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Enviar Reporte</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                // Función para cargar datos en el modal
                function cargarDatosReporte(idProducto, nombreProducto, idUsuario, nombreUsuario) {
                    $('#reporte_id_producto').val(idProducto);
                    $('#reporte_id_usuario').val(idUsuario);
                    $('#reporte_nombre_producto').text(nombreProducto);
                    $('#reporte_nombre_vendedor').text(nombreUsuario);
                    $('#reportarModal').modal('show');
                }
            </script>






        </section>
    </section>





    <?php include("LibraryJs.php"); ?>

</body>

</html>