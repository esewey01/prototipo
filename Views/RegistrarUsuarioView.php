<!DOCTYPE html>
<html lang="es">

<?php include('../Model/Conexion.php'); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UPIICSA FOOD</title>
    <link href="/Prototipo/Views/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Prototipo/Views/css/bootstrap-theme.css" rel="stylesheet">
    <link href="/Prototipo/Views/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="/Prototipo/Views/css/font-awesome.css" rel="stylesheet" />
    <link href="/Prototipo/Views/css/style.css" rel="stylesheet">
    <link href="/Prototipo/Views/css/style-responsive.css" rel="stylesheet" />

</head>

<?php
session_start();
if (isset($_SESSION['registration_messages'])) {
    echo '<div class="alert alert-'.$_SESSION['registration_messages']['type'].'">';
    echo $_SESSION['registration_messages']['text'];
    echo '</div>';
    unset($_SESSION['registration_messages']);
}
?>
<div class="container">
    <form class="login-form" action="/Prototipo/Controller/RegistrarUsuarioController.php" method="POST" enctype="multipart/form-data" id="registroForm">
        <div class="login-wrap">
            <h2 class="text-center">Registro de Usuario</h2>

            <div class="input-group">
                <span class="input-group-addon"><i class="icon_id_alt"></i></span>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required value="<?= htmlspecialchars($valores['nombre'] ?? '') ?>">
            </div>

            <div class="input-group">
                <span class="input-group-addon"><i class="icon_id"></i></span>
                <input type="number" min="200000000" max="3000000000" step="1" name="login" class="form-control" placeholder="Boleta" required value="<?= htmlspecialchars($valores['login'] ?? '') ?>">
            </div>

            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required value="<?= htmlspecialchars($valores['password'] ?? '') ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password2" class="form-control" placeholder="Confirmar Contraseña" required value="<?= htmlspecialchars($valores['password2'] ?? '') ?>">
            </div>

            <div id="telefono-status" class="hidden"></div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_phone"></i></span>
                <input type="tel" name="telefono" id="telefono" class="form-control" placeholder="Telefono" required value="<?= htmlspecialchars($valores['telefono'] ?? '') ?>">
                <span class="input-group-btn">
                    <button type="button" id="btn-verificar-telefono" class="btn btn-primary">Verificar</button>
                </span>
            </div>


            <div class="input-group">
                <span class="input-group-addon"><i class="icon_image"></i></span>
                <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png" class="form-control" placeholder="Imagen">
            </div>
            <small>(Máximo 2MB, formatos: JPG, PNG)</small>

            <button class="btn btn-primary btn-lg btn-block" type="submit" id="btn-registrarse">Registrarse</button>
            <button class="btn btn-primary btn-lg btn-block" type="button" onclick="location.href='LoginView.php'">Volver</button>
        </div>
    </form>
</div>

<script>
    const btnVerificar = document.getElementById('btn-verificar-telefono');
    const telefonoInput = document.getElementById('telefono');
    const statusElement = document.getElementById('telefono-status');
    const btnRegistrarse = document.getElementById('btn-registrarse');

    let telefonoValido = false;

    btnVerificar.addEventListener('click', function() {
        const telefono = telefonoInput.value;

        btnRegistrarse.disabled = true; // Por seguridad, lo deshabilitamos cada vez

        if (!telefono.match(/^\d{10}$/)) {
            statusElement.textContent = '✗ El teléfono debe tener 10 dígitos';
            statusElement.className = 'text-danger';
            statusElement.classList.remove('hidden');
            telefonoValido = false;
            return;
        }

        statusElement.textContent = '⏳ Verificando...';
        statusElement.className = 'text-info';
        statusElement.classList.remove('hidden');

        fetch('/Prototipo/Controller/VerificarTelefonoController.php?telefono=' + telefono)
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    statusElement.textContent = '✓ Teléfono válido';
                    statusElement.className = 'text-success';
                    telefonoValido = true;
                    btnRegistrarse.disabled = false;
                } else {
                    statusElement.textContent = '✗ Teléfono no válido';
                    statusElement.className = 'text-danger';
                    telefonoValido = false;
                    btnRegistrarse.disabled = true;
                }
                statusElement.classList.remove('hidden');
            })
            .catch(error => {
                statusElement.textContent = '✗ Error al verificar el teléfono';
                statusElement.className = 'text-danger';
                statusElement.classList.remove('hidden');
                telefonoValido = false;
                btnRegistrarse.disabled = true;
            });
    });

    // Opcional: si el usuario edita el teléfono, deshabilitar registro
    telefonoInput.addEventListener('input', () => {
        telefonoValido = false;
        btnRegistrarse.disabled = true;
        statusElement.classList.add('hidden');
    });
</script>

</body>

</html>