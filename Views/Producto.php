<?php
if (empty($allProducto)): ?>
    <tr>
        <td colspan="4" class="text-center">
            <div class="alert alert-warning">
                No se encontraron productos
            </div>
        </td>
    </tr>
<?php else: ?>
    <tr class="success">
    <?php 
    $contador = 0;
    foreach ($allProducto as $product): 
        $contador++;
        if ($contador > 4):
            $contador = 1;
            echo '</tr><tr class="success">';
        endif;
    ?>
        <td background="<?= htmlspecialchars($urlViews) ?>img/menuPOS.jpg" align="center">
            <div style="width: 112px">
                <div class="single-product">
                    <div class="product-f-image">
                        <img src="<?= htmlspecialchars($urlViews.$product['imagen']) ?>" 
                             width="90" height="90" class="imgRedonda"
                             onerror="this.src='<?= htmlspecialchars($urlViews) ?>img/default-product.png'">
                        <div class="product-hover">
                            <a onclick="insertarPedidoMesa('<?= $product['idproducto'] ?>','<?= $_SESSION['id_usuario'] ?>')" 
                               class="add-to-cart-link">Mesa</a>
                            <a onclick="insertarPedidoLlevar('<?= $product['idproducto'] ?>','<?= $_SESSION['id_usuario'] ?>')" 
                               class="view-details-link">Llevar</a>
                        </div>
                        <span style="color: #FFFFFF">
                            <b>
                                <?= htmlspecialchars($product['nombreProducto']) ?><br>
                                <?= htmlspecialchars($product['precioVenta']) ?>
                                <?= htmlspecialchars($tipoMonedaElegida) ?>
                            </b>
                        </span>
                    </div>
                </div>
            </div>
        </td>
    <?php endforeach; ?>
    </tr>
<?php endif; ?>