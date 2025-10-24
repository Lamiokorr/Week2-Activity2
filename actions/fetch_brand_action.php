<?php

header('Content-Type: application/json');

session_start();

require_once '../controllers/brand_controller.php';

$brand = get_all_brands_ctr();
if ($brand) {
    echo json_encode($brand);
} else {
    echo json_encode([]);
}

?>