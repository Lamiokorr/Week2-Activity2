<?php
require_once("../settings/core.php");
require_once("../controllers/brand_controller.php");
require_once("../controllers/category_controller.php");

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

$user_id = $_SESSION['customer_id'];
$brands = get_all_brands_ctr($user_id);
$categories = get_all_categories_ctr($user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Brands</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <h2 class="text-center mb-4">Brand Management</h2>

        <!-- CREATE -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Add New Brand</div>
            <div class="card-body">
                <form action="../actions/add_brand_action.php" method="POST" class="row g-3">
                    <div class="col-md-6">
                        <label for="brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="brand_name" id="brand_name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['cat_id']; ?>">
                                    <?= htmlspecialchars($category['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add Brand</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RETRIEVE + UPDATE + DELETE -->
        <?php
        $brandsByCategory = [];
        foreach ($brands as $brand) {
            $brandsByCategory[$brand['cat_name']][] = $brand;
        }

        if (!empty($brandsByCategory)):
            foreach ($brandsByCategory as $category => $brandList): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <strong><?= htmlspecialchars($category); ?></strong>
                    </div>
                    <div class="card-body">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($brandList as $i => $brand): ?>
                                    <tr>
                                        <td><?= $i + 1; ?></td>
                                        <td><?= htmlspecialchars($brand['brand_name']); ?></td>
                                        <td>
                                            <!-- UPDATE button triggers inline form -->
                                            <form action="../actions/update_brand_action.php" method="POST" class="d-inline">
                                                <input type="hidden" name="brand_id" value="<?= $brand['brand_id']; ?>">
                                                <input type="text" name="brand_name" value="<?= htmlspecialchars($brand['brand_name']); ?>" class="form-control d-inline w-auto" required>
                                                <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                            </form>

                                            <!-- DELETE -->
                                            <form action="../actions/delete_brand_action.php" method="POST" class="d-inline">
                                                <input type="hidden" name="brand_id" value="<?= $brand['brand_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this brand?');">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach;
        else: ?>
            <div class="alert alert-warning">No brands found. Add one above!</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/brand.js"></script>
</body>

</html>