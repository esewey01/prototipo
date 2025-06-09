<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - UPIICSA FOOD</title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body class="contenedor_login">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Restablecer Contraseña</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (isset($_SESSION['mensaje'])): ?>
                            <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?>">
                                <?= $_SESSION['mensaje'] ?>
                            </div>
                            <?php 
                            unset($_SESSION['mensaje']);
                            unset($_SESSION['alerta']);
                        endif; ?>
                        
                        <?php if (isset($_GET['token'])): ?>
                            <form action="Controller/PasswordRecoveryController.php" method="POST">
                                <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
                                <div class="form-group">
                                    <label for="newPassword">Nueva Contraseña</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <button type="submit" class="btn btn-primary" name="resetPassword">Cambiar Contraseña</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                No se proporcionó un token válido. Por favor, solicita un nuevo enlace de recuperación.
                            </div>
                            <a href="LoginView.php" class="btn btn-default">Volver al login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>