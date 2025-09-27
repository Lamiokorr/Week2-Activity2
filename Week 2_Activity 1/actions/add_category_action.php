<?php

header('Content Type: application/json');

session_start();

require_once '../controllers/category_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_name = $_POST['cat_name'];
    $customer_id = $_SESSION['customer_id'];

    $result = add_category_ctr($category_name, $customer_id);

    if($result){
         echo "Category added successfully";  
        } else {
            echo "Unable to add category";  
    }

}

?>