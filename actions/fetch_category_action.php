<?php

header('Content Type: application/json');

session_start();
require_once '../controllers/category_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = $_SESSION['customer_id'];


    $category = get_all_categories_ctr($category_id);

    if($category){
         echo json_encode($category);  
        } else {
            echo "error";  
    }

}

?>