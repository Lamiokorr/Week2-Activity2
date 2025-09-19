<?php

require_once '../settings/db_class.php';

/**
 * 
 */
class Customer extends db_connection
{
    private $customer_id;
    private $name;
    private $email;
    private $password;
    private $country;
    private $city;
    private $role;
    private $date_created;
    private $phone_number;

    public function __construct($customer_id = null)
    {
        parent::db_connect();
        if ($customer_id) {
            $this->user_id = $customer_id;
            $this->loadCustomer();
        }
    }

    private function loadCustomer($customer_id = null)
    {
        if ($customer_id) {
            $this->customer_id = $customer_id;
        }
        if (!$this->customer_id) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_id = ?");
        $stmt->bind_param("i", $this->customer_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $this->name = $result['customer_name'];
            $this->email = $result['customer_email'];
            $this->role = $result['user_role'];
            $this->date_created = isset($result['date_created']) ? $result['date_created'] : null;
            $this->phone_number = $result['customer_contact'];
        }
    }

    public function createCustomer($name, $email, $password, $country, $city, $phone_number, $role)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $email, $hashed_password, $country, $city, $phone_number, $role);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function getCustomerByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM customer WHERE customer_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function loginCustomer($email, $password) {
        $sql = "SELECT * FROM customers WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer && password_verify($password, $customer['password'])) {
            return $customer; // returns user info
        }
        return false;
    }

    public function deleteCustomer($customer_id)
     {
        $stmt = $this->db->prepare("DELETE FROM customers WHERE customer_id = ?");
        $stmt ->bind_param("i", $customer_id);
        $stmt ->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
