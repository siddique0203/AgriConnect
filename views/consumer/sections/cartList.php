<h2 class="section-title">Cart List</h2>

<?php if(!empty($cart)): ?>
<table class="cart-table" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#2d6a4f; color:#fff;">
            <th style="padding:8px;">Product</th>
            <th style="padding:8px;">Quantity</th>
            <th style="padding:8px;">Price</th>
            <th style="padding:8px;">Subtotal</th>
            <th style="padding:8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $total = 0; foreach($cart as $item): 
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
        ?>
        <tr>
            <td style="padding:8px;"><?php echo htmlspecialchars($item['product_name']); ?></td>
            <td style="padding:8px;"><?php echo intval($item['quantity']); ?></td>
            <td style="padding:8px;"><?php echo number_format($item['price'],2); ?></td>
            <td style="padding:8px;"><?php echo number_format($subtotal,2); ?></td>
            <td style="padding:8px;">
                <form action="../../controllers/ConsumerController.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="removeFromCart">
                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    <button type="submit" 
                            style="padding:5px 10px; background:#d00000; color:#fff; border:none; border-radius:4px; cursor:pointer;">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="4" style="text-align:right; padding:8px; font-weight:bold;">Total:</td>
            <td style="padding:8px; font-weight:bold;"><?php echo number_format($total,2); ?></td>
        </tr>
    </tbody>
</table>

<div style="margin-top:15px; text-align:right;">
    <!-- Checkout button -->
    <a href="checkout.php" class="btn-checkout" 
       style="padding:10px 20px; background:#2d6a4f; color:#fff; text-decoration:none; border-radius:5px;">
       Proceed to Checkout
    </a>
</div>

<?php else: ?>
<p class="no-data">Cart is empty.</p>
<?php endif; ?>
