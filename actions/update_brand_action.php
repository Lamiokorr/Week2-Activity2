<?php

header('Content-Type: application/json');

session_start();

require_once '../controllers/brand_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand_id = $_POST['id'];
    $brand_name = $_POST['name'];

    $updated = update_brand_ctr($brand_id, $brand_name);

    if ($updated) {
        echo json_encode([
            "status" => "success",
            "message" => "Brand updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Unable to update brand"
        ]);
    }

}
?>