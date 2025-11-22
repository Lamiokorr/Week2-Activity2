<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../settings/core.php");
require_once("../controllers/product_controller.php");
require_once("../controllers/brand_controller.php");
require_once("../controllers/category_controller.php");

if (!isLoggedIn()) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['customer_id'];
$products = view_all_products_ctr($user_id);
$brands = get_all_brands_ctr($user_id);
$categories = get_all_categories_ctr($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            background-color: #fafafa;
            padding: 20px;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            width: 280px;
            padding: 15px;
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-card h4 {
            margin: 10px 0 5px;
            color: #007bff;
        }

        .product-card p {
            margin: 4px 0;
            color: #555;
        }

        .product-card button {
            margin-right: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            max-width: 600px;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        .modal {
            display: none;
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -30%);
            background-color: #fff;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
        }

        .modal-content {
            text-align: center;
            font-size: 16px;
        }

        .product-grid {
            animation: fadeIn 0.6s ease-in-out;
        }
    </style>
</head>

<body>

    <h1>Product Page</h1>

    <!--CREATE / UPDATE FORM -->
    <form id="productForm" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="product_id">

        <label for="product_title">Product Title:</label>
        <input type="text" name="product_title" id="product_title" required>

        <label for="product_price">Product Price:</label>
        <input type="number" name="product_price" id="product_price" step="0.01" required>

        <label for="product_desc">Product Description:</label>
        <textarea name="product_desc" id="product_desc" rows="3" required></textarea>

        <label for="product_keywords">Product Keywords:</label>
        <input type="text" name="product_keywords" id="product_keywords" required>

        <label for="category_id">Category:</label>
        <select name="category_id" id="category_id" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="brand_id">Brand:</label>
        <select name="brand_id" id="brand_id" required>
            <option value="">Select Brand</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image" id="product_image" accept="image/*">

        <button type="submit" id="saveProductBtn">Save Product</button>
    </form>

    <!--DISPLAY PRODUCTS -->
    <h2>Products by Category & Brand</h2>
    <?php if (!empty($products)): ?>
        <?php foreach ($categories as $cat): ?>
            <h3><?= htmlspecialchars($cat['cat_name']) ?></h3>
            <div class="product-container">
                <?php
                $found = false;
                foreach ($products as $product):
                    if ($product['cat_id'] == $cat['cat_id']):
                        $found = true;
                ?>
                        <div class="product-card">
                            <img src="../images/product/<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image">
                            <h4><?= htmlspecialchars($product['product_title']) ?></h4>
                            <p><strong>Brand:</strong> <?= htmlspecialchars($product['brand_name']) ?></p>
                            <p><strong>Price:</strong> GHS <?= htmlspecialchars($product['product_price']) ?></p>
                            <p><strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords']) ?></p>

                            <button class="edit-btn"
                                data-id="<?= $product['product_id'] ?>"
                                data-title="<?= htmlspecialchars($product['product_title']) ?>"
                                data-price="<?= htmlspecialchars($product['product_price']) ?>"
                                data-desc="<?= htmlspecialchars($product['product_desc']) ?>"
                                data-keywords="<?= htmlspecialchars($product['product_keywords']) ?>"
                                data-cat="<?= $product['cat_id'] ?>"
                                data-brand="<?= $product['brand_id'] ?>"
                                data-image="<?= htmlspecialchars($product['product_image']) ?>">
                                Edit
                            </button>

                            <form action="../actions/delete_product_action.php" method="POST" class="deleteForm" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </div>
                <?php
                    endif;
                endforeach;
                if (!$found) echo "<p>No products in this category yet.</p>";
                ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products available yet.</p>
    <?php endif; ?>

    <div id="feedbackModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"></p>
        </div>
    </div>


    <script src="../js/product.js"></script>


</body>

</html>