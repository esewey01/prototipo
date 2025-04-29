<?PHP require('Constants.php'); ?>

<!--MENU EXCLUSIVO PARA EL USUARIO-->
<div class="top-nav notification-row">
    <ul class="nav pull-right top-menu">
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" >
                <span class="profile-ava">

                <img src="<?= URL_VIEWS . (isset($user_data['foto_perfil']) ? $user_data['foto_perfil'] : '') ?>"
                alt="Usuario" height="20" width="20"
                onerror="this.onerror=null; this.src='<?= URL_VIEWS . 'fotoproducto/user.png' ?>'">

                    
                </span>
                <span class="username"> <?PHP echo $_SESSION['usuario']['nombre']. ' '.$_SESSION['usuario']['apellido']; ?> </span>
                <b class="caret"></b>
            </a>
            <?PHP include ("MenuOpciones.php"); ?>
        </li>

    </ul>
</div>

