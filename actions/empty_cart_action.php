<?php
session_start();
header('Content-Type: application/json');
require_once "../controllers/cart_controller.php";

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$result = empty_cart_ctr($ip, $c_id);
if ($result) {
    echo json_encode(['status'=>'success','message'=>'Cart emptied']);
} else {
    echo json_encode(['status'=>'error','message'=>'Failed to empty cart']);
}
?>
