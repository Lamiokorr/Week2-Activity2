<?php

require_once '../settings/db_class.php';

class Product extends db_connection {
    private $product_id;
    private $product_cat;
    private $product_brand;
    private $product_title;
    private $product_price;
    private $product_desc;
    private $product_image;
    private $product_keywords;
    private $date_created;

    public function __construct($product_id = null) 
    {
        parent::db_connect();
        if ($product_id) {
            $this->product_id = $product_id;
            $this->loadProduct();
        }
    }

    private function loadProduct($product_id = null) {
        if ($product_id) {
            $this->product_id = $product_id;
        }
        if (!$this->product_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $this->product_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->product_cat = $result['product_cat'];
            $this->product_brand = $result['product_brand'];
            $this->product_title = $result['product_title'];
            $this->product_price = $result['product_price'];
            $this->product_desc = $result['product_desc'];
            $this->product_image = $result['product_image'];
            $this->product_keywords = $result['product_keywords'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
        }
    }

    // Add new product
    public function createProduct($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords) {
        $stmt = $this->db->prepare("INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisisss", $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }


    
    // Update product
    public function updateProduct($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords) {
        $stmt = $this->db->prepare("UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, product_desc = ?, product_image = ?, product_keywords = ? WHERE product_id = ?");
        $stmt->bind_param("iisisssi", $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $product_id);
        return $stmt->execute();
    }

    // Delete product
    public function deleteProduct($product_id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    public function updateProductImagePath($product_id, $image_path) {
        $stmt = $this->db->prepare("UPDATE products SET product_image = ? WHERE product_id = ?");
        $stmt->bind_param("si", $image_path, $product_id);
        return $stmt->execute();
    }
    
    public function get_last_inserted_product_id() {
        return $this->db->insert_id;
    }

    //View all products
    public function view_all_products(){
        $stmt = $this->db->prepare("SELECT * FROM products");
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function search_products($query){
        $like_query = '%' . $query . '%';
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_title LIKE ? OR product_desc LIKE ? OR product_keywords LIKE ?");
        $stmt->bind_param("sss", $like_query, $like_query, $like_query);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products; 
    }

    public function filter_products_by_category($cat_id){
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_cat = ?");
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products; 
    }

    public function filter_products_by_brand($brand_id){
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_brand = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products; 
    }

    public function view_single_product($id){
        $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); 
    }

    public function view_all_products_paginated($offset, $limit) {
    $stmt = $this->db->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

 public function count_all_products() {
    $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM products");
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}
}

?>