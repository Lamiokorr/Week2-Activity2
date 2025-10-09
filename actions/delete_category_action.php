<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/category_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = $_POST['id'];

    $result = delete_category_ctr($category_id);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Category deleted successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Unable to delete category"
        ]);
    }
}
?>