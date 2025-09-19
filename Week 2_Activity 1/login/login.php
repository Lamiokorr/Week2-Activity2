<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            border: none;
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            padding: 12px 20px;
            font-size: 1.1rem;
        }

        .btn-custom:hover {
            background: linear-gradient(135deg, #ff5252, #ff7979);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
            color: #fff;
        }

        .highlight {
            color: #ff6b6b;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .highlight:hover {
            color: #ff5252;
            text-decoration: none;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 70%, rgba(255, 107, 107, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 20%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            animation: backgroundPulse 10s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes backgroundPulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        .login-container {
            margin-top: 80px;
            margin-bottom: 50px;
            position: relative;
            z-index: 10;
        }

        .card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            transition: all 0.4s ease;
            max-width: 450px;
            margin: 0 auto;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e, #ffb3ba);
            color: #fff;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            animation: shimmer 4s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .card-header h4 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            text-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 45px 40px;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-label {
            color: #4a4a4a;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .form-label i {
            color: #ff6b6b;
            margin-left: 10px;
            animation: bounce 2s infinite;
            font-size: 1rem;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-4px); }
            60% { transform: translateY(-2px); }
        }

        .form-control {
            border: 2px solid #e1e8ed;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            margin-bottom: 25px;
        }

        .form-control:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.25rem rgba(255, 107, 107, 0.25);
            background: #fff;
            transform: translateY(-2px);
        }

        .card-footer {
            background: rgba(248, 249, 250, 0.8);
            padding: 25px 30px;
            text-align: center;
            font-size: 1.1rem;
            color: #666;
            font-weight: 500;
        }

        .animate-pulse-custom {
            animation: pulseGlow 2.5s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 8px 30px rgba(255, 107, 107, 0.5);
                transform: scale(1.03);
            }
            100% {
                box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
                transform: scale(1);
            }
        }

        /* Alert styling */
        .alert-info {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            color: white;
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
            animation: slideInDown 0.8s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            pointer-events: none;
            opacity: 0.5;
        }

        .floating-element.chef-1 {
            top: 15%;
            left: 8%;
            font-size: 2.5rem;
            color: rgba(255, 255, 255, 0.3);
            animation: float1 8s ease-in-out infinite;
        }

        .floating-element.chef-2 {
            top: 65%;
            right: 12%;
            font-size: 2rem;
            color: rgba(255, 107, 107, 0.3);
            animation: float2 10s ease-in-out infinite;
        }

        .floating-element.chef-3 {
            bottom: 15%;
            left: 10%;
            font-size: 2.2rem;
            color: rgba(118, 75, 162, 0.3);
            animation: float3 9s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(10deg); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(-10deg); }
        }

        @keyframes float3 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(15deg); }
        }

        /* Welcome back animation */
        .welcome-icon {
            position: absolute;
            top: -15px;
            right: 20px;
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(255, 107, 107, 0.4);
            animation: welcomePulse 3s ease-in-out infinite;
        }

        @keyframes welcomePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .card-body {
                padding: 35px 30px;
            }
            
            .login-container {
                margin-top: 50px;
            }
            
            .floating-element {
                display: none;
            }
            
            .card {
                margin: 0 20px;
            }
        }

        @media (max-width: 480px) {
            .card-header h4 {
                font-size: 1.8rem;
            }
            
            .form-control {
                padding: 12px 15px;
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Floating decorative elements -->
    <div class="floating-element chef-1">
        <i class="fas fa-chef-hat"></i>
    </div>
    <div class="floating-element chef-2">
        <i class="fas fa-cookie-bite"></i>
    </div>
    <div class="floating-element chef-3">
        <i class="fas fa-wine-glass-alt"></i>
    </div>

    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-8 col-lg-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header">
                        <div class="welcome-icon">
                        </div>
                        <h4></i>Welcome Back</h4>
                    </div>
                    <div class="card-body">
                      

                        <form method="POST" action="" class="mt-4" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">
                                <i class="fas fa-rocket me-2"></i>Sign In to Your Account
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        New to Taste of Africa? <a href="register.php" class="highlight">Create an account</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>

    <script>
        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 8px 25px rgba(255, 107, 107, 0.25)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0 0 0.25rem rgba(255, 107, 107, 0.25)';
                });

                // Add typing effect
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = '#4CAF50';
                        this.style.boxShadow = '0 0 0 0.25rem rgba(76, 175, 80, 0.25)';
                    } else {
                        this.style.borderColor = '#ff6b6b';
                        this.style.boxShadow = '0 0 0 0.25rem rgba(255, 107, 107, 0.25)';
                    }
                });
            });

            // Add welcome message animation
            const card = document.querySelector('.card');
            setTimeout(() => {
                card.style.transform = 'translateY(-5px) scale(1.01)';
                setTimeout(() => {
                    card.style.transform = 'translateY(0) scale(1)';
                }, 300);
            }, 1000);
        });
    </script>
</body>

</html>