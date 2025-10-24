<?php

require_once '../settings/db_class.php';

class Brand extends db_connection
{
    private $brand_id;
    private $brand_name;
    private $date_created;

    public function __construct($brand_id = null)
    {
        parent::db_connect();
        if ($brand_id) {
            $this->brand_id = $brand_id;
            $this->loadBrand();
        }
    }

    private function loadBrand($brand_id = null)
    {
        if ($brand_id) {
            $this->brand_id = $brand_id;
        }
        if (!$this->brand_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $this->brand_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->brand_name = $result['brand_name'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
        }
    }

    // Add new brand
    public function createBrand($brand_name)
    {
        $stmt = $this->db->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $brand_name);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    //Get all brands
    public function getAllBrands()
    {
        $stmt = $this->db->prepare("SELECT * FROM brands");
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = [];
        while ($row = $result->fetch_assoc()) {
            $brands[] = $row;
        }
        return $brands;
    }

    // Update brand
    public function updateBrand($brand_id, $brand_name)
    {
        $stmt = $this->db->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
        $stmt->bind_param("si", $brand_name, $brand_id);
        return $stmt->execute();
    }

    // Delete brand
    public function deleteBrand($brand_id)
    {
        $stmt = $this->db->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        return $stmt->execute();
    }

}



?>