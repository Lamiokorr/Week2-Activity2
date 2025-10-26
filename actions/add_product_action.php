<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/product_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $product_cat = $_POST['product_cat'];
    $product_brand = $_POST['product_brand'];
    $product_name = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_desc'];
    $product_image = $_FILES['product_image'];
    $product_keywords = $_POST['product_keywords'];
    $customer_id = $_SESSION['customer_id'];


   $result = add_product_ctr($product_cat, $product_brand, $product_name, $product_price, $product_description, $product_image, $product_keywords, $customer_id);

    if($result){
         echo json_encode(["success" => true, "message" => "Product added successfully"]);  
        } else {
            echo json_encode(["success" => false, "message" => "Unable to add product"]);  
    }

}
?>