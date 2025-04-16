<!--MENU EXCLUSIVO PARA EL USUARIO-->
<div class="top-nav notification-row">
    <ul class="nav pull-right top-menu">
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span class="profile-ava">

                <img src="<?PHP echo $urlViews .$_SESSION['usuario']['foto'];?>" alt="Usuario" height="20" width="20">

                    
                </span>
                <span class="username"> <?PHP echo $_SESSION['usuario']['nombre']. ' '.$_SESSION['usuario']['apellido']; ?> </span>
                <b class="caret"></b>
            </a>
            <?PHP include ("MenuOpciones.php"); ?>
        </li>

    </ul>
</div>

