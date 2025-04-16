<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro - UPIICSA FOOD</title>

    <link href="/Prototipo/Views/css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="/Prototipo/Views/css/bootstrap-theme.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="/Prototipo/Views/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="/Prototipo/Views/css/font-awesome.css" rel="stylesheet" />
    <link href="/Prototipo/Views/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="/Prototipo/Views/css/style-responsive.css" rel="stylesheet" />
</head>



<body class="contenedor_login">
    <div class="container">
        <form class="login-form" action="/Prototipo/Controller/CompletarUsuarioController.php"
            method="POST" enctype="multipart/form-data">
            <div class="login-wrap">

                <h2 class="text-center">Completar Registro</h2>

                <div class="input-group">
                <span class="input-group-addon"><i class="icon_id_alt"></i></span>
                <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>

                <div class="input-group">
                <span class="input-group-addon"><i class="icon_phone"></i></span>
                <input type="text" name="telefono" class="form-control" placeholder="Telefono" required>
                </div>

                <div class="input-group">
                <span class="input-group-addon"><i class="icon_image"></i></span>
                <input type="image" name="foto" class="form-control" placeholder="Imagen" required>              
                </div>
                <small>(Máximo 2MB, formatos: JPG, PNG)</small>

                

                <button class="btn btn-primary btn-lg btn-block" type="submit">Completar Registro</button>
            </div>
        </form>
    </div>
</body>

</html>