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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 50%, #ffe8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle pattern overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 60px, rgba(255, 107, 53, 0.02) 60px, rgba(255, 107, 53, 0.02) 120px),
                repeating-linear-gradient(-45deg, transparent, transparent 60px, rgba(233, 30, 99, 0.02) 60px, rgba(233, 30, 99, 0.02) 120px);
            z-index: 0;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 1;
        }

        /* Header styling */
        h2 {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 3rem;
            font-weight: 700;
            font-style: oblique;
            text-align: center;
            margin-bottom: 2rem;
            letter-spacing: -1px;
            animation: fadeInDown 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card styling */
        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease;
            background: white;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(255, 107, 53, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%) !important;
            color: white !important;
            font-weight: 600;
            font-size: 1.2rem;
            font-style: oblique;
            padding: 1.2rem 1.5rem;
            border: none;
        }

        .card-body {
            padding: 2rem 1.5rem;
        }

        /* Form styling */
        .form-label {
            color: #e91e63;
            font-weight: 600;
            font-style: oblique;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #ffe0ec;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-style: oblique;
        }

        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
            outline: none;
        }

        /* Button styling */
        .btn-primary {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8555 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ff8555 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.3);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        /* Table styling */
        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #fff0f5 0%, #ffe8f0 100%);
        }

        .table thead th {
            color: #e91e63;
            font-weight: 700;
            font-style: oblique;
            border: none;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #ffe0ec;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            color: #333;
            font-style: oblique;
        }

        /* Category card with gradient border */
        .card.mb-4 {
            border: 3px solid transparent;
            background-image:
                linear-gradient(white, white),
                linear-gradient(135deg, #ff6b35, #e91e63);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .card-header.bg-info {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%) !important;
        }

        /* Alert styling */
        .alert-warning {
            background: linear-gradient(135deg, #fff8e1 0%, #ffe0f0 100%);
            border: 2px solid #ff6b35;
            border-radius: 15px;
            color: #d84315;
            font-weight: 600;
            font-style: oblique;
            padding: 1.5rem;
        }

        /* Inline form in table */
        .table .form-control {
            display: inline-block;
            width: auto;
            min-width: 200px;
            margin-right: 0.5rem;
            padding: 0.5rem 0.75rem;
        }

        .table .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        /* Add stagger animation to category cards */
        .card.mb-4:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card.mb-4:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card.mb-4:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card.mb-4:nth-child(4) {
            animation-delay: 0.4s;
        }

        /* Decorative corner elements */
        .card::before {
            content: '◈';
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        .card-body {
            position: relative;
            z-index: 1;
        }

        /* Back button styling */
        .btn-back {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid #ff6b35;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            color: #ff6b35;
            font-weight: 600;
            font-style: oblique;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.2);
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.3);
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            .card-body {
                padding: 1.5rem 1rem;
            }

            .table .form-control {
                min-width: 150px;
                margin-bottom: 0.5rem;
            }

            .btn-back {
                top: 10px;
                left: 10px;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body class="bg-light">
     <a href="../index.php" class="btn-back">Back to Home</a>

    <div class="container py-5">
        <h2 class="text-center mb-4">Brand Management</h2>

        <!-- CREATE -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Add New Brand</div>
            <div class="card-body">
                <form id="addBrandForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="name" id="brand_name" required>
                    </div>
                    <div class="col-12">
                        <button id="add-brand" type="submit" class="btn btn-primary">Add Brand</button>
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
                                    <th>ID</th>
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
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/brand.js"></script>
</body>

</html>