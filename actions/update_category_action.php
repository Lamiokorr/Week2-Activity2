<?php
header('Content-Type: application/json');

session_start();

require_once '../controllers/category_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $category_id = $_POST['cat_id'];
    $category_name = $_POST['cat_name'];
    


    $updated = update_category_ctr($category_id, $category_name);

    if($updated){
         echo "Category updated successfully";  
        } else {
            echo "Unable to update category";  
    }

}
?>