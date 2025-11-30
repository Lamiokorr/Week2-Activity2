<?php
session_start();
require_once '../settings/core.php';


// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login/login.php');
    exit();
}

// Check if cart is not empty
require_once "../controllers/cart_controller.php";
$ip = $_SERVER['REMOTE_ADDR'];
$customer_id = isset($_SESSION['customer_id']) ? intval($_SESSION['customer_id']) : null;
$cart_items = get_user_cart_ctr($ip, $customer_id);

// Calculate total
$total = 0.0;
if ($cart_items) {
    foreach ($cart_items as $it) {
        $total += floatval($it['product_price']) * intval($it['qty']);
    }
}

if (!$cart_items || count($cart_items) == 0) {
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page - KultureKart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'comorant garamond', serif;
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
            max-width: 900px;
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

        .page-header p {
            color: #e91e63;
            font-size: 1.1rem;
            font-style: oblique;
            font-weight: 500;
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

        /* Checkout Card */
        .checkout-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(233, 30, 99, 0.15);
            animation: fadeInUp 0.6s ease;
            border: 3px solid transparent;
            background-image:
                linear-gradient(white, white),
                linear-gradient(135deg, #ff6b35, #e91e63);
            background-origin: border-box;
            background-clip: padding-box, border-box;
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

        .section-title {
            color: #e91e63;
            font-size: 1.5rem;
            font-weight: 700;
            font-style: oblique;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid;
            border-image: linear-gradient(to right, #ff6b35, #e91e63) 1;
        }

        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 2rem;
        }

        .summary-table thead {
            background: linear-gradient(135deg, #fff0f5 0%, #ffe8f0 100%);
        }

        .summary-table th {
            padding: 1rem;
            text-align: left;
            color: #e91e63;
            font-weight: 700;
            font-style: oblique;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #ffe0ec;
        }

        .summary-table th:first-child {
            border-radius: 12px 0 0 0;
        }

        .summary-table th:last-child {
            border-radius: 0 12px 0 0;
        }

        .summary-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #ffe0ec;
        }

        .summary-table tbody tr:hover {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
        }

        .summary-table td {
            padding: 1rem;
            color: #333;
            font-style: oblique;
            font-weight: 500;
        }

        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: right;
        }

        .total-section strong {
            font-size: 2rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-style: oblique;
        }

        /* Payment Button */
        .payment-section {
            text-align: center;
            margin-top: 2rem;
        }

        #simulate-pay {
            padding: 1.2rem 3rem;
            background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: 700;
            font-style: oblique;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            position: relative;
            overflow: hidden;
        }

        #simulate-pay::before {
            content: '🛒';
            margin-right: 0.5rem;
        }

        #simulate-pay:hover {
            background: linear-gradient(135deg, #e91e63 0%, #ff6b35 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.5);
        }

        #simulate-pay:active {
            transform: translateY(-1px);
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
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

        /* Security Badge */
        .security-badge {
            text-align: center;
            margin-top: 2rem;
            padding: 1rem;
            background: linear-gradient(135deg, #fff8f0 0%, #fff0f5 100%);
            border-radius: 12px;
            border: 2px dashed #ffe0ec;
        }

        .security-badge p {
            color: #ff6b35;
            font-style: oblique;
            font-weight: 600;
            margin: 0;
        }

        .security-badge p::before {
            content: '🔒';
            margin-right: 0.5rem;
        }

        /* Back to Cart Link */
        .back-link {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: #ff6b35;
            text-decoration: none;
            font-weight: 600;
            font-style: oblique;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #e91e63;
            transform: translateX(-5px);
        }

        .back-link::before {
            content: '← ';
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

            .checkout-card {
                padding: 1.5rem;
            }

            .summary-table {
                font-size: 0.9rem;
            }

            .summary-table th,
            .summary-table td {
                padding: 0.75rem 0.5rem;
            }

            #simulate-pay {
                width: 100%;
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            .navbar {
                padding: 0.6rem 1rem;
            }

            .navbar-title {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .summary-table {
                font-size: 0.85rem;
            }

            .total-section strong {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
     <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="../index.php" class="navbar-brand">
            <img src="../assets/logo.png" alt="KultureKart" class="navbar-logo">
        </a>
        <div class="navbar-menu">
            <a href="../index.php">Home</a>
            <a href="all_product.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="../login/logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <!--Page Header-->
        <div class="page-header">
            <h1>Checkout</h1>
            <p>Review your order and proceed to payment</p>
        </div>

        <a href="cart.php" class="back-link">Back to Cart</a>

        <!-- Checkout Card -->
        <div class="checkout-card">
            <h2 class="section-title">Order Summary</h2>

            <?php if (empty($cart_items)): ?>
                <!--Empty Cart-->
                <div class="empty-cart">
                    <p>Your cart is empty. <a href="all_product.php">Shop now</a></p>
            </div>
            <?php else: ?>
                <!--Cart with Items-->
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $it):
                            $sub = floatval($it['product_price']) * intval($it['qty']);
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($it['product_title']); ?></td>
                                <td>GHS <?php echo number_format($it['product_price'], 2); ?></td>
                                <td><?php echo $it['qty']; ?></td>
                                <td>GHS <?php echo number_format($sub, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <div class="total-section">
                    <strong>Total: GHS <?php echo number_format($total, 2); ?></strong>
                </div>

            <!-- Payment Section -->
                <div class="payment-section">
                    <button id="simulate-pay">Complete Payment</button>
                </div>

            <!-- Security Badge -->
            <div style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); color: white; padding: 20px; border-radius: 12px; margin: 20px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <div style="font-size: 12px; margin-bottom: 10px; opacity: 0.8;">SECURED PAYMENT</div>
                <div style="font-size: 18px; letter-spacing: 2px; margin-bottom: 15px;">🔒 Powered by Paystack</div>
                <div style="font-size: 12px; opacity: 0.8;">Your payment information is 100% secure and encrypted</div>
            </div>

            <p style="text-align: center; color: #6b7280; font-size: 13px; margin-bottom: 20px;">
                    You will be redirected to Paystack's secure payment gateway
                </p>
            <?php endif; ?>
        </div>

        <script src="../js/checkout.js"></script>
</body>

</html>