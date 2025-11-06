<?php

require_once '../classes/product_class.php';

function add_product_ctr($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords){
    $product = new Product();
    $product_id = $product->createProduct($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);
    if($product_id){
        return $product_id;
    }
    return false;

    function get_all_products_ctr(){
        $product = new Product();
        return $product->getAllProducts();
    }

    function update_product_ctr($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords){
        $product = new Product($product_id);
        return $product->updateProduct($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);
    }

    function delete_product_ctr($product_id){
        $product = new Product();
        return $product->deleteProduct($product_id);
    }

    function get_last_inserted_product_id_ctr() {
        $product = new Product();
        return $product -> get_last_inserted_product_id();
    }

    function update_product_image_path_ctr($product_id, $image_path) {
        $product = new Product();
        return $product -> updateProductImagePath($product_id, $image_path);
}
}

?>

