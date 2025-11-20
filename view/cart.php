<?php
session_start();
require_once "../controllers/cart_controller.php";

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$items = get_user_cart_ctr($ip, $c_id);
$total = 0.0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        .cart-table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        .cart-table th, .cart-table td { padding: 10px; border-bottom: 1px solid #ddd; text-align:left; }
        .cart-image img { width: 100px; height: 100px; object-fit:contain; }
        .controls button { padding:6px 10px; margin-right:6px; }
        .actions { margin-top: 20px; text-align: right; width:90%; margin-left:auto; margin-right:auto; }
    </style>
</head>
<body>
<h1>Your Cart</h1>

<?php if (empty($items)): ?>
    <p>Your cart is empty. <a href="all_product.php">Continue shopping</a></p>
<?php else: ?>

<table class="cart-table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="cart-items">
    <?php foreach ($items as $it): 
        $subtotal = floatval($it['product_price']) * intval($it['qty']);
        $total += $subtotal;
    ?>
        <tr data-pid="<?php echo $it['p_id']; ?>">
            <td class="cart-image"><img src="../product/<?php echo htmlspecialchars($it['product_image']); ?>" alt=""></td>
            <td><?php echo htmlspecialchars($it['product_title']); ?></td>
            <td>GHS <?php echo number_format($it['product_price'],2); ?></td>
            <td>
                <input type="number" min="1" value="<?php echo $it['qty']; ?>" class="qty-input" data-pid="<?php echo $it['p_id']; ?>">
            </td>
            <td class="subtotal">GHS <?php echo number_format($subtotal,2); ?></td>
            <td>
                <button class="remove-btn" data-pid="<?php echo $it['p_id']; ?>">Remove</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div style="width:90%; margin: 10px auto; text-align:right;">
    <strong>Total: GHS <?php echo number_format($total,2); ?></strong>
</div>

<div class="actions">
    <a href="all_product.php"><button>Continue Shopping</button></a>
    <button id="empty-cart">Empty Cart</button>
    <a href="checkout.php"><button id="checkout-btn">Proceed to Checkout</button></a>
</div>

<?php endif; ?>

<script src="../js/cart.js"></script>
</body>
</html>
