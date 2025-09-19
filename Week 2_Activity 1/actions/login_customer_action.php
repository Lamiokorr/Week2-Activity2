<?php

header('Content Type: application/json');

session_start();

require_once '../controllers/customer_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $customer = login_customer_ctr($email, $password);

    if($customer){
        $_SESSION['customer_id'] = $customer['customer_id'];
        $_SESSION['fullname']    = $customer['fullname'];
        $_SESSION['email']       = $customer['email'];
        $_SESSION['role']        = $customer['user_role'];

         echo "success";  
        } else {
            echo "error";  
    }

}
?>