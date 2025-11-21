<?php

header('Content-Type: application/json');

session_start();

require_once '../controllers/brand_controller.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand_name = $_POST['name'];

    if ($brand_name === '') {
        echo json_encode([
            "status" => "error",
            "message" => "Brand name is required"
        ]);
        exit;
    }

    $result = add_brand_ctr($brand_name);

   if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Brand added successfully",
            "brand_id" => $result
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Unable to add brand"
        ]);
    }
    exit;

}



?>