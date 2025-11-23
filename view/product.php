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
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 50%, #ffe8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 3rem;
        }

        /* Subtle pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 60px, rgba(255, 107, 53, 0.02) 60px, rgba(255, 107, 53, 0.02) 120px),
                repeating-linear-gradient(-45deg, transparent, transparent 60px, rgba(233, 30, 99, 0.02) 60px, rgba(233, 30, 99, 0.02) 120px);
            z-index: 0;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 100px;
        }

        /* Logo styling */
        .site-logo {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 120px;
            height: auto;
            z-index: 1001;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 10px rgba(255, 107, 53, 0.2));
        }

        .site-logo:hover {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 15px rgba(233, 30, 99, 0.3));
        }

        /* Back button */
        .btn-back {
            position: fixed;
            top: 20px;
            left: 160px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid #ff6b35;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            color: #ff6b35;
            font-weight: 600;
            font-style: oblique;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
        }

        /* Header styling */
        h1 {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 3rem;
            font-weight: 700;
            font-style: oblique;
            text-align: center;
            margin-bottom: 2.5rem;
            letter-spacing: -1px;
            animation: fadeInDown 0.6s ease;
        }

        h2 {
            color: #e91e63;
            font-size: 2rem;
            font-weight: 600;
            font-style: oblique;
            margin-top: 3rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid;
            border-image: linear-gradient(to right, #ff6b35, #e91e63) 1;
            animation: fadeInUp 0.6s ease;
        }

        h3 {
            color: #ff6b35;
            font-size: 1.5rem;
            font-weight: 600;
            font-style: oblique;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Card */
        .form-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            margin-bottom: 3rem;
            animation: fadeInUp 0.6s ease;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            border: 3px solid transparent;
            background-image: 
                linear-gradient(white, white),
                linear-gradient(135deg, #ff6b35, #e91e63);
            background-origin: border-box;
            background-clip: padding-box, border-box;
            position: relative;
        }

        .form-card::before {
            content: '◈';
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 2rem;
            opacity: 0.1;
            color: #ff6b35;
        }

        /* Form styling */
        form label {
            color: #e91e63;
            font-weight: 600;
            font-style: oblique;
            margin-bottom: 0.5rem;
            display: block;
            margin-top: 1rem;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border: 2px solid #ffe0ec;
            border-radius: 12px;
            font-style: oblique;
            transition: all 0.3s ease;
            background: white;
        }

        form input:focus,
        form select:focus,
        form textarea:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.15);
        }

        form textarea {
            resize: vertical;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        form button[type="submit"] {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            border: none;
            border-radius: 12px;
            padding: 1rem 3rem;
            color: white;
            font-weight: 600;
            font-style: oblique;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            margin-top: 1.5rem;
            width: 100%;
        }

        form button[type="submit"]:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        /* Product Grid */
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.15);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(255, 107, 53, 0.25);
            border-color: #ff6b35;
        }

        .product-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card-body {
            padding: 1.5rem;
        }

        .product-card h4 {
            color: #e91e63;
            font-size: 1.3rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 0.8rem;
            line-height: 1.3;
        }

        .product-card p {
            color: #555;
            margin: 0.5rem 0;
            font-style: oblique;
            font-size: 0.95rem;
        }

        .product-card p strong {
            color: #ff6b35;
            font-weight: 600;
        }

        .product-price {
            font-size: 1.4rem !important;
            color: #ff6b35 !important;
            font-weight: 700 !important;
            margin: 1rem 0 !important;
        }

        /* Product Card Buttons */
        .product-card-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.2rem;
            padding-top: 1rem;
            border-top: 1px solid #ffe0ec;
        }

        .edit-btn {
            flex: 1;
            background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        .edit-btn:hover {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        }

        .delete-btn {
            flex: 1;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.3);
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
            z-index: 2000;
            min-width: 300px;
            text-align: center;
        }

        .modal-content p {
            color: #333;
            font-size: 1.2rem;
            font-weight: 600;
            font-style: oblique;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #ff6b35;
            font-style: oblique;
            font-size: 1.2rem;
            font-weight: 500;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(233, 30, 99, 0.1);
        }

        /* Category badge */
        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-style: oblique;
            margin-bottom: 1rem;
            box-shadow: 0 3px 10px rgba(255, 107, 53, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                padding-top: 80px;
            }

            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .product-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .site-logo {
                width: 80px;
            }

            .btn-back {
                left: 110px;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .product-container {
                grid-template-columns: 1fr;
            }
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
                    if ($product['product_cat'] == $cat['cat_id']):
                        $found = true;
                ?>
                        <div class="product-card">
                            <img src="../<?= htmlspecialchars($product['product_image']) ?>" alt="Product Image">
                            <h4><?= htmlspecialchars($product['product_title']) ?></h4>
                            <p><strong>Brand:</strong> <?= htmlspecialchars($product['product_brand']) ?></p>
                            <p><strong>Price:</strong> GHS <?= htmlspecialchars($product['product_price']) ?></p>
                            <p><strong>Keywords:</strong> <?= htmlspecialchars($product['product_keywords']) ?></p>

                            <button class="edit-btn"
                                data-id="<?= $product['product_id'] ?>"
                                data-title="<?= htmlspecialchars($product['product_title']) ?>"
                                data-price="<?= htmlspecialchars($product['product_price']) ?>"
                                data-desc="<?= htmlspecialchars($product['product_desc']) ?>"
                                data-keywords="<?= htmlspecialchars($product['product_keywords']) ?>"
                                data-cat="<?= $product['product_cat'] ?>"
                                data-brand="<?= $product['product_brand'] ?>"
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