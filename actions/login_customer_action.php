<?php

header('Content-Type: application/json');

session_start();

require_once '../controllers/customer_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['customer_email'];
    $password = $_POST['customer_pass'];

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email and password required"]);
        exit;
    }
    
    $customer = login_customer_ctr($email, $password);

    if($customer){
        $_SESSION['customer_id'] = $customer['customer_id'];
        $_SESSION['fullname']    = $customer['customer_name'];
        $_SESSION['email']       = $customer['customer_email'];
        $_SESSION['role']        = $customer['user_role'];

         echo json_encode(["status" => "success", "message" => "Login successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
?>