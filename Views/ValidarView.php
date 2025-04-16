<div class="requirements">Escribe tu nombre completo</div>
<!---CASILLA PARA NOMBRE---->
<div class="input-group">
    <span class="input-group-addon"><i class="icon_profile"></i></span>
    <input type="text" name="nombre" class="form-control" placeholder="Nombre completo"
        value="<?php echo isset($_SESSION['datos_anteriores']['nombre']) ? htmlspecialchars($_SESSION['datos_anteriores']['nombre']) : ''; ?>" required>
</div>


<div class="input-group">
    <span class="input-group-addon"><i class="icon_phone"></i></span>
    <input type="text" name="telefono" class="form-control" placeholder="Número Telefónico" required>
</div>

<div class="input-group">
                    <span class="input-group-addon"><i class="icon_image"></i></span>
                    <input type="text" name="foto" class="form-control" placeholder="PENDIENTE">
                </div>