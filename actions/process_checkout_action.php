<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once "../controllers/cart_controller.php";
require_once "../controllers/order_controller.php";
require_once "../controllers/product_controller.php"; 

// Paystack secret key (TEST)
$secret_key = "sk_test_acb1b5ad4d0ed7a63fe7866559bfef4263983b43"; 

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

// Read JSON input (from JS)
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['reference'])) {
    echo json_encode(['status'=>'error','message'=>'Payment reference missing']);
    exit;
}
$reference = $input['reference'];

// Verify payment with Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".urlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secret_key",
        "Cache-Control: no-cache",
    ],
]);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['status'=>'error','message'=>'Curl error: '.$err]);
    exit;
}

$resp = json_decode($response, true);
if (!isset($resp['data']) || $resp['data']['status'] !== 'success') {
    echo json_encode(['status'=>'error','message'=>'Payment verification failed']);
    exit;
}

// compute total amount from cart to cross-check
$total = 0.0;
foreach ($items as $it) {
    $price = floatval($it['product_price']);
    $qty = intval($it['qty']);
    $total += $price * $qty;
}

// Optionally cross-check Paystack amount matches cart total
$paid_amount = $resp['data']['amount'] / 100; // Paystack returns in kobo
if (abs($paid_amount - $total) > 0.01) {
    echo json_encode(['status'=>'error','message'=>'Paid amount does not match cart total']);
    exit;
}

// generate invoice_no
$invoice_no = rand(100000, 999999);
$order_date = date('Y-m-d');

// Create order
$order_id = create_order_ctr($c_id, $invoice_no, $order_date, 'processing');
if (!$order_id) {
    echo json_encode(['status'=>'error','message'=>'Failed to create order']);
    exit;
}

// Add order details
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

// Record payment
$currency = 'GHS';
$payment_date = date('Y-m-d');
$pay_id = rand(1000000, 9999999);
$okPay = record_payment_ctr($pay_id, $total, $c_id, $order_id, $currency, $payment_date);
if (!$okPay) {
    echo json_encode(['status'=>'error','message'=>'Payment recording failed']);
    exit;
}

// Empty cart
$okEmpty = empty_cart_ctr($ip, $c_id);

// Success response
$response = [
    'status' => 'success',
    'message' => 'Payment verified and order created',
    'order_id' => $order_id,
    'invoice_no' => $invoice_no,
    'amount' => $total
];

echo json_encode($response);
exit;
?>
