<?php

header('Content-Type: application/json');

session_start();
require_once '../controllers/category_controller.php';

$category = get_all_categories_ctr();

if ($category) {
    echo json_encode($category);
} else {
    echo json_encode([]);
}
