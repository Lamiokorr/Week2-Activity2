<?php
session_start();
header('Content-Type: application/json');
require_once "../controllers/cart_controller.php";

if (!isset($_POST['p_id']) || !isset($_POST['qty'])) {
    echo json_encode(['status'=>'error','message'=>'Parameters missing']);
    exit;
}
$p_id = intval($_POST['p_id']);
$qty = intval($_POST['qty']);
$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$result = update_cart_item_ctr($p_id, $qty, $ip, $c_id);
if ($result) {
    echo json_encode(['status'=>'success','message'=>'Quantity updated']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to update quantity']);
}
?>
