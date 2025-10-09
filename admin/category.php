<?php
require_once("../settings/core.php");
require_once("../controllers/category_controller.php");

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['customer_id'];
$categories = get_all_categories_ctr($user_id); // fetch categories for current user
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <script src="../js/category.js" defer></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { color: #333; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 6px; margin-right: 10px; }
        button { padding: 6px 12px; margin-left: 5px; }
        table { border-collapse: collapse; width: 60%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <h2>Category Management</h2>

    <!-- CREATE -->
    <h3>Add New Category</h3>
    <form id="addCategoryForm">
        <input type="text" name="name" placeholder="Enter category name" required>
        <button type="submit">Add Category</button>
    </form>

    <!-- RETRIEVE + UPDATE + DELETE -->
    <h3>Your Categories</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoryList">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cat['cat_id']); ?></td>
                        <td><?php echo htmlspecialchars($cat['cat_name']); ?></td>
                        <td>
                            <button onclick="updateCategory(<?php echo $cat['cat_id']; ?>, '<?php echo $cat['name']; ?>')">Update</button>
                            <button onclick="deleteCategory(<?php echo $cat['cat_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">No categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
