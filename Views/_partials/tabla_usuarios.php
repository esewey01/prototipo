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
                                        <!-- Botón Editar -->
                                        <a href="#editUser<?= $user['id_usuario'] ?? '' ?>" 
                                           role="button" 
                                           class="btn btn-success btn-sm" 
                                           data-toggle="modal"
                                           title="Editar">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        <!-- Botón Eliminar -->
                                        <a href="Registros.php?idborrar=<?= $user['id_usuario'] ?? '' ?>&usuarioLogin=<?= $usuario ?>&passwordLogin=<?= $password ?>"
                                           role="button" 
                                           class="btn btn-danger btn-sm"
                                           title="Eliminar"
                                           onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        
                                        <!-- Botón Ver Detalles -->
                                        <a href="#viewUser<?= $user['id_usuario'] ?? '' ?>" 
                                           role="button" 
                                           class="btn btn-info btn-sm" 
                                           data-toggle="modal"
                                           title="Ver detalles">
                                            <i class="fa fa-eye"></i>
                                        </a>
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