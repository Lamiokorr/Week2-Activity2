<?php
session_start();
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : null;
?>

<!DOCTYPE html>
<html>
<head><title>Payment Success</title></head>
<body>
<h1>Payment Successful</h1>
<?php if ($order_id): ?>
    <p>Thank you! Your order #<?php echo $order_id; ?> was placed successfully.</p>
<?php else: ?>
    <p>Thank you! Your payment was successful.</p>
<?php endif; ?>
<a href="all_product.php">Continue shopping</a>
</body>
</html>