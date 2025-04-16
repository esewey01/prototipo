
<div id="addUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-validate form-horizontal" name="form2" action="Registros.php" method="POST" enctype="multipart/form-data">
        <input name="usuarioLogin" value="<?php echo $usuario; ?>" type="hidden">
        <input name="passwordLogin" value="<?php echo $password; ?>" type="hidden">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel" align="center">Registrar Nuevo Usuario</h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <section class="panel">
                                <div class="panel-heading">Imagen del Usuario</div>
                                <div class="panel-body">
                                <?php include(__DIR__ . '/../UploadViewImageCreate.php'); ?>
                                
                                    
                                </div>
                            </section>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre" class="control-label col-lg-4">Nombre:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="nombre" name="nombre" minlength="5" type="text" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tipo" class="control-label col-lg-4">Tipo:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="tipo" required>
                                        <option value="">Seleccione un tipo</option>
                                        <option value="1">ADMINISTRADOR</option>
                                        <option value="2">VENDEDOR</option>
                                        <option value="3">CLIENTE</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="telefono" class="control-label col-lg-4">Teléfono:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="telefono" name="telefono" type="tel">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="login" class="control-label col-lg-4">Login:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="login" name="login" minlength="5" type="text" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="control-label col-lg-4">Password:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="password" name="password" minlength="5" type="password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                        <strong>Cerrar</strong>
                    </button>
                    <button name="nuevo_usuario" type="submit" class="btn btn-primary">
                        <strong>Registrar</strong>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>