<?php

require_once '../classes/brand_class.php';

function add_brand_ctr($brand_name){
    $brand = new Brand();
    $brand_id = $brand->createBrand($brand_name);
    if($brand_id){
        return $brand_id;
    }
    return false;
}

function get_all_brands_ctr(){
    $brand = new Brand();
    return $brand->getAllBrands();
}

function update_brand_ctr($brand_id, $brand_name){
    $brand = new Brand($brand_id);
    return $brand->updateBrand($brand_id, $brand_name);
}

function delete_brand_ctr($brand_id){
    $brand = new Brand();
    return $brand->deleteBrand($brand_id);
}


?>