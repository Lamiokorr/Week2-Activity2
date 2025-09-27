<?php

require_once '../classes/category_class.php';

function add_category_ctr($category_name){
    $category = new Category();
    $category_id = $category->createCategory($category_name);
    if($category_id){
        return $category_id;
    }
    return false;
}

function get_all_categories_ctr(){
    $category = new Category();
    return $category->getAllCategories();
}

function update_category_ctr($category_id, $category_name){
    $category = new Category($category_id);
    return $category->updateCategory($category_id, $category_name);
}

function delete_category_ctr($category_id){
    $category = new Category();
    return $category->deleteCategory($category_id);
}

?>