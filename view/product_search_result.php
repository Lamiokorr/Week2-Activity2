<?php
require_once "../controllers/product_controller.php";

// --- GET SEARCH QUERY 
$search_query = isset($_GET['search']) ? trim($_GET['search']) : null;

// Get Filters
$cat_filter   = isset($_GET['category']) ? $_GET['category'] : null;
$brand_filter = isset($_GET['brand']) ? $_GET['brand'] : null;

// --- DETERMINE WHICH RESULTS TO SHOW ---
if ($search_query) {
    $products = search_products_ctr($search_query);

    // If user applied additional filters over search results:
    if ($cat_filter) {
        $products = array_filter($products, function($p) use ($cat_filter) {
            return $p['product_cat'] == $cat_filter;
        });
    }

    if ($brand_filter) {
        $products = array_filter($products, function($p) use ($brand_filter) {
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
    </style>
</head>
<body>

<h1>Search Results for: "<?php echo htmlspecialchars($search_query); ?>"</h1>

<div class="search-header">
    <p><?php echo count($products); ?> result(s) found</p>
</div>

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

<div class="product-grid">
    <?php
    if (empty($products)) {
        echo "<p>No products matched your search or filters.</p>";
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

<br>
<a href="all_product.php" class="back">← Back to All Products</a>

</body>
</html>
