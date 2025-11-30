<?php
require_once "../controllers/product_controller.php";
require_once "../settings/core.php";

//Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid product ID.";
    exit;
}

$product_id = intval($_GET['id']);

//Fetch product details
$product = view_single_product_ctr($product_id);

if (!$product) {
    echo "Product not found.";
    exit;
}

// Prepare image path
$image_path = "../product/" . $product['product_image'];

?>

<!DOCTYPE html>
<html>

<head>
    <title><?php echo $product['product_title']; ?></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 50%, #ffe8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
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

        /* Navigation Bar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid rgba(255, 107, 53, 0.2);
            padding: 0.8rem 2rem;
            box-shadow: 0 4px 20px rgba(233, 30, 99, 0.15);
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 0.8rem;
        }

        .navbar-logo {
            width: 45px;
            height: 45px;
        }

        .navbar-title {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.5rem;
            font-weight: 700;
            font-style: oblique;
            margin: 0;
        }

        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #ff6b35;
            font-weight: 600;
            font-style: oblique;
            padding: 0.5rem 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
        }

        /* Container */
        .container-main {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 120px;
        }

        /* Back Link */
        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #e91e63;
            transform: translateX(-5px);
        }

        .back-link::before {
            content: '← ';
        }

        /* Product Container */
        .product-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            animation: fadeInUp 0.6s ease;
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

        /* Product Image Section */
        .product-image {
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.2);
            transition: transform 0.3s ease;
        }

        .product-image img:hover {
            transform: scale(1.03);
        }

        /* Product Details Section */
        .product-details {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h1 {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .price {
            font-size: 2.5rem;
            color: #ff6b35;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 1.5rem;
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            color: #e91e63;
            font-weight: 700;
            font-style: oblique;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .info-value {
            color: #555;
            font-style: oblique;
            font-size: 1rem;
            line-height: 1.6;
            padding: 0.5rem 0;
        }

        .info-badge {
            display: inline-block;
            padding: 0.5rem 1.2rem;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            color: #e91e63;
            border-radius: 20px;
            font-weight: 600;
            font-style: oblique;
            border: 2px solid #ffe0ec;
            margin-right: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Description Box */
        .description-box {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid #ff6b35;
            margin: 1.5rem 0;
        }

        .description-box p {
            color: #555;
            font-style: oblique;
            line-height: 1.8;
            margin: 0;
        }

        /* Keywords */
        .keywords {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .keyword-tag {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            font-style: oblique;
        }

        /* Add to Cart Button */
        .btn-add-cart {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: 700;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2rem;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-add-cart:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.5);
            color: white;
        }

        .btn-add-cart::before {
            content: '🛒 ';
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .quantity-label {
            color: #e91e63;
            font-weight: 700;
            font-style: oblique;
            font-size: 1.1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #ff6b35;
            background: white;
            color: #ff6b35;
            border-radius: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            border-color: #e91e63;
        }

        .quantity-input {
            width: 80px;
            padding: 0.5rem;
            border: 2px solid #ffe0ec;
            border-radius: 10px;
            text-align: center;
            font-weight: 700;
            font-style: oblique;
            font-size: 1.1rem;
            color: #e91e63;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #ff6b35;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .product-container {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 2rem;
            }

            .product-details h1 {
                font-size: 2rem;
            }

            .price {
                font-size: 2rem;
            }

            .container-main {
                padding: 1rem;
                padding-top: 100px;
            }

            .navbar {
                padding: 0.6rem 1rem;
            }

            .navbar-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .product-image img {
                height: 300px;
            }

            .product-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <h1>Product Details</h1>

    <div class="container">

        <!-- PRODUCT IMAGE -->
        <div class="product-image">
            <img src="<?php echo $image_path; ?>" alt="<?php echo $product['product_title']; ?>">
        </div>

        <!-- PRODUCT DETAILS -->
        <div class="product-details">
            <h2><?php echo $product['product_title']; ?></h2>

            <p><strong>Price:</strong> $<?php echo $product['product_price']; ?></p>
            <p><strong>Category:</strong> <?php echo $product['product_cat']; ?></p>
            <p><strong>Brand:</strong> <?php echo $product['product_brand']; ?></p>

            <p><strong>Description:</strong><br>
                <?php echo nl2br($product['product_desc']); ?>
            </p>

            <p><strong>Keywords:</strong> <?php echo $product['product_keywords']; ?></p>

            <br>
            <a href="#" class="add-to-cart">Add to Cart</a>

            <br><br>
            <a href="all_product.php" class="back">← Back to All Products</a>
        </div>
    </div>

</body>

</html>