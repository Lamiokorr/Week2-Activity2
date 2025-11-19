<?php

require_once '../settings/db_class.php';

class Cart extends db_connection 
{
    private $p_id;
    private $ip_id;
    private $c_id;
    private $qty;
    private $date_created;

    public function __construct($cart_id = null) 
    {
        parent::db_connect();
        if ($c_id) {
            $this->c_id = $c_id;
            $this->loadCart();
        }
    }

    private function loadCart($c_id = null) {
        if ($c_id) {
            $this->c_id = $c_id;
        }
        if (!$this->c_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE c_id = ?");
        $stmt->bind_param("i", $this->c_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->p_id = $result['p_id'];
            $this->ip_id = $result['ip_add'];
            $this->qty = $result['qty'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
        }
    }

    // Add item to cart
    public function add_to_cart($p_id, $ip_id, $qty) {
        $stmt = $this->db->prepare("INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $p_id, $ip_id, $qty);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }
    
    // Update cart item quantity
    public function update_cart_quantity($c_id, $qty) {
        $stmt = $this->db->prepare("UPDATE cart SET qty = ? WHERE c_id = ?");
        $stmt->bind_param("ii", $qty, $c_id);
        return $stmt->execute();
    }

    // Remove item from cart
    public function remove_from_cart($c_id) {
        $stmt = $this->db->prepare("DELETE FROM cart WHERE c_id = ?");
        $stmt->bind_param("i", $c_id);
        return $stmt->execute();
    }

    
}