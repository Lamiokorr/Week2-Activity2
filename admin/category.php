<?php
require_once("../settings/core.php");
require_once("../controllers/category_controller.php");

if (!isLoggedIn()) {
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cambria', 'Cochin', 'Georgia', 'Times', 'Times New Roman', serif;
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
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 100px;
        }

        .content-wrapper {
            max-width: 850px;
            margin: 0 auto;
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
            margin-bottom: 2.5rem;
            letter-spacing: -1px;
            animation: fadeInDown 0.6s ease;
        }

        h3 {
            color: #e91e63;
            font-size: 1.5rem;
            font-weight: 600;
            font-style: oblique;
            margin-bottom: 1.5rem;
            margin-top: 2rem;
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

        /* Card styling for form */
        .form-card {
            background: white;
            border: none;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease;
            transition: all 0.3s ease;
            border: 3px solid transparent;
            background-image:
                linear-gradient(white, white),
                linear-gradient(135deg, #ff6b35, #e91e63);
            background-origin: border-box;
            background-clip: padding-box, border-box;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(255, 107, 53, 0.2);
        }

        /* Form styling */
        #addCategoryForm {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        input[type="text"] {
            flex: 1;
            min-width: 250px;
            padding: 0.9rem 1.2rem;
            border: 2px solid #ffe0ec;
            border-radius: 12px;
            font-size: 1rem;
            font-style: oblique;
            transition: all 0.3s ease;
            background: #fff;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 53, 0.15);
            transform: scale(1.02);
        }

        input[type="text"]::placeholder {
            color: #ffb3c9;
            font-style: oblique;
        }

        /* Button styling */
        button[type="submit"] {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            border: none;
            border-radius: 12px;
            padding: 0.9rem 2rem;
            color: white;
            font-weight: 600;
            font-style: oblique;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        button[type="submit"]:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        /* Table card */
        .table-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            animation: fadeInUp 0.8s ease;
            overflow: hidden;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 0.5rem;
        }

        thead {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
        }

        th {
            padding: 1.2rem 1rem;
            text-align: left;
            color: white;
            font-weight: 700;
            font-style: oblique;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            border: none;
        }

        th:first-child {
            border-radius: 15px 0 0 0;
        }

        th:last-child {
            border-radius: 0 15px 0 0;
        }

        tbody tr {
            transition: all 0.3s ease;
            background: white;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.1);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #ffe0ec;
            color: #333;
            font-style: oblique;
            font-weight: 500;
        }

        tbody tr:last-child td:first-child {
            border-radius: 0 0 0 15px;
        }

        tbody tr:last-child td:last-child {
            border-radius: 0 0 15px 0;
        }

        /* Action buttons in table */
        td button {
            padding: 0.5rem 1.2rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            font-size: 0.875rem;
        }

        td button:first-of-type {
            background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%);
            color: white;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        td button:first-of-type:hover {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        }

        td button:last-of-type {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.3);
        }

        td button:last-of-type:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #ff6b35;
            font-style: oblique;
            font-weight: 500;
        }

        /* Back button */
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

        /* Decorative elements */
        .form-card::before {
            content: '◈';
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 2rem;
            opacity: 0.1;
            color: #ff6b35;
        }

        .table-card::after {
            content: '❋';
            position: absolute;
            bottom: 15px;
            right: 20px;
            font-size: 2rem;
            opacity: 0.1;
            color: #e91e63;
        }

        .form-card,
        .table-card {
            position: relative;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                padding-top: 80px;
            }

            h2 {
                font-size: 2rem;
            }

            h3 {
                font-size: 1.2rem;
            }

            .form-card,
            .table-card {
                padding: 1.5rem;
            }

            #addCategoryForm {
                flex-direction: column;
                align-items: stretch;
            }

            input[type="text"] {
                min-width: 100%;
            }

            button[type="submit"] {
                width: 100%;
            }

            table {
                font-size: 0.9rem;
            }

            th,
            td {
                padding: 0.75rem 0.5rem;
            }

            td button {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
                margin-bottom: 0.3rem;
            }

            .btn-back {
                top: 10px;
                left: 10px;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .site-logo {
                position: fixed;
                top: 20px;
                left: 20px;
                width: 120px;
                height: auto;
                z-index: 1001;
                transition: all 0.3s ease;
                filter: drop-shadow(0 4px 10px rgba(255, 107, 53, 0.2));
            }

            .site-logo:hover {
                transform: scale(1.05);
                filter: drop-shadow(0 6px 15px rgba(233, 30, 99, 0.3));
            }
        }
    </style>
</head>

<body>
    <a href="../index.php" class="btn-back" style="left: 160px;">Back to Home</a>
    <div class="container">
        <h2>Category Management</h2>

        <!-- CREATE -->
        <div class="form-card">
            <h3>Add New Category</h3>
            <form id="addCategoryForm">
                <input type="text" name="name" placeholder="Enter category name" required>
                <button type="submit">Add Category</button>
            </form>
        </div>

        <!-- RETRIEVE + UPDATE + DELETE -->
        <div class="table-card">
            <h3>Categories Available</h3>
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
                                    <button onclick="updateCategory(<?php echo $cat['cat_id']; ?>, '<?php echo $cat['cat_name']; ?>')">Update</button>
                                    <button onclick="deleteCategory(<?php echo $cat['cat_id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No categories found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/category.js"></script>
</body>

</html>