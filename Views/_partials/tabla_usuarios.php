<div class="panel panel-default">


    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-usuarios">
                <thead>
                    <tr>
                        <th><i class="icon_image"></i> Foto</th>
                        <th><i class="icon_profile"></i> Nombre</th>
                        <th><i class="icon_folder"></i> Tipo</th>
                        <th><i class="icon_number"></i> Teléfono</th>
                        <th><i class="icon_id"></i> Login</th>
                        <th><i class="icon_cog"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Definir tipos basados en roles (ajusta según tu estructura real)
                    $tipos = [
                        'ADMINISTRADOR' => ['estilo' => 'danger', 'icono' => 'fa-shield'],
                        'VENDEDOR' => ['estilo' => 'danger', 'icono' => 'fa-user-tie'],
                        'CLIENTE' => ['estilo' => 'label-info', 'icono' => 'fa-user'],
                        'SUPER_USER' => ['estilo' => 'primary', 'icono' => 'fa-crown']
                    ];

                    if (!empty($usuarios)): ?>
                        <?php foreach ($usuarios as $user):
                            // Obtener el rol del usuario (asumiendo que viene en $user['tipo'] o similar)
                            $rol = $user['tipo'] ?? $user['nombre_rol'];
                            $tipo = $tipos[$rol] ?? ['estilo' => 'label-default', 'icono' => 'fa-user'];

                        ?>
                            <tr>
                                <td>
                                    <img src="<?= URL_VIEWS . ($user['foto_perfil'] ?? 'fotoproducto/user.png') ?>"
                                        width="40" height="40"
                                        class="img-circle"
                                        onerror="this.src='<?= URL_VIEWS ?>fotoproducto/user.png'">
                                </td>

                                <td><?= htmlspecialchars($user['nombre'] ?? '') ?></td>

                                <td>

                                    <span class="label label-danger">
                                        <i class="fa <?= $tipo['icono'] ?>"></i>
                                        <?= $rol ?>
                                    </span>
                                </td>

                                <td><?= htmlspecialchars($user['telefono'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['login'] ?? '') ?></td>

                                <td>
                                    <div class="btn-group">
                                    

                                            <!--BOTON PARA REPORTAR AL USUARIO-->
                                            <button class="btn btn-warning btn-sm"
                                                onclick="cargarDatosReporteUsuario(
                                                '<?= $user['id_usuario'] ?>',
                                                '<?= htmlspecialchars($user['nombre']) ?>',
                                                '<?= $rol ?>'
                                                 )"
                                                title="Reportar usuario">
                                                <i class="fa fa-flag"></i>
                                            </button>

                                            <!-- Botón Eliminar -->
                                            <a href="Registros.php?idborrar=<?= $user['id_usuario'] ?? '' ?>&usuarioLogin=<?= $usuario ?>&passwordLogin=<?= $password ?>"
                                                role="button"
                                                class="btn btn-danger btn-sm"
                                                title="Eliminar"
                                                onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                                <i class="fa fa-trash-o"></i>
                                            </a>



                                            <!-- Botón Ver Detalles -->
                                            <a href="#viewUser<?= $user['id_usuario'] ?? '' ?>"
                                                role="button"
                                                class="btn btn-info btn-sm"
                                                data-toggle="modal"
                                                title="Ver detalles">
                                                <i class="fa fa-eye">



                                                </i>
                                            </a>
                                     

                                        <?php if ($_SESSION['usuario']['rol']['id_rol'] === 0): ?>
                                            <!-- Botón Editar -->
                                            <a href="#editUser<?= $user['id_usuario'] ?? '' ?>"
                                                role="button"
                                                class="btn btn-success btn-sm"
                                                data-toggle="modal"
                                                title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay <?= $tipo_actual ?>s registrados</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal para Reportar Usuario -->
<div class="modal fade" id="reportarUsuarioModal<?= $user['id_usuario'] ?>" tabindex="-1" role="dialog" aria-labelledby="reportarUsuarioModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="RegistroReporteController.php" method="POST">
                <input type="hidden" name="tipo_reporte" value="USUARIO">
                <input type="hidden" name="id_usuario_reportado" id="reporte_id_usuario">
                <input type="hidden" name="id_administrador" value="<?= $_SESSION['usuario']['id_usuario'] ?>">
                <!-- Para reportes de usuario, id_producto puede ser 0 o NULL -->
                <input type="hidden" name="id_producto" value="0">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="reportarUsuarioModalLabel">Reportar Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Usuario a reportar:</label>
                        <p class="form-control-static" id="reporte_nombre_usuario"></p>
                    </div>
                    <div class="form-group">
                        <label>Tipo de usuario:</label>
                        <p class="form-control-static" id="reporte_tipo_usuario"></p>
                    </div>
                    <div class="form-group">
                        <label for="motivo">Motivo del reporte:</label>
                        <select class="form-control" name="motivo" required>
                            <option value="">Seleccione un motivo</option>
                            <option value="Comportamiento inapropiado">Comportamiento inapropiado</option>
                            <option value="Publicaciones inadecuadas">Publicaciones inadecuadas</option>
                            <option value="Incumplimiento de normas">Incumplimiento de normas</option>
                            <option value="Intento de fraude">Intento de fraude</option>
                            <option value="Spam o publicidad no deseada">Spam o publicidad no deseada</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="accion_tomada">Acción a tomar:</label>
                        <select class="form-control" name="accion_tomada" required>
                            <option value="">Seleccione una acción</option>
                            <option value="Advertencia">Enviar advertencia</option>
                            <option value="Suspender cuenta">Suspender cuenta temporalmente</option>
                            <option value="Banear cuenta">Banear cuenta permanentemente</option>
                            <option value="Eliminar publicaciones">Eliminar publicaciones del usuario</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="comentarios">Comentarios adicionales:</label>
                        <textarea class="form-control" name="comentarios" rows="3" placeholder="Detalles adicionales sobre el reporte..."></textarea>
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
    // Función para cargar datos en el modal de reporte de usuario
    function cargarDatosReporteUsuario(idUsuario, nombreUsuario, tipoUsuario) {
    $('#reporte_id_usuario').val(idUsuario);
    $('#reporte_nombre_usuario').text(nombreUsuario);
    $('#reporte_tipo_usuario').text(tipoUsuario);
    $('#reportarUsuarioModal'+idUsuario).modal('show');
}
</script>