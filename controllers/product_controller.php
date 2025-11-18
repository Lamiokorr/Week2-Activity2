<?php

require_once '../classes/product_class.php';

//Create Product
function add_product_ctr($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords){
    $product = new Product();
     return $product->createProduct($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);
}

    //View All Products
    function view_all_products_ctr() {
    $product = new Product();
    return $product->view_all_products();
}

    //View Single Product
    function view_single_product_ctr($id) {
    $product = new Product();
    return $product->view_single_product($id);
}


    //Search Products
    function search_products_ctr($query) {
    $product = new Product();
    return $product->search_products($query);
}

//Filter products by category
    function filter_products_by_category_ctr($cat_id) {
    $product = new Product();
    return $product->filter_products_by_category($cat_id);
}

    //Filter products by brand
    function filter_products_by_brand_ctr($brand_id) {
    $product = new Product();
    return $product->filter_products_by_brand($brand_id);
}

    //Update Product
    function update_product_ctr($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords){
        $product = new Product($product_id);
        return $product->updateProduct($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);
    }

    //Delete Product
    function delete_product_ctr($product_id){
        $product = new Product();
        return $product->deleteProduct($product_id);
    }

    //Image Handling
    function update_product_image_path_ctr($product_id, $image_path) {
    $product = new Product();
    return $product->updateProductImagePath($product_id, $image_path);
}
    
    function get_last_inserted_product_id_ctr() {
    $product = new Product();
    return $product->get_last_inserted_product_id();
}


?>

