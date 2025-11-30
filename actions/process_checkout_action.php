<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once "../controllers/cart_controller.php";
require_once "../controllers/order_controller.php";
require_once "../controllers/payment_controller.php"; // Make sure this exists!

// Paystack secret key (TEST)
$secret_key = "sk_test_acb1b5ad4d0ed7a63fe7866559bfef4263983b43";

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = $_SESSION['customer_id'] ?? null;

if (!$c_id) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to checkout.']);
    exit;
}

$items = get_user_cart_ctr($ip, $c_id);
if (empty($items)) {
    echo json_encode(['status' => 'error', 'message' => 'Cart is empty']);
    exit;
}

// Read reference from frontend
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['reference'])) {
    echo json_encode(['status' => 'error', 'message' => 'Payment reference missing']);
    exit;
}
$reference = $input['reference'];

// Verify with Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . urlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer $secret_key"],
]);
$response = curl_exec($curl);
$curl_err = curl_error($curl);
curl_close($curl);

if ($curl_err) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Connection to Paystack failed',
        'debug' => ['curl_error' => $curl_err]
    ]);
    exit;
}

$resp = json_decode($response, true);

if (!isset($resp['data']) || $resp['data']['status'] !== 'success') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment not successful on Paystack',
        'debug' => ['paystack_response' => $resp]
    ]);
    exit;
}

// Calculate cart total (from DB)
$total = 0.0;
foreach ($items as $it) {
    $total += floatval($it['product_price']) * intval($it['qty']);
}

$paid_amount = $resp['data']['amount'] / 100;

if (abs($paid_amount - $total) > 0.01) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Amount mismatch',
        'debug' => [
            'cart_total' => $total,
            'paid_amount' => $paid_amount,
            'difference' => abs($paid_amount - $total)
        ]
    ]);
    exit;
}

// Create order
$invoice_no = rand(100000, 999999);
$order_date = date('Y-m-d');
$order_id = create_order_ctr($c_id, $invoice_no, $order_date, 'Paid');

if (!$order_id) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to create order']);
    exit;
}

// Add order details
foreach ($items as $it) {
    $p_id = intval($it['p_id']);
    $qty = intval($it['qty']);
    if (!add_order_detail_ctr($order_id, $p_id, $qty)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save order items']);
        exit;
    }
}

// Record payment — CORRECT PARAMETER ORDER!
$payment_success = record_payment_ctr(
    $total,                    // amt (double) → column 2
    $c_id,                     // customer_id → column 3
    $order_id,                 // order_id → column 4
    'GHS',                     // currency
    'Paystack',                // payment_method
    $reference,                // transaction_ref
    date('Y-m-d H:i:s'),       // payment_date
    $resp['data']['authorization']['authorization_code'] ?? null,
    $resp['data']['channel'] ?? 'unknown'
);

if (!$payment_success) {
    // Get the actual SQL error
    global $conn; // assuming you use a global $conn somewhere
    $sql_error = mysqli_error($conn ?? null) ?: 'Unknown DB error';
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to record payment in database',
        'debug' => ['sql_error' => $sql_error]
    ]);
    exit;
}

// Empty cart
empty_cart_ctr($ip, $c_id);

// SUCCESS!
echo json_encode([
    'status' => 'success',
    'message' => 'Payment successful!',
    'order_id' => $order_id,
    'invoice_no' => $invoice_no,
    'amount' => $total
]);
exit;
?>