<?php
require_once "../controllers/product_controller.php";
require_once "../settings/core.php"; // For session management

// Get Filters
$search_query = isset($_GET['search']) ? $_GET['search'] : null;
$cat_filter   = isset($_GET['category']) ? $_GET['category'] : null;
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : null;

//Fetch Products Based On Filters
if ($search_query) {
    $products = search_products_ctr($search_query);
} elseif ($cat_filter) {
    $products = filter_products_by_category_ctr($cat_filter);
} elseif ($brand_filter) {
    $products = filter_products_by_brand_ctr($brand_filter);
} else {
    $products = view_all_products_ctr();
}

// PAGINATION SETTINGS
$limit = 10; // products per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total products
$total_products = count_all_products_ctr();
$total_pages = ceil($total_products / $limit);

// If search or filters are applied, skip pagination
if ($search_query || $cat_filter || $brand_filter) {
    // normal (unpaginated) results
} else {
    // Load paginated products
    $products = get_products_paginated_ctr($limit, $offset);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
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

        .header-section p {
            font-size: 1.1rem;
            font-style: oblique;
            opacity: 0.95;
            margin-bottom: 2rem;
        }

        /* Search Bar */
        .search-container {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 0.5rem;
        }

        .search-container input {
            flex: 1;
            padding: 0.9rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-style: oblique;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .search-container input:focus {
            outline: none;
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }

        .search-container button {
            padding: 0.9rem 2rem;
            background: #ff6b35;
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .search-container button:hover {
            background: #ff8555;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
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

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-top: 3rem;
            padding: 2rem 0;
        }

        .pagination a,
        .pagination strong {
            padding: 0.7rem 1.5rem;
            background: white;
            color: #e91e63;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            font-style: oblique;
            box-shadow: 0 2px 10px rgba(233, 30, 99, 0.15);
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .pagination strong {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
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

            .search-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="../index.php" class="navbar-brand">
            <img src="../assets/images/logo.png" alt="KultureKart" class="navbar-logo">
            <!--<h2 class="navbar-title">KultureKart</h2> -->
        </a>
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="cart.php">My Cart</a>
            <a href="../login/logout.php">Logout</a>
        </div>
    </nav>

    < <!-- Header Section -->
    <div class="header-section">
        <h1>Welcome to Our Store</h1>
        <p>Discover Authentic African Artisan Crafts</p>
        
        <div class="search-container">
            <form method="GET" action="../actions/product_actions.php" style="display: flex; width: 100%; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search for products..." required>
                <button name="search_btn" type="submit">Search</button>
            </form>
        </div>
    </div>

        <!-- CATEGORY FILTER -->
        <form method="GET" action="../actions/product_actions.php">
            <select id="categoryFilter" onchange="applyFilters()">
                <option value="">All Categories</option>
                <?php
                require_once "../controllers/category_controller.php";
                $categories = get_all_categories_ctr();
                foreach ($categories as $cat) {
                    echo "<option value='{$cat['cat_id']}'>{$cat['cat_name']}</option>";
                }
                ?>
            </select>
        </form>

        <!-- BRAND FILTER -->
        <form method="GET" action="../actions/product_actions.php">
            <select id="brandFilter" onchange="applyFilters()">
                <option value="">All Brands</option>
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
            echo "<p>No products found.</p>";
        } else {
            foreach ($products as $product) {
                $img = "../product/" . $product['product_image'];
                $id = $product['product_id'];

                echo "
            <div class='product-card'>
                <a href='single_product.php?id=$id'>
                    <img src='$img' alt='{$product['product_title']}'>
                </a>
                <h3>{$product['product_title']}</h3>
                <p><strong>Price:</strong> \${$product['product_price']}</p>
                <p><strong>Category:</strong> {$product['product_cat']}</p>
                <p><strong>Brand:</strong> {$product['product_brand']}</p>

                <a href='cart.php' class='btn'>Add to Cart</a>
            </div>
            ";
            }
        }
        ?>
    </div>
    <?php if (!$search_query && !$cat_filter && !$brand_filter): ?>
        <div style="margin-top: 20px; text-align:center;">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">← Previous</a>
            <?php endif; ?>

            <strong> Page <?php echo $page; ?> of <?php echo $total_pages; ?> </strong>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
    <!-- Footer -->
    <div class="footer">
        © 2025 KultureKart. All rights reserved.
    </div>

    <script>
        function applyFilters(page = 1) {
            const category = document.getElementById("categoryFilter").value;
            const brand = document.getElementById("brandFilter").value;

            const container = document.getElementById("product-container");
            container.innerHTML = "<p>Loading products...</p>";

            fetch(`../actions/filter_products_ajax.php?page=${page}&category=${category}&brand=${brand}`)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                })
                .catch(err => {
                    container.innerHTML = "<p>Error loading products.</p>";
                });
        }

        // LOAD FIRST PAGE ON PAGE LOAD
        document.addEventListener("DOMContentLoaded", function() {
            loadProducts(1); // default pagination loader
        });
    </script>

</body>

</html>