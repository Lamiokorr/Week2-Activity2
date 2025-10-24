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
		}

		body {
			background: linear-gradient(135deg, #e3f2fd 0%, #b3e5fc 50%, #81d4fa 100%);
			min-height: 100vh;
			position: relative;
			overflow-x: hidden;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		/* Animated wave background */
		body::before {
			content: '';
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 300px;
			background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%2364b5f6" fill-opacity="0.3" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
			animation: wave 15s linear infinite;
			z-index: 0;
		}

		body::after {
			content: '';
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 300px;
			background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%234fc3f7" fill-opacity="0.2" d="M0,224L48,208C96,192,192,160,288,160C384,160,480,192,576,197.3C672,203,768,181,864,181.3C960,181,1056,203,1152,208C1248,213,1344,203,1392,197.3L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
			animation: wave 20s linear infinite reverse;
			z-index: 0;
		}

		@keyframes wave {
			0% { background-position-x: 0; }
			100% { background-position-x: 1440px; }
		}

		.menu-tray {
			position: fixed;
			top: 20px;
			right: 20px;
			background: rgba(255, 255, 255, 0.85);
			backdrop-filter: blur(10px);
			border: 1px solid rgba(100, 181, 246, 0.3);
			border-radius: 16px;
			padding: 10px 16px;
			box-shadow: 0 8px 32px rgba(33, 150, 243, 0.15);
			z-index: 1000;
			transition: all 0.3s ease;
		}

		.menu-tray:hover {
			background: rgba(255, 255, 255, 0.95);
			box-shadow: 0 12px 40px rgba(33, 150, 243, 0.25);
			transform: translateY(-2px);
		}

		.menu-tray span {
			color: #0277bd;
			font-weight: 500;
		}

		.menu-tray a { 
			margin-left: 8px;
			border-radius: 10px;
			transition: all 0.3s ease;
		}

		.btn-outline-primary {
			border-color: #0288d1;
			color: #0288d1;
		}

		.btn-outline-primary:hover {
			background: #0288d1;
			border-color: #0288d1;
			color: white;
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(2, 136, 209, 0.3);
		}

		.btn-outline-secondary {
			border-color: #4fc3f7;
			color: #0277bd;
		}

		.btn-outline-secondary:hover {
			background: #4fc3f7;
			border-color: #4fc3f7;
			color: white;
			transform: translateY(-1px);
			box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
		}

		.container {
			position: relative;
			z-index: 1;
			padding-top: 120px;
		}

		.welcome-card {
			background: rgba(255, 255, 255, 0.75);
			backdrop-filter: blur(15px);
			border-radius: 24px;
			padding: 60px 40px;
			box-shadow: 0 20px 60px rgba(33, 150, 243, 0.2);
			border: 1px solid rgba(255, 255, 255, 0.5);
			animation: fadeInUp 0.8s ease;
			max-width: 600px;
			margin: 0 auto;
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
			color: #01579b;
			font-size: 3.5rem;
			font-weight: 300;
			margin-bottom: 20px;
			letter-spacing: -1px;
			animation: fadeIn 1s ease 0.2s both;
		}

		@keyframes fadeIn {
			from { opacity: 0; }
			to { opacity: 1; }
		}

		.text-muted {
			color: #0277bd !important;
			font-size: 1.1rem;
			font-weight: 400;
			animation: fadeIn 1s ease 0.4s both;
		}

		/* Floating bubbles */
		.bubble {
			position: fixed;
			bottom: -100px;
			background: rgba(129, 212, 250, 0.4);
			border-radius: 50%;
			animation: float-up linear infinite;
			z-index: 0;
		}

		@keyframes float-up {
			to {
				transform: translateY(-110vh) translateX(20px);
				opacity: 0;
			}
		}

		.bubble:nth-child(1) {
			width: 40px;
			height: 40px;
			left: 10%;
			animation-duration: 12s;
			animation-delay: 0s;
		}

		.bubble:nth-child(2) {
			width: 60px;
			height: 60px;
			left: 25%;
			animation-duration: 15s;
			animation-delay: 2s;
		}

		.bubble:nth-child(3) {
			width: 30px;
			height: 30px;
			left: 45%;
			animation-duration: 10s;
			animation-delay: 4s;
		}

		.bubble:nth-child(4) {
			width: 50px;
			height: 50px;
			left: 65%;
			animation-duration: 13s;
			animation-delay: 1s;
		}

		.bubble:nth-child(5) {
			width: 35px;
			height: 35px;
			left: 85%;
			animation-duration: 11s;
			animation-delay: 3s;
		}

		@media (max-width: 768px) {
			h1 {
				font-size: 2.5rem;
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
		}
	</style>
</head>
<body>
	<!-- Floating bubbles -->
	<div class="bubble"></div>
	<div class="bubble"></div>
	<div class="bubble"></div>
	<div class="bubble"></div>
	<div class="bubble"></div>

	<div class="menu-tray">
		<span class="me-2">Menu:</span>
		<a href="login/register.php" class="btn btn-sm btn-outline-primary">Register</a>
		<a href="login/login.php" class="btn btn-sm btn-outline-secondary">Login</a>
	</div>

	<div class="container">
		<div class="text-center">
			<div class="welcome-card">
				<h1>Welcome to KultureKart</h1>
				<p class="text-muted">Use the menu in the top-right to Register or Login.</p>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>