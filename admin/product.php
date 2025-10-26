<?php
require_once("../settings/core.php");
require_once("../controllers/product_controller.php");

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['customer_id'];
$products = get_all_products_ctr($user_id); 
$brands = get_all_brands_ctr($user_id); 
$categories = get_all_categories_ctr($user_id);

?>

