<?php
// UsuarioDetalleModal.php
?>
<div class="modal fade" id="usuarioDetalleModal" tabindex="-1" role="dialog" aria-labelledby="usuarioDetalleModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="usuarioDetalleModalLabel">Información del Vendedor</h4>
            </div>
            <div class="modal-body">
                <div class="text-center py-5" id="loadingUsuario">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p>Cargando información del vendedor...</p>
                </div>
                <div id="usuarioContent" style="display: none;">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="usuarioFoto" src="" class="img-thumbnail" style="width: 150px; height: 150px;">
                            <h4 id="usuarioNombre" class="mt-2"></h4>
                            <p id="usuarioLogin" class="text-muted"></p>
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-info-circle"></i> Información de contacto
                                </div>
                                <div class="panel-body">
                                    <p><strong><i class="fa fa-envelope"></i> Email:</strong> <span id="usuarioEmail"></span></p>
                                    <p><strong><i class="fa fa-phone"></i> Teléfono:</strong> <span id="usuarioTelefono"></span></p>
                                    <p><strong><i class="fa fa-map-marker"></i> Dirección:</strong> <span id="usuarioDireccion"></span></p>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-share-alt"></i> Redes sociales
                                </div>
                                <div class="panel-body" id="usuarioRedes">
                                    <!-- Las redes sociales se cargarán aquí -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>