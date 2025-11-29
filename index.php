<?php
if (!session_id()) {
	session_start();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KultureKart | Home</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body {
			background: #fff;
			font-family: 'garamond', serif;
			overflow-x: hidden;
		}

		section {
			padding: 100px 0;
		}

		h2.section-title {
			font-size: 3rem;
			font-weight: 700;
			text-align: center;
			margin-bottom: 40px;
			background: linear-gradient(135deg, #ff6b35, #e91e63);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}

		/* TOP NAVBAR */
		.navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 16px 32px;
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(12px);
			position: fixed;
			width: 100%;
			top: 0;
			z-index: 1000;
		}

		.navbar ul {
			display: flex;
			list-style-type: none;
			gap: 40px;
		}

		.navbar a {
			font-weight: 600;
			text-decoration: none;
			color: #e91e63;
		}

		.navbar a:hover {
			color: #ff6b35;
		}

		#logo {
			font-size: 40px;
			background: linear-gradient(135deg, #ff6b35, #e91e63);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			font-weight: 800;
		}

		/* HERO SECTION */
		.hero {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			background: linear-gradient(135deg, #ffffff 0%, #fff8f0 50%, #fff0f5 100%);
		}

		.hero h1 {
			font-size: 5rem;
			font-weight: 800;
			background: linear-gradient(135deg, #ff6b35, #e91e63);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
		}

		.hero p {
			color: #ff6b35;
			font-size: 1.2rem;
			margin-top: 10px;
		}

		.hero .btn-primary {
			background: linear-gradient(135deg, #ff6b35, #ff884d);
			border: none;
			font-weight: 600;
			padding: 12px 26px;
		}

		/* ABOUT SECTION */
		.about-box {
			background: #fff;
			padding: 40px;
			border-radius: 20px;
			border-left: 6px solid #ff6b35;
			box-shadow: 0 8px 20px rgba(255, 107, 53, 0.15);
		}

		/* FEATURES SECTION */
		.feature-card {
			padding: 30px;
			border-radius: 20px;
			background: #fff;
			box-shadow: 0 6px 20px rgba(233, 30, 99, 0.15);
			border-top: 4px solid #e91e63;
			transition: 0.3s;
		}

		.feature-card:hover {
			transform: translateY(-10px);
			box-shadow: 0 10px 30px rgba(255, 107, 53, 0.25);
		}

		/* ARTISAN CTA */
		.join-section {
			background: linear-gradient(135deg, #ff6b35, #e91e63);
			color: #fff;
			text-align: center;
			padding: 100px 0;
		}

		.join-section h2 {
			color: #fff !important;
		}

		/* FOOTER */
		footer {
			background: #111;
			color: #fff;
			padding: 40px 0;
			text-align: center;
		}

		.carousel-control-prev-icon,
		.carousel-control-next-icon {
			filter: invert(1);
			background-color: rgba(233, 30, 99, 0.5);
			border-radius: 50%;
			padding: 20px;
		}
	</style>
</head>

<body>
	<!-- NAVIGATION -->
	<header>
		<nav class="navbar">
			<h3>
				<a href="index.php">
					<img src="assets/logo.png"
						alt="KultureKart Logo"
						style="height:55px; width:auto; object-fit:contain;">
				</a>
			</h3>

			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="view/all_product.php">Products</a></li>
				<li><a href="view/cart.php">Cart</a></li>

				<?php if (isset($_SESSION['customer_id'])): ?>
					<li><a href="login/logout.php">Logout</a></li>
				<?php else: ?>
					<li><a href="login/login.php">Login</a></li>
					<li><a href="login/register.php">Register</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</header>

	<!-- HERO SECTION -->
	<section class="hero">
		<div>
			<h1>KultureKart</h1>
			<p>Connecting Africa’s Artisans to the World</p>
			<a href="view/all_product.php" class="btn btn-primary mt-3">Shop Now</a>
		</div>
	</section>

	<!-- ABOUT SECTION -->
	<section id="about">
		<div class="container">
			<h2 class="section-title">About KultureKart</h2>

			<div class="about-box">
				<p>
					KultureKart is an inclusive e-commerce platform designed to empower African artisans,
					fashion designers, and creative entrepreneurs by connecting them to global markets.
					The platform celebrates authentic African craftsmanship, cultural storytelling,
					and ethical fashion.
				</p>
			</div>
		</div>
	</section>

	<!-- FEATURED PRODUCTS CAROUSEL -->
<section id="featured-products" style="background:#fff8f0; padding:100px 0;">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>

        <?php
        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // DATABASE CONNECTION
        require_once("controllers/product_controller.php");
        
        // Debug: Check session
        echo "<!-- DEBUG: Session ID = " . ($_SESSION['customer_id'] ?? 'NOT SET') . " -->";
        
        // Try to get products
        try {
            $featured_products = view_all_products_ctr();
            
            // Debug: Show what we got
            echo "<!-- DEBUG: Product count = " . count($featured_products) . " -->";
            echo "<!-- DEBUG: Products = " . print_r($featured_products, true) . " -->";
            
        } catch (Exception $e) {
            echo "<!-- ERROR: " . $e->getMessage() . " -->";
            $featured_products = [];
        }
        ?>

        <!-- Temporary: Show raw data -->
        <div style="background: white; padding: 20px; margin: 20px 0; border: 2px solid red;">
            <h4>DEBUG INFO:</h4>
            <p><strong>Products Found:</strong> <?= count($featured_products) ?></p>
            <?php if (!empty($featured_products)): ?>
                <pre><?= print_r($featured_products[0] ?? 'No products', true) ?></pre>
            <?php else: ?>
                <p style="color: red;">No products returned from database!</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($featured_products)): ?>
            <div id="kkCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $active = "active";
                    foreach ($featured_products as $product):
                    ?>
                        <div class="carousel-item <?= $active ?>">
                            <?php $active = ""; ?>
                            <div class="row justify-content-center">
                                <div class="col-md-5 text-center">
                                    <!-- Debug: Show actual path -->
                                    <p>Image Path: product/<?= htmlspecialchars($product['product_image']) ?></p>
                                    
                                    <!-- Product Image -->
                                    <img src="product/<?= htmlspecialchars($product['product_image']) ?>"
                                        class="d-block w-75 mx-auto rounded-4 shadow"
                                        style="max-height:350px; object-fit:cover;"
                                        onerror="this.src='https://via.placeholder.com/350?text=Image+Not+Found'">

                                    <!-- Product Info -->
                                    <h4 class="mt-4" style="font-weight:700; color:#e91e63;">
                                        <?= htmlspecialchars($product['product_title']) ?>
                                    </h4>

                                    <p style="color:#ff6b35; font-size:1.2rem;">
                                        GHS <?= number_format($product['product_price'], 2) ?>
                                    </p>

                                    <a href="view/single_product.php?id=<?= $product['product_id'] ?>"
                                        class="btn btn-primary"
                                        style="background:linear-gradient(135deg,#ff6b35,#ff884d); border:none;">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#kkCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#kkCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No products available yet.</p>
        <?php endif; ?>
    </div>
</section>
										

					<!-- Controls -->
					<button class="carousel-control-prev" type="button" data-bs-target="#kkCarousel" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#kkCarousel" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
					</button>

				</div>

			<?php else: ?>
				<p class="text-center text-muted">No products available yet.</p>
			<?php endif; ?>
		</div>
	</section>


	<!-- VALUE PROPOSITION -->
	<section id="value">
		<div class="container">
			<h2 class="section-title">Our Value Proposition</h2>

			<div class="row g-4">
				<div class="col-md-4">
					<div class="feature-card">
						<h4>Authenticity Verified</h4>
						<p>Each product is culturally rooted, ethically made, and artisan-verified.</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="feature-card">
						<h4>Fair Trade</h4>
						<p>Artisans earn transparent, fair compensation for their creative work.</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="feature-card">
						<h4>Inclusive Payments</h4>
						<p>Supports Mobile Money, PayPal, and later debit/credit cards.</p>
					</div>
				</div>
			</div>
		</div>
	</section>



	<!-- PLATFORM FEATURES -->
	<section id="features">
		<div class="container">
			<h2 class="section-title">Our Platform Features</h2>

			<div class="row g-4">

				<div class="col-md-4">
					<div class="feature-card">
						<h4>AI-Driven Recommendations</h4>
						<p>Personalized shopping powered by smart product suggestions.</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="feature-card">
						<h4>Storytelling Profiles</h4>
						<p>Each artisan’s cultural journey and heritage is highlighted.</p>
					</div>
				</div>

				<div class="col-md-4">
					<div class="feature-card">
						<h4>Secure Payments</h4>
						<p>Fraud-proof, encrypted transactions for all users.</p>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- JOIN CTA -->
	<section class="join-section">
		<h2 class="section-title" style="color:white;">Become an Artisan on KultureKart</h2>
		<p class="mt-2 mb-4">Sell your craft, share your story, and reach global customers.</p>

		<a href="login/register.php" class="btn btn-light px-4 py-2">Join Now</a>
	</section>

	<!-- FOOTER -->
	<footer>
		<p>© 2025 KultureKart. All Rights Reserved.</p>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>