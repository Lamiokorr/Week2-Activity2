<?php

require_once '../settings/db_class.php';

class Category extends db_connection
{
    private $category_id;
    private $category_name;
    private $date_created;

    public function __construct($category_id = null)
    {
        parent::db_connect();
        if ($category_id) {
            $this->category_id = $category_id;
            $this->loadCategory();
        }
    }

    private function loadCategory($category_id = null)
    {
        if ($category_id) {
            $this->category_id = $category_id;
        }
        if (!$this->category_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $this->category_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->category_name = $result['cat_name'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
        }
    }

    // Add new category
    public function createCategory($category_name)
    {
        $stmt = $this->db->prepare("INSERT INTO categories (cat_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    //Get all categories
    public function getAllCategories()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Update category
    public function updateCategory($category_id, $category_name)
    {
        $stmt = $this->db->prepare("UPDATE categories SET cat_name = ? WHERE cat_id = ?");
        $stmt->bind_param("si", $category_name, $category_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete category
    public function deleteCategory($category_id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE cat_id = ?");
        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
