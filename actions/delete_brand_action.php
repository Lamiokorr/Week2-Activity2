<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/brand_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $brand_id = $_POST['brand_id'];

    $result = delete_brand_ctr($brand_id);

    if($result){
         echo "Brand deleted successfully";  
        } else {
            echo "Unable to delete brand";  
    }
}
?>