<!--MENU PARA EL ADMINSTRADOR -->
<aside>
    <div id="sidebar" class="nav-collapse">
        <ul class="sidebar-menu" style="background-color: #4e4e4e; ">
        <?php
        foreach ($_SESSION['usuario']['menu'] as $menu) {
            // Filtrar por acceso si es necesario (A = Acceso permitido)
            if ($menu['acceso'] === 'A') {
                echo "<li class='menu-item' style='background-color:".$menu['color'].";'>";
                echo "<a class='menu-link' href='".$menu['ubicacion']."'>";
                echo "<i class='".$menu['icono']."'></i>";
                echo "<span style='color: black;'>".$menu['opcion']."</span>";
                echo "</a>";
                echo "</li>";   
            }
        }
        ?> 
        </ul>
    </div>
</aside>