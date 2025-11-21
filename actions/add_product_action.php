<?php
header('Content-Type: application/json');

session_start();

require_once '../settings/file_helpers.php';
require_once '../controllers/product_controller.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_cat      = intval($_POST['product_cat'] ?? 0);
    $product_brand    = intval($_POST['product_brand'] ?? 0);
    $product_title    = trim($_POST['product_title'] ?? '');
    $product_price    = floatval($_POST['product_price'] ?? 0);
    $product_desc     = trim($_POST['product_desc'] ?? '');
    $product_keywords = trim($_POST['product_keywords'] ?? '');
    $customer_id      = $_SESSION['customer_id'] ?? null;


    if (!$customer_id) {
        echo json_encode(["success" => false, "message" => "You must be logged in to add products."]);
        exit;
    }

    if ($product_cat <= 0 || $product_brand <= 0 || $product_title === '' || $product_price <= 0) {
        echo json_encode(["success" => false, "message" => "Missing or invalid required fields."]);
        exit;
    }

    $product_id = add_product_ctr($product_cat, $product_brand, $product_title, $product_price, $product_desc, '', $product_keywords);

    if (!$product_id) {
        echo json_encode(["success" => false, "message" => "Failed to create product."]);
        exit;
    }

    $image_path = null;
    $file = $_FILES['product_image'] ?? ($_FILES['productImage'] ?? null);

    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file['type'], $allowed)) {
            echo json_encode(["success" => false, "message" => "Invalid image type"]);
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(["success" => false, "message" => "Image too large"]);
            exit;
        }

        $upload_root = 'uploads';
        $user_folder = $upload_root . "/u{$customer_id}";
        $product_folder = $user_folder . "/p{$product_id}";

        $server_user_folder = __DIR__ . '/../' . $user_folder;
        $server_product_folder = __DIR__ . '/../' . $product_folder;

        if (!is_dir($server_user_folder)) {
            mkdir($server_user_folder, 0755, true);
        }
        if (!is_dir($server_product_folder)) {
            mkdir($server_product_folder, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $safe = safe_filename(pathinfo($file['name'], PATHINFO_FILENAME));
        $newname = $safe . '_' . uniqid() . '.' . $ext;
        $target_relative = "{$product_folder}/{$newname}";
        $target_server = $server_product_folder . "/{$newname}";

        if (move_uploaded_file($file['tmp_name'], $target_server)) {
            $image_path = $target_relative;
            if (!update_product_image_path_ctr($product_id, $image_path)) {
                echo json_encode(["success" => false, "message" => "Uploaded but failed to update DB"]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to move uploaded file"]);
            exit;
        }
    }

    echo json_encode([
        "success" => true,
        "message" => "Product created",
        "product_id" => $product_id,
        "image" => $image_path
    ]);
    exit;
}
?>