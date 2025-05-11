<?php
session_start();
//FUNCION DE ALERTA DE MENSAJES-
if (isset($_SESSION['mensaje'])): ?>
    <div class="alert <?= $_SESSION['alerta'] ?? 'alert-info' ?> alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <strong><?= $_SESSION['mensaje'] . ': ' . $_SESSION['usuario']['rol']['nombre_rol'] ?></strong>
    </div>
<?php
    unset($_SESSION['mensaje']);
    unset($_SESSION['alerta']);
endif; ?>
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <!---PROYECTO DE INGENIERÍA DE PRUEBAS 
    PROFESOR: OSKAR
    EQUIPO #3
    - 5NV71 --->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PROYECTO DE INGENIERÍA DE PRUEBAS">

    <title>UPIICSA FOOD - Sistema de Compra y Venta</title>
    


    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/bootstrap-theme.css" rel="stylesheet">
    <link href="public/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="public/css/font-awesome.css" rel="stylesheet" />
    <link href="public/css/style.css" rel="stylesheet">
    <link href="public/css/style-responsive.css" rel="stylesheet" />

</head>



<body class="contenedor_login">

    <div class="container">

        <form class="login-form" action="Controller/AccessUsers.php" method="POST">
            <div class="login-wrap">

                <p class="login-img"><i class="icon_like"></i></p>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_profile"></i></span>
                    <input type="text" name="login" class="form-control" placeholder="Nombre de Usuario" autofocus required>
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <label class="checkbox">
                    <input type="checkbox" value="remember-me"> Recuerdame
                    <span class="pull-right"> <a href="#"> Olvidaste tu Contraseña?</a></span>
                </label>
                <button class="btn btn-primary btn-lg btn-block" type="submit">Iniciar Sesión</button>
                <button class="btn btn-primary btn-lg btn-block" type="button"
                    onclick="location.href='Views/RegistrarUsuarioView.php'">
                    Registrarse
                </button>
            </div>
        </form>
    </div>
</body>

<!--FUNCION DE ALERTA DE MENSAJES-->


</html>