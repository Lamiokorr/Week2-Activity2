<?php
session_start();
header('Content-Type: application/json');

require_once "../controllers/cart_controller.php";
require_once "../controllers/order_controller.php";
require_once "../controllers/product_controller.php"; 

// Determine user
$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

if (!$c_id) {
    echo json_encode(['status'=>'error','message'=>'You must be logged in to checkout.']);
    exit;
}

// Get cart items
$items = get_user_cart_ctr($ip, $c_id);
if (empty($items)) {
    echo json_encode(['status'=>'error','message'=>'Cart is empty']);
    exit;
}

// compute total amount
$total = 0.0;
foreach ($items as $it) {
    // ensure price matches product_controller's data 
    $price = floatval($it['product_price']);
    $qty = intval($it['qty']);
    $total += $price * $qty;
}

// generate invoice_no (simple random int)
$invoice_no = rand(100000, 999999);
// order_date
$order_date = date('Y-m-d');

//Create order
$order_id = create_order_ctr($c_id, $invoice_no, $order_date, 'processing');
if (!$order_id) {
    echo json_encode(['status'=>'error','message'=>'Failed to create order']);
    exit;
}

//Add order details
$okDetail = true;
foreach ($items as $it) {
    $p_id = intval($it['p_id']);
    $qty = intval($it['qty']);
    $okDetail = add_order_detail_ctr($order_id, $p_id, $qty) && $okDetail;
}
if (!$okDetail) {
    echo json_encode(['status'=>'error','message'=>'Failed to add order details']);
    exit;
}

// Record simulated payment
$currency = 'GHS';
$payment_date = date('Y-m-d');
$pay_id = rand(1000000, 9999999);
$okPay = record_payment_ctr($pay_id, $total, $c_id, $order_id, $currency, $payment_date);

if (!$okPay) {
    echo json_encode(['status'=>'error','message'=>'Payment recording failed']);
    exit;
}

//Empty cart
$okEmpty = empty_cart_ctr($ip, $c_id);

// Success response
$response = [
    'status' => 'success',
    'message' => 'Checkout completed',
    'order_id' => $order_id,
    'invoice_no' => $invoice_no,
    'amount' => $total
];

echo json_encode($response);
exit;
?>
