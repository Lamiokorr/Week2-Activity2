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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            min-height: 100vh;
        }

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .search-query {
            font-size: 1.3rem;
            font-style: oblique;
            opacity: 0.95;
            margin-bottom: 1rem;
        }

        .results-count {
            font-size: 1.1rem;
            font-style: oblique;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Main Content */
        .main-content {
            background: linear-gradient(to bottom, #f5e6ed 0%, #fce4ec 50%, #fff0f5 100%);
            min-height: calc(100vh - 250px);
            padding: 2rem 0 3rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
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
            padding: 0.7rem 1.5rem;
            background: white;
            border-radius: 25px;
            box-shadow: 0 2px 10px rgba(233, 30, 99, 0.15);
        }

        .back-link:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .back-link::before {
            content: '← ';
        }

        /* Filter Section */
        .filters {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filters select {
            padding: 0.7rem 1.5rem;
            border: 2px solid #e91e63;
            border-radius: 25px;
            font-style: oblique;
            font-weight: 600;
            color: #c2185b;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(233, 30, 99, 0.1);
        }

        .filters select:hover {
            border-color: #ff6b35;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);
            transform: translateY(-2px);
        }

        .filters select:focus {
            outline: none;
            border-color: #ff6b35;
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Product Card */
        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .product-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.1);
        }

        .product-info {
            padding: 1.5rem;
            background: white;
        }

        .product-info h3 {
            color: #e91e63;
            font-size: 1.1rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 0.8rem;
            line-height: 1.3;
        }

        .product-info p {
            color: #666;
            font-size: 0.9rem;
            margin: 0.3rem 0;
            font-style: oblique;
        }

        .product-info strong {
            color: #ff6b35;
            font-weight: 600;
        }

        .product-price {
            font-size: 1.3rem !important;
            color: #ff6b35 !important;
            font-weight: 700 !important;
            margin: 0.8rem 0 !important;
        }

        .btn-cart {
            width: 100%;
            padding: 0.8rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-cart:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #e91e63;
            font-style: oblique;
            font-size: 1.3rem;
            font-weight: 600;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
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

        /* Adjust header to account for fixed navbar */
        .header-section {
            margin-top: 65px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            font-style: oblique;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }

            .search-query {
                font-size: 1.1rem;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .navbar {
                padding: 0.6rem 1rem;
            }

            .navbar-title {
                font-size: 1.2rem;
            }

            .navbar-menu {
                gap: 0.5rem;
            }

            .navbar-menu a {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
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
                        // Handle image path - support multiple formats
                        $img_path = isset($product['product_image']) ? trim($product['product_image']) : '';
                        if (empty($img_path)) {
                            $img = "../images/placeholder.jpg";
                        } elseif (strpos($img_path, 'uploads/') === 0) {
                            // Path like: uploads/u1/p2/image.jpg
                            $img = "../" . $img_path;
                        } elseif (strpos($img_path, 'images/') === 0) {
                            // Path like: images/product/image.jpg
                            $img = "../" . $img_path;
                        } elseif (strpos($img_path, '/') === 0 || strpos($img_path, '../') === 0) {
                            // Already a full/relative path
                            $img = $img_path;
                        } else {
                            // Just filename - try images/product/ first (admin format), then product/
                            $img = "../images/product/" . $img_path;
                        }
                        $id = $product['product_id'];
                        $cat_name = isset($product['cat_name']) ? htmlspecialchars($product['cat_name']) : 'N/A';
                        $brand_name = isset($product['brand_name']) ? htmlspecialchars($product['brand_name']) : 'N/A';
                        // Ensure image path is properly formatted
                        $img_escaped = htmlspecialchars($img, ENT_QUOTES, 'UTF-8');
                        $placeholder_escaped = htmlspecialchars("../images/placeholder.jpg", ENT_QUOTES, 'UTF-8');
                        echo "
            <div class='product-card'>
                <div class='product-image'>
                    <a href='single_product.php?id=$id'>
                        <img src='$img_escaped' alt='" . htmlspecialchars($product['product_title']) . "' onerror=\"if(this.src != '$placeholder_escaped') { this.src = '$placeholder_escaped'; }\">
                    </a>
                </div>
                <div class='product-info'>
                    <h3>" . htmlspecialchars($product['product_title']) . "</h3>
                    <p class='product-price'>GHS " . number_format($product['product_price'], 2) . "</p>
                    <p><strong>Category:</strong> $cat_name</p>
                    <p><strong>Brand:</strong> $brand_name</p>
                    <button class='btn-cart' onclick='addToCart($id)'>Add to Cart</button>
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
        </div>
    </div>

    <script>
        function addToCart(productId) {
            const formData = new FormData();
            formData.append('p_id', productId);
            formData.append('qty', 1);

            fetch('../actions/add_to_cart_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Product added to cart!');
                    } else {
                        alert('Failed to add product: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding to cart');
                });
        }
    </script>
</body>

</html>