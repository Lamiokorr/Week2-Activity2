<?php

header('Content-Type: application/json');

session_start();

require_once '../controllers/brand_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand_name = $_POST['name'];
    $customer_id = $_SESSION['customer_id'];

    $result = add_brand_ctr($brand_name, $customer_id);

    if($result){
         echo "Brand added successfully";  
        } else {
            echo "Unable to add brand";  
    }

}



?>