<?php include('../includes/header_cliente.php'); ?>

<div class="container mt-4">
    <h2 class="mb-4">Finalizar Compra</h2>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Información de Envío</h5>
                </div>
                <div class="card-body">
                    <form id="checkout-form" action="OrdenesController.php?action=crear" method="POST">
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección de Envío</label>
                            <textarea class="form-control" id="direccion" name="direccion" rows="3" required><?= 
                                htmlspecialchars($_SESSION['usuario']['direccion'] ?? '') 
                            ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas adicionales (opcional)</label>
                            <textarea class="form-control" id="notas" name="notas" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resumen de la Orden</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><?= htmlspecialchars($item['nombre_producto']) ?> x<?= $item['cantidad'] ?></span>
                            <span>$<?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?></span>
                        </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>$<?= number_format(array_reduce($items, function($carry, $item) {
                                return $carry + ($item['precio_unitario'] * $item['cantidad']);
                            }, 0), 2) ?></span>
                        </li>
                    </ul>
                    
                    <button type="submit" form="checkout-form" class="btn btn-primary w-100 mt-3">
                        Confirmar Compra
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../includes/footer_cliente.php'); ?>