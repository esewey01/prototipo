<?php include('Head.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3><i class="fa fa-check-circle"></i> ¡Pago Completado!</h3>
                </div>
                <div class="panel-body text-center">
                    <i class="fa fa-shopping-bag fa-5x text-success"></i>
                    <h2>Gracias por tu compra</h2>
                    <p>Tu orden #<?= $orden_id ?> ha sido procesada correctamente.</p>
                    <p>Método de pago: <strong>Efectivo</strong></p>
                    
                    <div class="alert alert-info">
                        <h4><i class="fa fa-info-circle"></i> Instrucciones</h4>
                        <p>Acude a tu punto de entrega más cercano para recoger tu pedido.</p>
                    </div>
                    
                    <a href="ComprarController.php" class="btn btn-primary">
                        <i class="fa fa-shopping-cart"></i> Continuar Comprando
                    </a>
                    <a href="HistorialController.php" class="btn btn-default">
                        <i class="fa fa-list"></i> Ver Historial
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('Footer.php'); ?>