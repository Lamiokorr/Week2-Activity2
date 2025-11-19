<?php
require_once "../controllers/product_controller.php";

$category = isset($_GET['category']) ? intval($_GET['category']) : null;
$brand    = isset($_GET['brand']) ? intval($_GET['brand']) : null;
$page     = isset($_GET['page']) ? intval($_GET['page']) : 1;

$limit = 10;
$offset = ($page - 1) * $limit;

// Step 1 — Load the raw product list based on filters
if ($category && !$brand) {
    $products = filter_products_by_category_ctr($category);
} elseif ($brand && !$category) {
    $products = filter_products_by_brand_ctr($brand);
} elseif ($brand && $category) {
    // composite filter
    $allCat = filter_products_by_category_ctr($category);
    $products = array_filter($allCat, function($p) use ($brand) {
        return $p['product_brand'] == $brand;
    });
} else {
    $products = view_all_products_ctr();
}

// Step 2 — PAGINATION on filtered results
$total_products = count($products);
$total_pages = ceil($total_products / $limit);

$products = array_slice($products, $offset, $limit);

// Step 3 — Build HTML
ob_start();

if (empty($products)) {
    echo "<p>No products found for selected filters.</p>";
} else {
    echo '<div class="product-grid">';
    foreach ($products as $product) {
        $img = "../product/" . $product['product_image'];
        $id  = $product['product_id'];

        echo "
        <div class='product-card'>
            <a href='single_product.php?id=$id'>
                <img src='$img'>
            </a>
            <h3>{$product['product_title']}</h3>
            <p><strong>Price:</strong> \${$product['product_price']}</p>
            <p><strong>Category:</strong> {$product['product_cat']}</p>
            <p><strong>Brand:</strong> {$product['product_brand']}</p>
            <a href='#' class='btn'>Add to Cart</a>
        </div>
        ";
    }
    echo "</div>";
}

// Pagination buttons
echo "<div class='pagination'>";
if ($page > 1) {
    echo "<button class='page-btn' onclick='applyFilters(" . ($page - 1) . ")'>← Previous</button>";
}

echo "<span> Page $page of $total_pages </span>";

if ($page < $total_pages) {
    echo "<button class='page-btn' onclick='applyFilters(" . ($page + 1) . ")'>Next →</button>";
}
echo "</div>";

echo ob_get_clean();
?>
