
<!-- javascripts -->
<script src="<?=URL_VIEWS?>js/jquery.js"></script>
<script src="<?=URL_VIEWS?>js/jquery-ui-1.10.4.min.js"></script>
<script src="<?=URL_VIEWS?>js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?=URL_VIEWS?>js/jquery-ui-1.9.2.custom.min.js"></script>
<!-- bootstrap -->
<script src="<?=URL_VIEWS?>js/bootstrap.min.js"></script>
<!-- nice scroll -->
<script src="<?=URL_VIEWS?>js/jquery.scrollTo.min.js"></script>
<script src="<?=URL_VIEWS?>js/jquery.nicescroll.js" type="text/javascript"></script>
<!-- charts scripts -->

<script src="<?=URL_VIEWS?>js/jquery.sparkline.js" type="text/javascript"></script>
<script src="<?=URL_VIEWS?>js/owl.carousel.js"></script>

<!--script for this page only-->
<!-----------------------------------<script src="js/calendar-custom.js"></script>----->
<script src="<?=URL_VIEWS?>js/jquery.rateit.min.js"></script>
<!-- custom select -->
<script src="<?=URL_VIEWS?>js/jquery.customSelect.min.js"></script>


<!--custome script for all page-->
<script src="<?=URL_VIEWS?>js/scripts.js"></script>
<!-- custom script for this page-->
<script src="<?=URL_VIEWS?>js/sparkline-chart.js"></script>
<script src="<?=URL_VIEWS?>js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=URL_VIEWS?>js/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?=URL_VIEWS?>js/xcharts.min.js"></script>
<script src="<?=URL_VIEWS?>js/jquery.autosize.min.js"></script>
<script src="<?=URL_VIEWS?>js/jquery.placeholder.min.js"></script>
<script src="<?=URL_VIEWS?>js/gdp-data.js"></script>
<script src="<?=URL_VIEWS?>js/morris.min.js"></script>
<script src="<?=URL_VIEWS?>js/sparklines.js"></script>
<script src="<?=URL_VIEWS?>js/charts.js"></script>
<script src="<?=URL_VIEWS?>js/jquery.slimscroll.min.js"></script>
<script src="<?=URL_VIEWS?>js/zabuto_calendar.js"></script>
<script src="<?=URL_VIEWS?>js/ajax.js"></script>
<script language="JavaScript" type="text/javascript"src="<?=URL_VIEWS?>js/ajaxPos.js" ></script>



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
<script src="<?=URL_VIEWS?>js/jquery.dataTables.min.js"></script>
<script src="<?=URL_VIEWS?>js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>


<!--<script src="--><?php //echo $urlViews; ?><!--/js/print/jquery-1.4.4.min.js" type="text/javascript"></script>-->
<script src="<?=URL_VIEWS?>/js/print/jquery.printPage.js" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        $(".btnPrint").printPage();
    });
</script>

