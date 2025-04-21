<!DOCTYPE html>
<html lang="es">
<?php include('Head.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido Cliente</title>
</head>

<body>

    <!--Menu desplegable-->
    <section id="container" class="">

        <header class="header dark-bg">
            <div class="toggle-nav">
                <div class="icon-reorder tooltips" data-original-title="Menú Principal" data-placement="bottom"><i
                        class="icon_menu"></i></div>
            </div>
            <?PHP include("Logo.php") ?>

            <div class="nav search-row" id="top_menu">
                <!--  search form start -->
                <ul class="nav top-menu">
                    <li>
                        <form class="navbar-form">
                            <input class="form-control" placeholder="Search" type="text">
                        </form>
                    </li>
                </ul>
                <!--  search form end -->
            </div>
            <?PHP include("DropDown.php"); ?> <!--MENU DE USUARIO-->
        </header>

        <?PHP include("Menu.php") ?>

    </section>

    <!-- Contenido principal -->
    <section id="main-content">
        <section class="wrapper">
            <div class="container">
                <h1>Bienvenido, <?= $_SESSION['usuario']['nombre'] ?></h1>
                <p>Aquí puedes ver tus compras, puntos y gestionar tu perfil.</p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Mi Perfil</h3>
                            </div>
                            <div class="panel-body text-center">
                                <img src="../fotoproducto/<?= $_SESSION['foto'] ?? 'user.png' ?>"
                                    class="img-circle" width="100" height="100">
                                <h4><?= $_SESSION['usuario']['nombre'] ?></h4>
                                <p>Cliente</p>
                                <a href="Profile.php" class="btn btn-primary">Editar perfil</a>
                            </div>
                        </div>
                    </div> <!-- Aquí puedes añadir más secciones como historial de compras, etc. -->
                </div>
            </div>

        </section>

    </section>



    <?PHP include("LibraryJs.php"); ?>

</body>

</html>