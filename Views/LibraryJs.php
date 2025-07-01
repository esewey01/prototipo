<?php
// LibraryJs.php - Archivo completo corregido

// Verificar si la constante URL_PUBLIC está definida
defined('URL_PUBLIC') or define('URL_PUBLIC', '/public/');
?>

<!-- jQuery con fallback a CDN -->
<script>
    if(typeof jQuery == 'undefined') {
        document.write('<script src="https://code.jquery.com/jquery-1.12.4.min.js"><\/script>');
    }
</script>
<script src="<?= URL_PUBLIC ?>js/jquery-1.12.4.min.js"></script>

<!-- jQuery Migrate para compatibilidad -->
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>

<!-- jQuery UI con fallback -->
<script>
    if(typeof jQuery.ui == 'undefined') {
        document.write('<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"><\/script>');
    }
</script>
<script src="<?= URL_PUBLIC ?>js/jquery-ui.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- Plugins esenciales -->
<script src="<?= URL_PUBLIC ?>js/jquery.scrollTo.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.nicescroll.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.sparkline.js"></script>
<script src="<?= URL_PUBLIC ?>js/owl.carousel.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.rateit.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.customSelect.min.js"></script>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet">
<script src="<?= URL_PUBLIC ?>js/jquery.dataTables.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/dataTables.bootstrap.min.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Otros plugins -->
<script src="<?= URL_PUBLIC ?>js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.autosize.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.placeholder.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/jquery.slimscroll.min.js"></script>
<script src="<?= URL_PUBLIC ?>js/zabuto_calendar.js"></script>

<!-- Scripts personalizados (deben ir al final) -->
<script src="<?= URL_PUBLIC ?>js/scripts.js"></script>
<script src="<?= URL_PUBLIC ?>js/sparkline-chart.js"></script>
<script src="<?= URL_PUBLIC ?>js/charts.js"></script>
<script src="<?= URL_PUBLIC ?>js/ajax.js"></script>
<script src="<?= URL_PUBLIC ?>js/ajaxPos.js"></script>

<!-- PrintPage (versión actualizada) -->
<script src="<?= URL_PUBLIC ?>js/print/jquery.printPage.js"></script>

<!-- Inicializaciones -->
<script>
    // Asegurar que jQuery esté cargado antes de ejecutar
    jQuery(document).ready(function($) {
        // Zabuto Calendar
        $("#my-calendar").zabuto_calendar({
            language: "es",
            today: true,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            }
        });

        // DataTables
        $('#dataTables-example').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
            }
        });

        // PrintPage
        $(".btnPrint").printPage();

        // Tooltips de Bootstrap
        $('[data-toggle="tooltip"]').tooltip();
        
        // Popovers de Bootstrap
        $('[data-toggle="popover"]').popover();
    });
</script>

<!-- Estilos adicionales -->
<style>
    /* Corrección para modales en Bootstrap */
    .modal {
        overflow-y: auto;
    }
    .modal-open {
        overflow: auto;
        padding-right: 0 !important;
    }
</style>