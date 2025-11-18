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
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Products</title>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #aaa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        img {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }

        .filters {
            margin-bottom: 20px;
        }

        .filters form {
            display: inline-block;
            margin-right: 10px;
        }

        .search-bar input {
            padding: 6px;
        }
    </style>
</head>
<body>

<h1>All Products</h1>

<div class="filters">

    <!-- SEARCH FORM -->
    <form class="search-bar" method="GET" action="../actions/product_actions.php">
        <input type="text" name="search" placeholder="Search products..." required>
        <button name ="search_btn" type="submit">Search</button>
    </form>

    <!-- CATEGORY FILTER -->
    <form method="GET" action="../actions/product_actions.php">
        <select name="category_filter" onchange="this.form.submit()">
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

    <!-- BRAND FILTER -->
    <form method="GET" action="../actions/product_actions.php">
        <select name="brand_filter" onchange="this.form.submit()">
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

                <a href='#' class='btn'>Add to Cart</a>
            </div>
            ";
        }
    }
    ?>
</div>

</body>
</html>
