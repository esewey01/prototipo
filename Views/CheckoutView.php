<?php
session_start();

// Debug - quitar en producción
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar sesión y orden
if (!isset($_SESSION['usuario']) || !isset($_SESSION['ultima_orden'])) {
    $_SESSION['error_checkout'] = 'No hay orden para mostrar';
    header('Location: ../Controller/CarritoController.php');
    exit;
}

require_once('../Model/Conexion.php');
$db = new Conexion();
$orden = $db->getOrdenById($_SESSION['ultima_orden']);
$detalles = $db->getDetalleOrden($_SESSION['ultima_orden']);

/* Temporalmente en CheckoutView.php
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit;*/
if (!$orden) {
    $_SESSION['error_checkout'] = 'La orden no existe';
    header('Location: ../Controller/CarritoController.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    

<head>
    <title>Confirmación de Pago</title>
   
    <style>
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: white;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    

    
    <div class="container mt-4 mb-5">
        <div class="receipt">
            <div class="receipt-header">
                <h2 class="text-success"><i class="fa fa-check-circle"></i> Pedido realizado con exito!</h2>
                <h4>UPIICSA FOOD</h4>
                <p class="text-muted">Orden #<?= htmlspecialchars($orden['id_orden']) ?></p>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= $orden['fecha_orden']->format('d/mY H:m') ?></p>
                    <p><strong>Cliente:</strong> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Total:</strong> $<?= number_format($orden['total'], 2) ?></p>
                    <p><strong>Estado:</strong> <span class="badge bg-warning"><?= htmlspecialchars($orden['estado']) ?></span></p>
                </div>
            </div>

            <h5 class="mt-4">Detalles del pedido:</h5>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre_producto']) ?></td>
                        <td class="text-end"><?= htmlspecialchars($item['cantidad']) ?></td>
                        <td class="text-end">$<?= number_format($item['precio_unitario'], 2) ?></td>
                        <td class="text-end">$<?= number_format($item['cantidad'] * $item['precio_unitario'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th class="text-end">$<?= number_format($orden['total'], 2) ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="alert alert-info mt-4">
                <h5><i class="fa fa-info-circle"></i> Instrucciones para pago en efectivo</h5>
                <p>Por favor entrega el pago exacto al momento de recibir tus productos.</p>
                <p>El vendedor te proporcionará un comprobante físico al recibir el pago.</p>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                <button onclick="window.print()" class="btn btn-outline-primary me-md-2">
                    <i class="fa fa-print"></i> Imprimir Comprobante
                </button>
                <a href="../Controller/PrincipalController.php" class="btn btn-primary">
                    <i class="fa fa-home"></i> Volver al inicio
                </a>
            </div>
        </div>
    </div>

    <?php include('../Views/LibraryJs.php'); ?>
</body>
</html>