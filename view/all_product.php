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

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .page-btn {
            padding: 6px 12px;
            margin: 0 5px;
            border: none;
            background: #1e90ff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }

        .page-btn:hover {
            background: #0066cc;
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
    box-shadow: 0px 4px 10px rgba(0,0,0,0.08);
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid #eee;
}

/* Hover effect (smooth lift) */
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 18px rgba(0,0,0,0.15);
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
    border-color: #275c91ff;
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

                <a href='#' class='btn'>Add to Cart</a>
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
    loadProducts(1);  // default pagination loader
});
</script>

</body>
</html>
