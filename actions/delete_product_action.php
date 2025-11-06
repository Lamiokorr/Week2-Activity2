<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/product_controller.php';
require_once '../settings/file_helpers.php';

$product_id = intval($_POST['product_id'] ?? 0);
if (!$product_id) {
    echo json_encode(["success"=>false,"message"=>"Missing product id"]);
    exit();
}

// get product and user_id (to locate folder)
$product = get_single_product_ctr($product_id);
if (!$product) { echo json_encode(["success"=>false,"message"=>"Not found"]); exit(); }
$user_id = intval($product['user_id'] ?? $_SESSION['customer_id']);


// delete DB record
$deleted = delete_product_ctr($product_id);
if (!$deleted) {
    echo json_encode(["success"=>false,"message"=>"DB delete failed"]);
    exit();
}

    // delete folder safely
$folder = "uploads/u{$user_id}/p{$product_id}";
if (is_inside_uploads($folder) && is_dir(__DIR__ . '/../' . $folder)) {
    rrmdir_uploads(__DIR__ . '/../' . $folder);
}

echo json_encode(["success"=>true,"message"=>"Product deleted"]);
