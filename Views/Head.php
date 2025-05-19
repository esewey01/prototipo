<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PROYECTO DE INGENIERÍA DE PRUEBAS">

     <!-- Fix para eventos passive -->
     <script>
    try {
      const isSupported = EventTarget.prototype.addEventListener && Object.defineProperty;
      if (isSupported) {
        let func = EventTarget.prototype.addEventListener;
        EventTarget.prototype.addEventListener = function(type, fn, capture) {
          this.func = func;
          if (typeof capture !== 'boolean') {
            capture = capture || {};
            capture.passive = false;
          }
          this.func(type, fn, capture);
        };
      }
    } catch(e) {}
    </script>

    <title>UPIICSA FOOD - Sistema de Compra y Venta</title>

    <!-- Bootstrap CSS -->
    <link href="<?=URL_PUBLIC?>css/bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="<?=URL_PUBLIC?>css/bootstrap-theme.css" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="<?=URL_PUBLIC?>css/elegant-icons-style.css" rel="stylesheet" />
    <link href="<?=URL_PUBLIC?>css/font-awesome.min.css" rel="stylesheet" />
    <!-- full calendar css-->
    <link href="<?=URL_PUBLIC?>css/assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
    <link href="<?=URL_PUBLIC?>css/assets/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" />
    <!-- easy pie chart-->
    <!-- <link href="assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>-->
    <!-- owl carousel -->
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/owl.carousel.css" type="text/css">
    <link href="<?=URL_PUBLIC?>css/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/fullcalendar.css">
    <link href="<?=URL_PUBLIC?>css/widgets.css" rel="stylesheet">
    <link href="<?=URL_PUBLIC?>css/style.css" rel="stylesheet">
    <link href="<?=URL_PUBLIC?>css/style_pru.css" rel="stylesheet">
    <link href="<?=URL_PUBLIC?>css/style-responsive.css" rel="stylesheet" />
    <link href="<?=URL_PUBLIC?>css/xcharts.min.css" rel=" stylesheet">
    <link href="<?=URL_PUBLIC?>css/jquery-ui-1.10.4.min.css" rel="stylesheet">

    <link href="<?=URL_PUBLIC?>css/anchomodal.css" rel="stylesheet">

    <!-- Subir Imagen -->
    <link href="<?=URL_PUBLIC?>css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
    <!-- Calendario -->
    <link href="<?=URL_PUBLIC?>css/calendario.css" rel="stylesheet">
    <!--    <link href="css/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css" rel="stylesheet">-->
    <link href="<?=URL_PUBLIC?>js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <!-- Ventana modal -->
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/basicModal.min.css">

    <!-- Ventana modal style
    <link rel="stylesheet" type="text/css" href="css/default.css" />        -->
    <link rel="stylesheet" type="text/css" href="<?=URL_PUBLIC?>css/component.css" />

    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/ngDialog.css">
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/ngDialog-theme-default.css">
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/ngDialog-theme-plain.css">
    <link rel="stylesheet" href="<?=URL_PUBLIC?>css/ngDialog-custom-width.css">


    <link href='<?=URL_PUBLIC?>css/adaptive-modal.css' rel='stylesheet' type='text/css'>

    <!-- letra style -->
    <!-- <link rel="stylesheet" href="css/letra.css">-->

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

       <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


</head>