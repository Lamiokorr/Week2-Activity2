<?php

require_once '../settings/db_class.php';

class Order extends db_connection {
    private $order_id;
    private $customer_id;
    private $invoice_no;
    private $order_date;
    private $order_status;

    public function __construct($order_id = null) 
    {
        parent::db_connect();
        if ($order_id) {
            $this->order_id = $order_id;
            $this->loadOrder();
        }
    }

    private function loadOrder($order_id = null) {
        if ($order_id) {
            $this->order_id = $order_id;
        }
        if (!$this->order_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
        $stmt->bind_param("i", $this->order_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->customer_id = $result['customer_id'];
            $this->invoice_no = $result['invoice_no'];
            $this->order_date = $result['order_date'];
            $this->order_status = $result['order_status'];
        }
    }

    // Create new order
    public function createOrder($customer_id, $invoice_no, $order_date, $order_status = 'Pending') {
        $stmt = $this->db->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $customer_id, $invoice_no, $order_date, $order_status);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    // Add order detail
    public function add_order_detail($order_id, $product_id, $qty) {
        $stmt = $this->db->prepare("INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $order_id, $product_id, $qty);
        return $stmt->execute();
    }

    // Record payment
    public function record_payment($pay_id, $amt, $customer_id, $order_id, $currency = 'GHS', $payment_date) {
        $stmt = $this->db->prepare("INSERT INTO payment (pay_id, amt, customer_id, order_id, currency, payment_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idiiss", $pay_id, $amt, $customer_id, $order_id, $currency, $payment_date);
        return $stmt->execute();
    }

    // Retrieve past orders for a user
    public function get_user_orders($customer_id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }


}