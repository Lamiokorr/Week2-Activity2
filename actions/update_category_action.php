<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/category_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = $_POST['id'];
    $category_name = $_POST['name'];




    $updated = update_category_ctr($category_id, $category_name);

    if ($updated) {
        echo json_encode([
            "status" => "success",
            "message" => "Category updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Unable to update category"
        ]);
    }
}