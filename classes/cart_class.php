<?php

require_once '../settings/db_class.php';

class Cart extends db_connection 
{
    private $p_id;
    private $ip_add;
    private $c_id;
    private $qty;
    private $date_created;

    public function __construct($c_id = null) 
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
            $this->ip_add = $result['ip_add'];
            $this->c_id = $result['c_id'];
            $this->qty = $result['qty'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
        }
    }

    // Add item to cart
    public function add_to_cart($p_id, $ip_add, $c_id = null, $qty = 1) {
        // check if exists (match customer or ip)
        if ($c_id) {
            $stmt = $this->db->prepare("SELECT qty FROM cart WHERE p_id = ? AND c_id = ?");
            $stmt->bind_param("ii", $p_id, $c_id);
        } else {
            $stmt = $this->db->prepare("SELECT qty FROM cart WHERE p_id = ? AND ip_add = ?");
            $stmt->bind_param("is", $p_id, $ip_add);
        }
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        if ($res) {
            $newQty = $res['qty'] + $qty;
            if ($c_id) {
                $stmt = $this->db->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?");
                $stmt->bind_param("iii", $newQty, $p_id, $c_id);
            } else {
                $stmt = $this->db->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?");
                $stmt->bind_param("iis", $newQty, $p_id, $ip_add);
            }
            return $stmt->execute();
        } else {
            if ($c_id) {
                $stmt = $this->db->prepare("INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $p_id, $ip_add, $c_id, $qty);
            } else {
                $nullCid = null;
                $stmt = $this->db->prepare("INSERT INTO cart (p_id, ip_add, c_id, qty) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $p_id, $ip_add, $nullCid, $qty);
            }
            return $stmt->execute();
        }
    }
    
    // Update cart item quantity
     public function update_quantity($p_id, $qty, $ip_add, $c_id = null) {
        if ($c_id) {
            $stmt = $this->db->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?");
            $stmt->bind_param("iii", $qty, $p_id, $c_id);
        } else {
            $stmt = $this->db->prepare("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?");
            $stmt->bind_param("iis", $qty, $p_id, $ip_add);
        }
        return $stmt->execute();
    }


    // Remove item from cart
     public function remove_from_cart($p_id, $ip_add, $c_id = null) {
        if ($c_id) {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE p_id = ? AND c_id = ?");
            $stmt->bind_param("ii", $p_id, $c_id);
        } else {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE p_id = ? AND ip_add = ?");
            $stmt->bind_param("is", $p_id, $ip_add);
        }
        return $stmt->execute();
    }

    // empty cart
     public function empty_cart($ip_add, $c_id = null) {
        if ($c_id) {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE c_id = ?");
            $stmt->bind_param("i", $c_id);
        } else {
            $stmt = $this->db->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->bind_param("s", $ip_add);
        }
        return $stmt->execute();
    }

    // Get cart items for user
    public function get_user_cart($ip_add, $c_id = null) {
        if ($c_id) {
            $stmt = $this->db->prepare("SELECT c.p_id, c.qty, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.c_id = ?");
            $stmt->bind_param("i", $c_id);
        } else {
            $stmt = $this->db->prepare("SELECT c.p_id, c.qty, p.product_title, p.product_price, p.product_image FROM cart c JOIN products p ON c.p_id = p.product_id WHERE c.ip_add = ?");
            $stmt->bind_param("s", $ip_add);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

     public function count_cart_items($ip_add, $c_id = null) {
        if ($c_id) {
            $stmt = $this->db->prepare("SELECT SUM(qty) AS total FROM cart WHERE c_id = ?");
            $stmt->bind_param("i", $c_id);
        } else {
            $stmt = $this->db->prepare("SELECT SUM(qty) AS total FROM cart WHERE ip_add = ?");
            $stmt->bind_param("s", $ip_add);
        }
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] ?? 0;
    }
}
?>