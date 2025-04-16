<div id="addUser" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-validate form-horizontal" name="form2" action="../Controller/AddUserController.php" method="POST" enctype="multipart/form-data">
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
                                    <input id="files" type="file" name="foto_perfil"/>
                                    <output id="list-miniatura"></output>
                                    <small class="text-muted">Formatos permitidos: JPG, PNG (Max. 2MB)</small>
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
                                    <select class="form-control" name="id_rol" required>
                                        <option value="">Seleccione un tipo</option>
                                        <?php
                                        // Obtener roles disponibles desde la base de datos
                                        $con = new Conexion();
                                        $roles = $con->getResults($con->executeQuery("SELECT id_rol, nombre_rol FROM ROLES"));
                                        foreach ($roles as $rol) {
                                            echo '<option value="'.$rol['id_rol'].'">'.$rol['nombre_rol'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="login" class="control-label col-lg-4">Usuario:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="login" name="login" minlength="5" type="text" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="password" class="control-label col-lg-4">Contraseña:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="password" name="password" minlength="5" type="password" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="control-label col-lg-4">Email:</label>
                                <div class="col-lg-8">
                                    <input class="form-control" id="email" name="email" type="email" required>
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

<script>
// Mantén este script para la vista previa de la imagen
document.getElementById('files').addEventListener('change', function(evt) {
    var files = evt.target.files;
    var output = document.getElementById('list-miniatura');
    output.innerHTML = '';
    
    for (var i = 0, f; f = files[i]; i++) {
        if (!f.type.match('image.*')) continue;
        
        var reader = new FileReader();
        reader.onload = (function(theFile) {
            return function(e) {
                var span = document.createElement('span');
                span.innerHTML = ['<img class="thumb" src="', e.target.result, '" title="', 
                                escape(theFile.name), '" style="max-width:100%; max-height:200px;"/><br />'].join('');
                output.appendChild(span);
            };
        })(f);
        reader.readAsDataURL(f);
    }
});
</script>