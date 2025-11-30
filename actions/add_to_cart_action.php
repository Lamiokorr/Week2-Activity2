<?php
session_start();
header('Content-Type: application/json');

require_once "../controllers/cart_controller.php";

// get input (POST)
$data = $_POST;

// required: p_id
if (!isset($data['p_id'])) {
    echo json_encode(['status'=>'error','message'=>'Product id missing']);
    exit;
}

$p_id = intval($data['p_id']);
$qty = isset($data['qty']) ? intval($data['qty']) : 1;

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$result = add_to_cart_ctr($p_id, $ip, $c_id, $qty);

if ($result) {
    $count = count_cart_items_ctr($ip, $c_id);
    echo json_encode(['status'=>'success','message'=>'Added to cart','count'=>$count]);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to add to cart']);
}
?>
