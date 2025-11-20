<?php
session_start();
header('Content-Type: application/json');
require_once "../controllers/cart_controller.php";

if (!isset($_POST['p_id'])) {
    echo json_encode(['status'=>'error','message'=>'Product id missing']);
    exit;
}
$p_id = intval($_POST['p_id']);
$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$result = remove_from_cart_ctr($p_id, $ip, $c_id);
if ($result) {
    $count = count_cart_items_ctr($ip, $c_id);
    echo json_encode(['status'=>'success','message'=>'Removed from cart','count'=>$count]);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to remove item']);
}
?>
