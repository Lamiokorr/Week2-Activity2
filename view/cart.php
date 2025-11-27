<?php
session_start();
require_once "../controllers/cart_controller.php";

$ip = $_SERVER['REMOTE_ADDR'];
$c_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;

$items = get_user_cart_ctr($ip, $c_id);
$total = 0.0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 50%, #ffe8f0 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            padding-bottom: 3rem;
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

        /* Container */
        .container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            padding-top: 120px;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 3rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 0.5rem;
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

        /* Cart Card */
        .cart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            animation: fadeInUp 0.6s ease;
            margin-bottom: 2rem;
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

        /* Cart Table */
        .cart-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .cart-table thead {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
        }

        .cart-table th {
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

        .cart-table th:first-child {
            border-radius: 15px 0 0 0;
        }

        .cart-table th:last-child {
            border-radius: 0 15px 0 0;
        }

        .cart-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #ffe0ec;
        }

        .cart-table tbody tr:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            transform: scale(1.01);
        }

        .cart-table td {
            padding: 1.5rem 1rem;
            vertical-align: middle;
            color: #333;
            font-style: oblique;
            font-weight: 500;
        }

        .cart-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(233, 30, 99, 0.15);
        }

        /* Quantity Input */
        .qty-input {
            width: 80px;
            padding: 0.5rem;
            border: 2px solid #ffe0ec;
            border-radius: 10px;
            font-weight: 600;
            font-style: oblique;
            text-align: center;
            transition: all 0.3s ease;
        }

        .qty-input:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
        }

        /* Remove Button */
        .remove-btn {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.3);
        }

        .remove-btn:hover {
            background: linear-gradient(135deg, #c2185b 0%, #ad1457 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.4);
        }

        /* Total Section */
        .cart-total {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin-top: 1rem;
            text-align: right;
        }

        .cart-total strong {
            font-size: 1.8rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: oblique;
        }

        /* Action Buttons */
        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .actions button,
        .actions a button {
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-continue {
            background: white;
            color: #ff6b35;
            border: 2px solid #ff6b35;
            box-shadow: 0 3px 10px rgba(255, 107, 53, 0.2);
        }

        .btn-continue:hover {
            background: #ff6b35;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-empty {
            background: white;
            color: #e91e63;
            border: 2px solid #e91e63;
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.2);
        }

        .btn-empty:hover {
            background: #e91e63;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }

        .btn-checkout {
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .btn-checkout:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
        }

        .empty-cart p {
            color: #e91e63;
            font-size: 1.3rem;
            font-style: oblique;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .empty-cart a {
            display: inline-block;
            padding: 0.9rem 2rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }

        .empty-cart a:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
                padding-top: 100px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .cart-card {
                padding: 1rem;
                overflow-x: auto;
            }

            .cart-table {
                min-width: 600px;
            }

            .actions {
                flex-direction: column;
            }

            .actions button,
            .actions a button {
                width: 100%;
            }

            .navbar {
                padding: 0.6rem 1rem;
            }

            .navbar-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
     <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="../index.php" class="navbar-brand">
            <img src="../assets/images/logo.png" alt="KultureKart" class="navbar-logo">
            <h2 class="navbar-title">KultureKart</h2>
        </a>
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="all_product.php">Products</a>
            <a href="../login/logout.php">Logout</a>
        </div>
    </nav>
<h1>Your Cart</h1>

<?php if (empty($items)): ?>
    <p>Your cart is empty. <a href="all_product.php">Continue shopping</a></p>
<?php else: ?>

<table class="cart-table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Subtotal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="cart-items">
    <?php foreach ($items as $it): 
        $subtotal = floatval($it['product_price']) * intval($it['qty']);
        $total += $subtotal;
    ?>
        <tr data-pid="<?php echo $it['p_id']; ?>">
            <td class="cart-image"><img src="../product/<?php echo htmlspecialchars($it['product_image']); ?>" alt=""></td>
            <td><?php echo htmlspecialchars($it['product_title']); ?></td>
            <td>GHS <?php echo number_format($it['product_price'],2); ?></td>
            <td>
                <input type="number" min="1" value="<?php echo $it['qty']; ?>" class="qty-input" data-pid="<?php echo $it['p_id']; ?>">
            </td>
            <td class="subtotal">GHS <?php echo number_format($subtotal,2); ?></td>
            <td>
                <button class="remove-btn" data-pid="<?php echo $it['p_id']; ?>">Remove</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div style="width:90%; margin: 10px auto; text-align:right;">
    <strong>Total: GHS <?php echo number_format($total,2); ?></strong>
</div>

<div class="actions">
    <a href="all_product.php"><button>Continue Shopping</button></a>
    <button id="empty-cart">Empty Cart</button>
    <a href="checkout.php"><button id="checkout-btn">Proceed to Checkout</button></a>
</div>

<?php endif; ?>

<script src="../js/cart.js"></script>
</body>
</html>
