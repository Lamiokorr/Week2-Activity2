<?php
session_start();
require_once "../controllers/cart_controller.php";

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$items = get_user_cart_ctr($ip, $c_id);
$total = 0;
foreach ($items as $it) {
    $total += floatval($it['product_price']) * intval($it['qty']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
        .summary { width: 90%; margin: 20px auto; }
        .summary table { width:100%; border-collapse: collapse; }
        .summary th, .summary td { padding:8px; border-bottom:1px solid #ddd; text-align:left;}
        #simulate-pay { padding:10px 18px; background:#1e90ff; color:#fff; border:none; border-radius:6px; cursor:pointer;}
    </style>
</head>
<body>
<h1>Checkout</h1>

<div class="summary">
    <h3>Order Summary</h3>
    <?php if (empty($items)): ?>
        <p>Your cart is empty. <a href="all_product.php">Shop now</a></p>
    <?php else: ?>
    <table>
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): 
            $sub = floatval($it['product_price']) * intval($it['qty']);
        ?>
            <tr>
                <td><?php echo htmlspecialchars($it['product_title']); ?></td>
                <td>GHS <?php echo number_format($it['product_price'],2); ?></td>
                <td><?php echo $it['qty']; ?></td>
                <td>GHS <?php echo number_format($sub,2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p style="text-align:right;"><strong>Total: GHS <?php echo number_format($total,2); ?></strong></p>

    <div style="text-align:right;">
        <button id="simulate-pay">Simulate Payment</button>
    </div>

    <?php endif; ?>
</div>

<script src="../js/checkout.js"></script>
</body>
</html>
