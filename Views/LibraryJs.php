
<!-- javascripts -->
<script src="<?=URL_PUBLIC?>js/jquery.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-ui-1.10.4.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?=URL_PUBLIC?>js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- bootstrap -->
<script src="<?=URL_PUBLIC?>js/bootstrap.min.js"></script>
<!-- nice scroll -->
<script src="<?=URL_PUBLIC?>js/jquery.scrollTo.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.nicescroll.js" type="text/javascript"></script>
<!-- charts scripts -->

<script src="<?=URL_PUBLIC?>js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?=URL_PUBLIC?>js/owl.carousel.js"></script>

<!--script for this page only-->
<!-----------------------------------<script src="js/calendar-custom.js"></script>----->
<script src="<?=URL_PUBLIC?>js/jquery.rateit.min.js"></script>
<!-- custom select -->
<script src="<?=URL_PUBLIC?>js/jquery.customSelect.min.js"></script>


<!--custome script for all page-->
<script src="<?=URL_PUBLIC?>js/scripts.js"></script>
<!-- custom script for this page-->
<script src="<?=URL_PUBLIC?>js/sparkline-chart.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?=URL_PUBLIC?>js/xcharts.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.autosize.min.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.placeholder.min.js"></script>
<script src="<?=URL_PUBLIC?>js/gdp-data.js"></script>
<script src="<?=URL_PUBLIC?>js/morris.min.js"></script>
<script src="<?=URL_PUBLIC?>js/sparklines.js"></script>
<script src="<?=URL_PUBLIC?>js/charts.js"></script>
<script src="<?=URL_PUBLIC?>js/jquery.slimscroll.min.js"></script>
<script src="<?=URL_PUBLIC?>js/zabuto_calendar.js"></script>
<script src="<?=URL_PUBLIC?>js/ajax.js"></script>
<script language="JavaScript" type="text/javascript"src="<?=URL_PUBLIC?>js/ajaxPos.js" ></script>



<script type="application/javascript">
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

<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet">
<!-- DataTables JavaScript -->
<script src="<?=URL_PUBLIC?>js/jquery.dataTables.min.js"></script>
<script src="<?=URL_PUBLIC?>js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>


<!--<script src="--><?php //echo $urlViews; ?><!--/js/print/jquery-1.4.4.min.js" type="text/javascript"></script>-->
<script src="<?=URL_PUBLIC?>/js/print/jquery.printPage.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".btnPrint").printPage();
    });
</script>

