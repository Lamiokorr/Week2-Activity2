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
        .container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            gap: 40px;
        }

        .product-image img {
            width: 350px;
            height: 350px;
            object-fit: contain;
            border: 1px solid #aaa;
            border-radius: 8px;
        }

        .product-details {
            max-width: 600px;
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            margin: 8px 0;
        }

        .add-to-cart {
            display: inline-block;
            padding: 10px 20px;
            background: #1E90FF;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .add-to-cart:hover {
            background: #0066CC;
        }

        .back {
            margin-top: 20px;
            display: inline-block;
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