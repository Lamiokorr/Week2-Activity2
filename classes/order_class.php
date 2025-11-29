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
    public function createOrder($customer_id, $invoice_no, $order_date, $order_status) {
        $stmt = $this->db->prepare("INSERT INTO orders (customer_id, invoice_no, order_date, order_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $customer_id, $invoice_no, $order_date, $order_status);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    // Add order detail
   public function add_order_detail($order_id, $product_id, $qty) {
        try {
            $order_id = (int)$order_id;
            $product_id = (int)$product_id;
            $qty = (int)$qty;
            
            $sql = "INSERT INTO orderdetails (order_id, product_id, qty) 
                    VALUES ($order_id, $product_id, $qty)";
            
            error_log("Adding order detail - Order: $order_id, Product: $product_id, Qty: $qty");
            
            return $this->db_write_query($sql);
            
        } catch (Exception $e) {
            error_log("Error adding order details: " . $e->getMessage());
            return false;
        }
    }

    // Record payment
   public function record_payment($amount, $customer_id, $order_id, $currency, $payment_date, $payment_method = 'direct', $transaction_ref = null, $authorization_code = null, $payment_channel = null) {
        error_log("=== RECORD_PAYMENT METHOD CALLED ===");
        try {
            $amount = (float)$amount;
            $customer_id = (int)$customer_id;
            $order_id = (int)$order_id;
            $currency = mysqli_real_escape_string($this->db_conn(), $currency);
            $payment_date = mysqli_real_escape_string($this->db_conn(), $payment_date);
            $payment_method = mysqli_real_escape_string($this->db_conn(), $payment_method);
            $transaction_ref = $transaction_ref ? mysqli_real_escape_string($this->db_conn(), $transaction_ref) : null;
            $authorization_code = $authorization_code ? mysqli_real_escape_string($this->db_conn(), $authorization_code) : null;
            $payment_channel = $payment_channel ? mysqli_real_escape_string($this->db_conn(), $payment_channel) : null;
            
            // Build SQL with optional fields
            $columns = "(amt, customer_id, order_id, currency, payment_date, payment_method";
            $values = "($amount, $customer_id, $order_id, '$currency', '$payment_date', '$payment_method'";
            
            if ($transaction_ref) {
                $columns .= ", transaction_ref";
                $values .= ", '$transaction_ref'";
            }
            if ($authorization_code) {
                $columns .= ", authorization_code";
                $values .= ", '$authorization_code'";
            }
            if ($payment_channel) {
                $columns .= ", payment_channel";
                $values .= ", '$payment_channel'";
            }
            
            $columns .= ")";
            $values .= ")";
            
            $sql = "INSERT INTO payment $columns VALUES $values";
            
            error_log("Executing SQL: $sql");
            
            if ($this->db_write_query($sql)) {
                $payment_id = $this->last_insert_id();
                error_log("Payment recorded successfully with ID: $payment_id");
                return $payment_id;
            } else {
                $error = mysqli_error($this->db_conn());
                error_log("Payment recording failed. MySQL error: " . $error);
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Error recording payment: " . $e->getMessage());
            return false;
        }
    }

    // Retrieve past orders for a user
 public function get_user_orders($customer_id) {
        try {
            $customer_id = (int)$customer_id;
            
            $sql = "SELECT 
                        o.order_id,
                        o.invoice_no,
                        o.order_date,
                        o.order_status,
                        p.amt as total_amount,
                        p.currency,
                        COUNT(od.product_id) as item_count
                    FROM orders o
                    LEFT JOIN payment p ON o.order_id = p.order_id
                    LEFT JOIN orderdetails od ON o.order_id = od.order_id
                    WHERE o.customer_id = $customer_id
                    GROUP BY o.order_id
                    ORDER BY o.order_date DESC, o.order_id DESC";
            
            return $this->db_fetch_all($sql);
            
        } catch (Exception $e) {
            error_log("Error getting user orders: " . $e->getMessage());
            return false;
        }
    }

    // Get details of a specific order
    public function get_order_details($order_id, $customer_id) {
        try {
            $order_id = (int)$order_id;
            $customer_id = (int)$customer_id;
            
            $sql = "SELECT 
                        o.order_id,
                        o.invoice_no,
                        o.order_date,
                        o.order_status,
                        o.customer_id,
                        p.amt as total_amount,
                        p.currency,
                        p.payment_date
                    FROM orders o
                    LEFT JOIN payment p ON o.order_id = p.order_id
                    WHERE o.order_id = $order_id AND o.customer_id = $customer_id";
            
            return $this->db_fetch_one($sql);
            
        } catch (Exception $e) {
            error_log("Error getting order details: " . $e->getMessage());
            return false;
        }
    }

    // Get all products in a specific order
    public function get_order_products($order_id) {
        try {
            $order_id = (int)$order_id;
            
            $sql = "SELECT 
                        od.product_id,
                        od.qty,
                        p.product_title,
                        p.product_price,
                        p.product_image,
                        (od.qty * p.product_price) as subtotal
                    FROM orderdetails od
                    INNER JOIN products p ON od.product_id = p.product_id
                    WHERE od.order_id = $order_id";
            
            return $this->db_fetch_all($sql);
            
        } catch (Exception $e) {
            error_log("Error getting order products: " . $e->getMessage());
            return false;
        }
    }

    // Update order status
    public function update_order_status($order_id, $order_status) {
        try {
            $order_id = (int)$order_id;
            $order_status = mysqli_real_escape_string($this->db_conn(), $order_status);
            
            $sql = "UPDATE orders SET order_status = '$order_status' WHERE order_id = $order_id";
            
            error_log("Updating order status: $order_id to $order_status");
            
            return $this->db_write_query($sql);
            
        } catch (Exception $e) {
            error_log("Error updating order status: " . $e->getMessage());
            return false;
        }
    }

}

?>