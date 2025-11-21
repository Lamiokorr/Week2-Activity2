<?php
if (!session_id()) {
	session_start();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		body {
			background: linear-gradient(135deg, #ffffff 0%, #fff8f0 50%, #fff0f5 100%);
			min-height: 100vh;
			position: relative;
			overflow-x: hidden;
		}

		/* Subtle African pattern overlay */
		.pattern-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-image:
				repeating-linear-gradient(45deg, transparent, transparent 50px, rgba(255, 107, 53, 0.015) 50px, rgba(255, 107, 53, 0.015) 100px),
				repeating-linear-gradient(-45deg, transparent, transparent 50px, rgba(233, 30, 99, 0.015) 50px, rgba(233, 30, 99, 0.015) 100px);
			z-index: 0;
			pointer-events: none;
		}

		.menu-tray {
			position: fixed;
			top: 20px;
			right: 20px;
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(10px);
			border: 2px solid rgba(255, 107, 53, 0.3);
			border-radius: 16px;
			padding: 10px 16px;
			box-shadow: 0 8px 32px rgba(233, 30, 99, 0.2);
			z-index: 1000;
			transition: all 0.3s ease;
		}

		.menu-tray:hover {
			background: rgba(255, 255, 255, 0.95);
			box-shadow: 0 12px 40px rgba(255, 107, 53, 0.35);
			transform: translateY(-2px);
			border-color: rgba(233, 30, 99, 0.5);
		}

		.menu-tray span {
			color: #d81b60;
			font-weight: 600;
			font-style: oblique;
		}

		.menu-tray a {
			margin-left: 8px;
			border-radius: 10px;
			transition: all 0.3s ease;
			font-weight: 500;
		}

		.btn-outline-primary {
			border-color: #ff6b35;
			color: #ff6b35;
			font-weight: 600;
		}

		.btn-outline-primary:hover {
			background: linear-gradient(135deg, #ff6b35 0%, #ff8555 100%);
			border-color: #ff6b35;
			color: white;
			transform: translateY(-1px);
			box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
		}

		.btn-outline-secondary {
			border-color: #e91e63;
			color: #e91e63;
			font-weight: 600;
		}

		.btn-outline-secondary:hover {
			background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
			border-color: #e91e63;
			color: white;
			transform: translateY(-1px);
			box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
		}

		.container {
			position: relative;
			z-index: 1;
			padding-top: 120px;
		}

		.welcome-card {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(10px);
			border-radius: 20px;
			padding: 60px 50px;
			box-shadow:
				0 10px 40px rgba(233, 30, 99, 0.15),
				0 2px 8px rgba(255, 107, 53, 0.1);
			border: 2px solid transparent;
			background-image:
				linear-gradient(white, white),
				linear-gradient(135deg, #ff6b35, #e91e63);
			background-origin: border-box;
			background-clip: padding-box, border-box;
			animation: fadeInUp 0.8s ease;
			max-width: 700px;
			margin: 0 auto;
			position: relative;
		}

		.welcome-card>* {
			position: relative;
			z-index: 1;
		}

		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		h1 {
			background: linear-gradient(135deg, #ff6b35 0%, #e91e63 100%);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			font-size: 4rem;
			font-weight: 700;
			margin-bottom: 20px;
			letter-spacing: -2px;
			animation: fadeIn 1s ease 0.2s both;
			font-style: oblique;
			font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
			}

			to {
				opacity: 1;
			}
		}

		.subtitle {
			color: #d81b60;
			font-size: 1.3rem;
			font-weight: 500;
			margin-bottom: 15px;
			font-style: oblique;
			animation: fadeIn 1s ease 0.4s both;
		}

		.text-muted {
			color: #ff6b35 !important;
			font-size: 1.1rem;
			font-weight: 400;
			animation: fadeIn 1s ease 0.6s both;
			font-style: oblique;
		}

		@media (max-width: 768px) {
			h1 {
				font-size: 2.8rem;
			}

			.welcome-card {
				padding: 40px 30px;
			}

			.menu-tray {
				top: 10px;
				right: 10px;
				padding: 8px 12px;
			}

			.menu-tray span {
				display: none;
			}

			.subtitle {
				font-size: 1.1rem;
			}
		}
	</style>
</head>

<body>

	<div class="menu-tray">
		<!--<span class="me-2">Menu:</span> -->

		<?php if (!isset($_SESSION['customer_id'])): ?>
			<!-- Not logged in -->
			<a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
			<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>

		<?php elseif (isset($_SESSION['role']) && $_SESSION['role'] == '1'): ?>
			<!-- Logged in as admin -->
			<a href="login/logout.php" class="btn btn-sm btn-outline-primary">Logout</a>
			<a href="admin/category.php" class="btn btn-sm btn-outline-secondary">Category</a>
			<a href="admin/brand.php" class="btn btn-sm btn-outline-secondary">Brand</a>

		<?php else: ?>
			<!-- Logged in as normal user -->
			<a href="login/logout.php" class="btn btn-sm btn-outline-primary">Logout</a>
			<a href="view/cart.php" class="btn btn-sm btn-outline-secondary">Cart</a>
			<a href="view/product.php" class="btn btn-sm btn-outline-secondary">Products</a>
		<?php endif; ?>
	</div>

	<div class="container">
		<div class="text-center">
			<div class="welcome-card">
				<h1>KultureKart</h1>
				<p class="text-muted">Connect With Authentic African Artisan Crafts</p>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>