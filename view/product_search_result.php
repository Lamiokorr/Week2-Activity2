<?php
require_once "../controllers/product_controller.php";

// GET SEARCH QUERY 
$search_query = isset($_GET['search']) ? trim($_GET['search']) : null;

// Get Filters
$cat_filter   = isset($_GET['category']) ? $_GET['category'] : null;
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : null;

// DETERMINE WHICH RESULTS TO SHOW 
if ($search_query) {
    $products = search_products_ctr($search_query);

    // If user applied additional filters over search results:
    if ($cat_filter) {
        $products = array_filter($products, function ($p) use ($cat_filter) {
            return $p['product_cat'] == $cat_filter;
        });
    }

    if ($brand_filter) {
        $products = array_filter($products, function ($p) use ($brand_filter) {
            return $p['product_brand'] == $brand_filter;
        });
    }
} else {
    echo "No search term provided.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Search Results</title>

    <style>
        .search-header {
            margin-bottom: 20px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .product-card {
            border: 1px solid #ccc;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }

        img {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }

        .filters form {
            display: inline-block;
            margin-right: 10px;
        }

        a.back {
            display: inline-block;
            margin-top: 20px;
        }

        /* === PRODUCT GRID === */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }

        /* === PRODUCT CARD === */
        .product-card {
            background: #fff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid #eee;
        }

        /* Hover effect (smooth lift) */
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 18px rgba(0, 0, 0, 0.15);
        }

        /* Product image */
        .product-card img {
            width: 180px;
            height: 180px;
            object-fit: contain;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        /* image zoom on hover */
        .product-card:hover img {
            transform: scale(1.07);
        }

        /* Product title */
        .product-card h3 {
            font-size: 1.1rem;
            color: #333;
            margin: 10px 0;
            font-weight: 600;
        }

        /* Price */
        .product-card p {
            margin: 5px 0;
            color: #555;
        }

        /* Add to Cart Button */
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #1e90ff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.25s ease;
        }

        .btn:hover {
            background: #0066cc;
        }

        /* Fade animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Pagination Styling */
        .pagination {
            margin: 25px auto;
            text-align: center;
        }

        .page-btn {
            padding: 7px 15px;
            border: none;
            background: #1e90ff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin: 0 5px;
            font-weight: 600;
            transition: background 0.25s ease;
        }

        .page-btn:hover {
            background: #0066cc;
        }

        /* Filter Dropdowns */
        .filters select {
            padding: 8px;
            margin-right: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            cursor: pointer;
            transition: border 0.25s ease;
        }

        .filters select:hover {
            border-color: #1e90ff;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="../index.php" class="navbar-brand">
            <img src="../assets/logo.png" alt="KultureKart" class="navbar-logo">
            <h2 class="navbar-title">KultureKart</h2>
        </a>
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="all_product.php">Products</a>
            <a href="cart.php">My Cart</a>
            <a href="../login/logout.php">Logout</a>
        </div>
    </nav>

    <div class="header-section">
        <h1>Search Results</h1>
        <p class="search-query">Searching for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></p>
        <p class="results-count"><?php echo count($products); ?> result(s) found</p>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <a href="all_product.php" class="back-link">Back to All Products</a>


            <div class="filters">

                <!-- CATEGORY FILTER FOR SEARCH RESULTS -->
                <form method="GET" action="product_search_result.php">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">

                    <select name="category" onchange="this.form.submit()">
                        <option disabled selected>Filter by Category</option>
                        <?php
                        require_once "../controllers/category_controller.php";
                        $categories = get_all_categories_ctr();

                        foreach ($categories as $cat) {
                            echo "<option value='{$cat['cat_id']}'>{$cat['cat_name']}</option>";
                        }
                        ?>
                    </select>
                </form>

                <!-- BRAND FILTER FOR SEARCH RESULTS -->
                <form method="GET" action="product_search_result.php">
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">

                    <select name="brand" onchange="this.form.submit()">
                        <option disabled selected>Filter by Brand</option>
                        <?php
                        require_once "../controllers/brand_controller.php";
                        $brands = get_all_brands_ctr();

                        foreach ($brands as $brand) {
                            echo "<option value='{$brand['brand_id']}'>{$brand['brand_name']}</option>";
                        }
                        ?>
                    </select>
                </form>

            </div>

            <hr>

            <!-- PRODUCT GRID -->
            <div class="product-grid">
                <?php
                if (empty($products)) {
                    echo "<div class='empty-state'><p>No products matched your search or filters.</p></div>";
                } else {
                    foreach ($products as $product) {
                        $img = "../product/" . $product['product_image'];
                        $id = $product['product_id'];
                        echo "
            <div class='product-card'>
                <div class='product-image'>
                    <a href='single_product.php?id=$id'>
                        <img src='$img' alt='{$product['product_title']}'>
                    </a>
                </div>
                <div class='product-info'>
                    <h3>{$product['product_title']}</h3>
                    <p class='product-price'>GHS {$product['product_price']}</p>
                    <p><strong>Category:</strong> {$product['product_cat']}</p>
                    <p><strong>Brand:</strong> {$product['product_brand']}</p>
                    <a href='cart.php?product_id=$id' class='btn-cart'>Add to Cart</a>
                </div>
            </div>
            ";
                    }
                }
                ?>
            </div>

            <!-- Footer -->
            <div class="footer">
                © 2025 KultureKart. All rights reserved.
            </div>
</body>

</html>