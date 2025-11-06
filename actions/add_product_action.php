<?php
header('Content-Type: application/json');

session_start();

require_once '../settings/file_helpers.php';
require_once '../controllers/product_controller.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $product_cat = $_POST['product_cat'];
    $product_brand = $_POST['product_brand'];
    $product_name = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_desc'];
    $product_image = $_FILES['product_image'];
    $product_keywords = $_POST['product_keywords'];
    $customer_id = $_SESSION['customer_id'];


    if (!$product_cat || !$product_brand || !$product_name || $product_price <= 0) {
    echo json_encode(["success"=>false,"message"=>"Missing required fields"]);
    exit();
}

    $product_id = get_last_inserted_product_id_ctr($user_id); 
// Implement get_last_inserted_product_id_ctr() in controller to return product_id
if (!$product_id) {
    echo json_encode(["success"=>false,"message"=>"Could not get product id"]);
    exit();
}

// Build folder path inside uploads
$upload_root = 'uploads';
$user_folder = $upload_root . "/u{$user_id}";
$product_folder = $user_folder . "/p{$product_id}";

// create folders if necessary (server relative path)
if (!is_dir($user_folder)) mkdir($user_folder, 0755, true);
if (!is_dir($product_folder)) mkdir($product_folder, 0755, true);

// Handle file if provided
$image_path = null;
if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['productImage'];

    // Validate MIME
    $allowed = ['image/jpeg','image/png','image/webp'];
    if (!in_array($file['type'], $allowed)) {
        echo json_encode(["success"=>false,"message"=>"Invalid image type"]);
        exit();
    }
    if ($file['size'] > 5*1024*1024) {
        echo json_encode(["success"=>false,"message"=>"Image too large"]);
        exit();
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safe = safe_filename(pathinfo($file['name'], PATHINFO_FILENAME));
    $newname = $safe . '_' . uniqid() . '.' . $ext;
    $target_relative = "{$product_folder}/{$newname}"; // store relative path in DB
    $target_server = __DIR__ . '/../' . $target_relative; // server path to move into

    //move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_server)) {
         $image_path = $target_relative;
        // update product record with image path
        $updated = update_product_image_path_ctr($product_id, $image_path); // implement in controller
        if (!$updated) {
            echo json_encode(["success"=>false,"message"=>"Uploaded but failed to update DB"]);
            exit();
        }
    } else {
        echo json_encode(["success"=>false,"message"=>"Failed to move uploaded file"]);
        exit();
    }
}

// success
echo json_encode(["success"=>true,"message"=>"Product created","product_id"=>$product_id,"image"=>$image_path]);
exit();
  

}
?>