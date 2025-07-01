<!-- jQuery (solo UNA versión) -->
<script src="<?=URL_PUBLIC?>js/jquery-1.12.4.min.js"></script>

<!-- jQuery Migrate para compatibilidad -->
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>

<!-- jQuery UI -->
<script src="<?=URL_PUBLIC?>js/jquery-ui-1.12.1.min.js"></script>

<!-- Bootstrap -->
<script src="<?=URL_PUBLIC?>js/bootstrap.min.js"></script>

<!-- Otros plugins (ordenados según dependencias) -->
<script src="<?=URL_PUBLIC?>js/jquery.scrollTo.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.nicescroll.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.sparkline.js"></script>
<script src="<?=URL_PUBLIC?>js/owl.carousel.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.rateit.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.customSelect.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.autosize.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.placeholder.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.slimscroll.min.js"></script>

<!-- DataTables -->
<script src="<?=URL_PUBLIC?>js/jquery.dataTables.min.js"></script>
<script src="<?=URL_PUBLIC?>js/dataTables.bootstrap.min.js"></script>

<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Scripts personalizados -->
<script src="<?=URL_PUBLIC?>js/scripts.js"></script>
<script src="<?=URL_PUBLIC?>js/sparkline-chart.js"></script>
<script src="<?=URL_PUBLIC?>js/charts.js"></script>
<script src="<?=URL_PUBLIC?>js/ajax.js"></script>
<script src="<?=URL_PUBLIC?>js/ajaxPos.js"></script>

<!-- Zabuto Calendar -->
<script src="<?=URL_PUBLIC?>js/zabuto_calendar.js"></script>
<script>
    $(document).ready(function () {
        $("#my-calendar").zabuto_calendar({
            language: "es",
            today: true,
            nav_icon: {
                prev: '<i class="fa fa-chevron-circle-left"></i>',
                next: '<i class="fa fa-chevron-circle-right"></i>'
            }
        });
    });
</script>

<!-- DataTables Init -->
<script>
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>

<!-- PrintPage (reemplaza por versión actualizada si es posible) -->
<script src="<?=URL_PUBLIC?>/js/print/jquery.printPage.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".btnPrint").printPage();
    });
</script>