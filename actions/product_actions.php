<?php
require_once "../controllers/product_controller.php";

// --- SEARCH PRODUCTS --- //
if (isset($_GET['search_btn'])) {
    $search_query = trim($_GET['search']);

    if (!empty($search_query)) {
        header("Location: ../view/product_search_result.php?search=" . urlencode($search_query));
        exit;
    } else {
        header("Location: ../view/all_product.php?error=empty_search");
        exit;
    }
}

// --- FILTER BY CATEGORY --- //
if (isset($_GET['category_filter'])) {
    $cat_id = intval($_GET['category_filter']);

    if ($cat_id > 0) {
        header("Location: ../view/all_product.php?category=" . $cat_id);
        exit;
    } else {
        header("Location: ../view/all_product.php");
        exit;
    }
}

// --- FILTER BY BRAND --- //
if (isset($_GET['brand_filter'])) {
    $brand_id = intval($_GET['brand_filter']);

    if ($brand_id > 0) {
        header("Location: ../view/all_product.php?brand=" . $brand_id);
        exit;
    } else {
        header("Location: ../view/all_product.php");
        exit;
    }
}

// --- VIEW SINGLE PRODUCT --- //
if (isset($_GET['single_product'])) {
    $product_id = intval($_GET['single_product']);

    if ($product_id > 0) {
        header("Location: ../view/single_product.php?id=" . $product_id);
        exit;
    } else {
        header("Location: ../view/all_product.php");
        exit;
    }
}

// If no valid action detected
header("Location: ../view/all_product.php");
exit;

?>
