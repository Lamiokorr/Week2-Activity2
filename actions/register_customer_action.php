<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

$response = array();

// If the user is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already registered in'; 
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect inputs safely
    $name         = trim($_POST['name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $password_raw = $_POST['password'] ?? '';
    $country      = trim($_POST['country'] ?? '');
    $city         = trim($_POST['city'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $role         = 1;

    // === VALIDATION ===
    if (empty($name) || strlen($name) < 3) {
        $response['status'] = 'error';
        $response['message'] = 'Name must be at least 3 characters long';
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit();
    }

    if (strlen($password_raw) < 8) {
        $response['status'] = 'error';
        $response['message'] = 'Password must be at least 8 characters long';
        echo json_encode($response);
        exit();
    }

    if (empty($country)) {
        $response['status'] = 'error';
        $response['message'] = 'Country is required';
        echo json_encode($response);
        exit();
    }

    if (empty($city)) {
        $response['status'] = 'error';
        $response['message'] = 'City is required';
        echo json_encode($response);
        exit();
    }

    if (!preg_match('/^[0-9]{7,15}$/', $phone_number)) {
        $response['status'] = 'error';
        $response['message'] = 'Phone number must contain 7-15 digits only';
        echo json_encode($response);
        exit();
    }

    // Hash password securely
    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Try registering
    try {
        $customer_id = register_user_ctr($name, $email, $password, $country, $city, $phone_number, $role);

        if ($customer_id) {
            $response['status'] = 'success';
            $response['message'] = 'Registered successfully';
            $response['customer_id'] = $customer_id;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to register (controller returned false)';
        }
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Exception: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
