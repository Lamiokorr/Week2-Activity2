<?php
header("Content-Type: application/json");

// Check if a file was uploaded
if (!isset($_FILES['productImage'])) {
    echo json_encode(["success" => false, "message" => "No image file uploaded."]);
    exit();
}

$file = $_FILES['productImage'];
$uploadDir = "../uploads/products/";

// Create upload folder if it doesn’t exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
} 

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];

// Validate file type
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid image format. Only JPG, PNG, and WEBP allowed."]);
    exit();
}

//Validate file size (max 5MB)
$maxSize = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $maxSize) {
    echo json_encode(["success" => false, "message" => "File is too large. Max 5MB allowed."]);
    exit();
}

// Generate unique filename to avoid overwrites
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = uniqid("product_", true) . '.' . strtolower($extension);
$targetPath = $uploadDir . $newFileName;

//Move uploaded file
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    echo json_encode([
        "success" => true,
        "message" => "Image uploaded successfully.",
        "file_path" => $targetPath
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to move uploaded file."]);
}
?>
