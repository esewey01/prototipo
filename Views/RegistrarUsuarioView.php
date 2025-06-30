<?php
session_start();
if (isset($_SESSION['registration_messages'])) {
    echo '<div class="alert alert-' . $_SESSION['registration_messages']['type'] . '">';
    echo $_SESSION['registration_messages']['text'];
    echo '</div>';
    unset($_SESSION['registration_messages']);
}
?>
<!DOCTYPE html>
<html lang="es">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - UPIICSA FOOD</title>

    <link href="../public/../public/css/bootstrap.min.css" rel="stylesheet">
    <link href="../public/css/bootstrap-theme.css" rel="stylesheet">
    <link href="../public/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="../public/css/font-awesome.css" rel="stylesheet" />
    <link href="../public/css/style.css" rel="stylesheet">
    <link href="../public/css/style-responsive.css" rel="stylesheet" />
</head>


<div class="container">
    <form class="login-form" action="../Controller/RegistrarUsuarioController.php" method="POST" enctype="multipart/form-data" id="registroForm">
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

            <!-- Mostrar reglas de contraseña -->
            <div id="password-rules" class="alert alert-info">
                <strong>La contraseña debe contener:</strong>
                <ul>
                    <li id="rule-length"><span class="text-danger">✗</span> Mínimo 8 caracteres</li>
                    <li id="rule-uppercase"><span class="text-danger">✗</span> Una letra mayúscula</li>
                    <li id="rule-lowercase"><span class="text-danger">✗</span> Una letra minúscula</li>
                    <li id="rule-number"><span class="text-danger">✗</span> Un número</li>
                    <li id="rule-special"><span class="text-danger">✗</span> Un carácter especial</li>
                    <li id="rule-passwords"><span class="text-danger">✗</span> Contraseñas iguales</li>
                </ul>

                <div class="mt-3">
                    <strong>Correo electrónico:</strong>
                    <div id="email-status" style="display: inline;" class="hidden ml-2"></div>
                </div>

                <div class="mt-2">
                    <strong>Teléfono:</strong>
                    <div id="telefono-status" style="display: inline;" class="hidden ml-2"></div>
                </div>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required value="<?= htmlspecialchars($valores['password'] ?? '') ?>">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password2" class="form-control" placeholder="Confirmar Contraseña" required value="<?= htmlspecialchars($valores['password2'] ?? '') ?>">
            </div>

            <!-- Después del campo de teléfono y antes del campo de foto -->
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_mail"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required value="<?= htmlspecialchars($valores['email'] ?? '') ?>">
            </div>


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

            <button class="btn btn-primary btn-lg btn-block" type="submit" id="btn-registrarse" disabled>Registrarse</button>
            <button class="btn btn-primary btn-lg btn-block" type="button" onclick="location.href='../index.php'">Volver</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del formulario
        const passwordInput = document.querySelector('input[name="password"]');
        const password2Input = document.querySelector('input[name="password2"]');
        const telefonoInput = document.getElementById('telefono');
        const btnVerificar = document.getElementById('btn-verificar-telefono');
        const statusElement = document.getElementById('telefono-status');
        const btnRegistrarse = document.getElementById('btn-registrarse');
        const emailElement = document.getElementById('email-status');
        const emailInput = document.querySelector('input[name="email"]');

        // Elementos de reglas de contraseña
        const ruleLength = document.getElementById('rule-length');
        const ruleUppercase = document.getElementById('rule-uppercase');
        const ruleLowercase = document.getElementById('rule-lowercase');
        const ruleNumber = document.getElementById('rule-number');
        const ruleSpecial = document.getElementById('rule-special');
        const rulePasswords = document.getElementById('rule-passwords');



        // Estado de validación
        let telefonoValido = false;
        let passwordValido = false;
        let passwordsCoinciden = false;

        // Botón deshabilitado por defecto
        btnRegistrarse.disabled = true;

        // Función para verificar fortaleza de contraseña
        function isPasswordValid(password) {
            return {
                minLength: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[^a-zA-Z0-9]/.test(password)
            };
        }

        // Actualiza las reglas visuales de la contraseña
        function updatePasswordRules() {
            const password = passwordInput.value;
            const rules = isPasswordValid(password);

            // Actualizar indicadores visuales
            ruleLength.innerHTML = `<span class="${rules.minLength ? 'text-success">✓' : 'text-danger">✗'}</span> Mínimo 8 caracteres`;
            ruleUppercase.innerHTML = `<span class="${rules.uppercase ? 'text-success">✓' : 'text-danger">✗'}</span> Una letra mayúscula`;
            ruleLowercase.innerHTML = `<span class="${rules.lowercase ? 'text-success">✓' : 'text-danger">✗'}</span> Una letra minúscula`;
            ruleNumber.innerHTML = `<span class="${rules.number ? 'text-success">✓' : 'text-danger">✗'}</span> Un número`;
            ruleSpecial.innerHTML = `<span class="${rules.special ? 'text-success">✓' : 'text-danger">✗'}</span> Un carácter especial`;

            // Validar si todas las reglas se cumplen
            passwordValido = Object.values(rules).every(rule => rule);
            validatePasswordsMatch();
            checkFormValidity();
        }




        // Valida si las contraseñas coinciden
        function validatePasswordsMatch() {
            const password = passwordInput.value;
            const password2 = password2Input.value;

            if (password === '' || password2 === '') {
                rulePasswords.innerHTML = `<span class="text-danger">✗</span> Contraseñas iguales`;
                passwordsCoinciden = false;
                return;
            }

            if (password !== password2) {
                rulePasswords.innerHTML = `<span class="text-danger">✗</span> Contraseñas iguales`;
                passwordsCoinciden = false;
            } else {
                rulePasswords.innerHTML = `<span class="text-success">✓</span> Contraseñas iguales`;
                passwordsCoinciden = true;
            }

            checkFormValidity();
        }

        // Función para validar email
        // Modifica la función validateEmail para incluir verificación de disponibilidad
        async function validateEmail() {
            const email = emailInput.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!email) {
                emailElement.textContent = '';
                emailElement.classList.add('hidden');
                return false;
            }

            if (!emailRegex.test(email)) {
                emailElement.textContent = '✗ Formato incorrecto';
                emailElement.className = 'text-danger';
                emailElement.classList.remove('hidden');
                return false;
            }

            // Verificar disponibilidad del email
            emailElement.textContent = '⏳ Verificando...';
            emailElement.className = 'text-info';
            emailElement.classList.remove('hidden');

            try {
                const response = await fetch(`../Controller/VerificarEmailController.php?email=${encodeURIComponent(email)}`);
                const data = await response.json();

                if (data.available) {
                    emailElement.textContent = '✓ Correo disponible';
                    emailElement.className = 'text-success';
                    return true;
                } else {
                    emailElement.textContent = '✗ Correo ya registrado';
                    emailElement.className = 'text-danger';
                    return false;
                }
            } catch (error) {
                emailElement.textContent = '✗ Error al verificar';
                emailElement.className = 'text-danger';
                return false;
            }
        }

        // Verifica el teléfono vía AJAX
        async function verificarTelefono() {
            const telefono = telefonoInput.value;
            btnRegistrarse.disabled = true;

            if (!telefono.match(/^\d{10}$/)) {
                statusElement.textContent = '✗ El teléfono debe tener 10 dígitos';
                statusElement.className = 'text-danger';
                statusElement.classList.remove('hidden');
                telefonoValido = false;
                checkFormValidity();
                return;
            }

            statusElement.textContent = '⏳ Verificando...';
            statusElement.className = 'text-info';
            statusElement.classList.remove('hidden');

            try {
                const response = await fetch('../Controller/VerificarTelefonoController.php?telefono=' + telefono);
                const data = await response.json();

                if (data.valid) {
                    statusElement.textContent = '✓ Teléfono válido';
                    statusElement.className = 'text-success';
                    telefonoValido = true;
                } else {
                    statusElement.textContent = '✗ Teléfono no válido';
                    statusElement.className = 'text-danger';
                    telefonoValido = false;
                }
            } catch (error) {
                statusElement.textContent = '✗ Error al verificar el teléfono';
                statusElement.className = 'text-danger';
                telefonoValido = false;
            }

            statusElement.classList.remove('hidden');
            checkFormValidity();
        }

        // Verifica si el formulario puede enviarse
        function checkFormValidity() {
            btnRegistrarse.disabled = !(passwordValido && passwordsCoinciden && telefonoValido);
        }

        // Event listeners
        passwordInput.addEventListener('input', updatePasswordRules);
        password2Input.addEventListener('input', validatePasswordsMatch);
        btnVerificar.addEventListener('click', verificarTelefono);


        // Agrega esto con los otros event listeners
        emailInput.addEventListener('input', async () => {
            await validateEmail();
            checkFormValidity();
        });
        // Si el usuario edita el teléfono, invalidar la verificación
        telefonoInput.addEventListener('input', () => {
            telefonoValido = false;
            statusElement.classList.add('hidden');
            checkFormValidity();
        });

        // Validación inicial si hay valores cargados
        if (passwordInput.value) updatePasswordRules();
        if (password2Input.value) validatePasswordsMatch();
    });
</script>


</body>

</html>