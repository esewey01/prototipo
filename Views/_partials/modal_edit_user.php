<div id="editUser<?= $user['id_usuario'] ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-validate form-horizontal" name="form2" action="Registros.php" method="post" enctype="multipart/form-data">
        <input name="usuarioLogin" value="<?php echo $usuario; ?>" type="hidden">
        <input name="passwordLogin" value="<?php echo $password; ?>" type="hidden">
        <input type="hidden" name="idUsuario" value="<?php echo $user['id_usuario']; ?>">
        <input type="hidden" name="imagen" value="<?php echo $user['foto']; ?>">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="myModalLabel" align="center">Editar Usuario: <?= htmlspecialchars($user['nombre']) ?></h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="<?= URL_VIEWS . $user['foto'] ?>"
                                    width="200" height="200"
                                    class="img-thumbnail"
                                    onerror="this.src='<?= URL_VIEWS ?>fotoproducto/user.png'">
                            </div>
                            <div class="form-group">
                                <label>Cambiar imagen:</label>

                                <?php include(__DIR__ . '/../UploadViewImageEdit.php'); ?>
                                
                                
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre" class="control-label col-lg-4">Nombre:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="tipo" class="control-label col-lg-4">Tipo:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="tipo">
                                        <?php foreach ($tipos as $id => $tipo): ?>
                                            <option value="<?= $id ?>" <?= ($id == $user['id_tipo']) ? 'selected' : '' ?>>
                                                <?= $tipo['nombre'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="telefono" class="control-label col-lg-4">Teléfono:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="tel" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="login" class="control-label col-lg-4">Login:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="control-label col-lg-4">Password:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" type="password" name="password" placeholder="Dejar en blanco para no cambiar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">
                        <strong>Cancelar</strong>
                    </button>
                    <button name="update_usuario" type="submit" class="btn btn-primary">
                        <strong>Guardar Cambios</strong>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>