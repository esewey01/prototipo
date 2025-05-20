<?php
session_start();

// Verificar sesión y orden
if (!isset($_SESSION['usuario']) || !isset($_SESSION['ultimas_ordenes'])) {
    $_SESSION['error_checkout'] = 'No hay orden para mostrar';
    header('Location: ../Controller/CarritoController.php');
    exit;
}

require_once('../Model/Conexion.php');
$db = new Conexion();

// Obtener todas las órdenes
$ordenes = [];
foreach ($_SESSION['ultimas_ordenes'] as $id_orden) {
    $orden = $db->getOrdenById($id_orden);
    if ($orden) {
        $orden['detalles'] = $db->getDetalleOrden($id_orden);
        $orden['vendedor'] = $db->getUserById($orden['id_vendedor']);
        $ordenes[] = $orden;
    }
}

if (empty($ordenes)) {
    $_SESSION['error_checkout'] = 'Las órdenes no existen';
    header('Location: ../Controller/CarritoController.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirmación de Pago</title>
    <!--?php include('Head.php'); ?-->
    <style>
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            background: white;
            margin-bottom: 30px;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .vendedor-info {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!--?php include('Menu.php'); ?-->

    <section id="main-content">
        <section class="wrapper">
            <div class="container mt-4 mb-5">
                <h2 class="text-center mb-4"><i class="fa fa-check-circle text-success"></i> Pedidos realizados con éxito</h2>
                
                <?php foreach ($ordenes as $orden): ?>
                <div class="receipt">
                    <div class="receipt-header">
                        <h4>UPIICSA FOOD</h4>
                        <p class="text-muted">Orden #<?= htmlspecialchars($orden['id_orden']) ?></p>
                    </div>
                    
                    <div class="vendedor-info">
                        <h5><i class="fa fa-user"></i> Vendedor: <?= htmlspecialchars($orden['vendedor']['nombre']) ?></h5>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> <?= $orden['fecha_orden']->format('d/m/Y H:i') ?></p>
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
                            <?php foreach ($orden['detalles'] as $item): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($item['nombre_producto']) ?>
                                    <?php if (!empty($item['comentario'])): ?>
                                        <br><small class="text-muted"><?= htmlspecialchars($item['comentario']) ?></small>
                                    <?php endif; ?>
                                </td>
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
                </div>
                <?php endforeach; ?>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <button onclick="window.print()" class="btn btn-outline-primary me-md-2">
                        <i class="fa fa-print"></i> Imprimir Comprobantes
                    </button>
                    <a href="../Controller/PrincipalController.php" class="btn btn-primary">
                        <i class="fa fa-home"></i> Volver al inicio
                    </a>
                </div>
            </div>
        </section>
    </section>

    <!--?php include('LibraryJs.php'); ?-->
</body>
</html>