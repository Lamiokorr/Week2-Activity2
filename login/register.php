<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Taste of Africa</title>
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
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 70%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            animation: backgroundPulse 8s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes backgroundPulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        .register-container {
            margin-top: 30px;
            margin-bottom: 30px;
            position: relative;
            z-index: 10;
        }

        .card {
            border: none;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e, #ffb3ba);
            color: #fff;
            padding: 25px;
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
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .card-header h4 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-label {
            color: #4a4a4a;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .form-label i {
            color: #ff6b6b;
            margin-left: 8px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-3px); }
            60% { transform: translateY(-2px); }
        }

        .form-control {
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.2);
            background: #fff;
            transform: translateY(-1px);
        }

        .custom-radio .form-check-input {
            display: none;
        }

        .form-check-label {
            position: relative;
            padding: 12px 20px;
            border: 2px solid #ff6b6b;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            color: #ff6b6b;
            font-weight: 600;
            margin-right: 15px;
        }

        .form-check-input:checked + .form-check-label {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }

        .form-check-label:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(255, 107, 107, 0.2);
        }

        .card-footer {
            background: rgba(248, 249, 250, 0.8);
            padding: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: #666;
        }

        .animate-pulse-custom {
            animation: pulseGlow 2s ease-in-out infinite;
        }

        @keyframes pulseGlow {
            0% {
                box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            }
            50% {
                box-shadow: 0 6px 25px rgba(255, 107, 107, 0.5);
                transform: scale(1.02);
            }
            100% {
                box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            }
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            pointer-events: none;
            opacity: 0.6;
        }

        .floating-element.utensil-1 {
            top: 10%;
            left: 5%;
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.3);
            animation: float1 6s ease-in-out infinite;
        }

        .floating-element.utensil-2 {
            top: 70%;
            right: 10%;
            font-size: 1.5rem;
            color: rgba(255, 107, 107, 0.3);
            animation: float2 8s ease-in-out infinite;
        }

        .floating-element.utensil-3 {
            bottom: 20%;
            left: 8%;
            font-size: 1.8rem;
            color: rgba(118, 75, 162, 0.3);
            animation: float3 7s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @keyframes float2 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(-10deg); }
        }

        @keyframes float3 {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(15deg); }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body {
                padding: 30px 25px;
            }
            
            .form-check-label {
                margin-bottom: 10px;
                margin-right: 0;
            }
            
            .floating-element {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Floating decorative elements -->
    <div class="floating-element utensil-1">
        <i class="fas fa-utensils"></i>
    </div>
    <div class="floating-element utensil-2">
        <i class="fas fa-pizza-slice"></i>
    </div>
    <div class="floating-element utensil-3">
        <i class="fas fa-hamburger"></i>
    </div>

    <div class="container register-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header">
                        <h4><i class="fas fa-user-plus me-3"></i>Register</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" class="mt-4" id="register-form">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <i class="fa fa-user"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country <i class="fa fa-globe"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="country" name="country" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City <i class="fa fa-map-marker-alt"></i></label>
                                <input type="text" class="form-control animate__animated animate__fadeInUp" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Contact Number <i class="fa fa-phone"></i></label>
                                <input type="tel" class="form-control animate__animated animate__fadeInUp" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Register As</label>
                                <div class="d-flex justify-content-start flex-wrap">
                                    <div class="form-check me-3 custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="1" checked>
                                        <label class="form-check-label" for="customer">
                                            <i class="fas fa-user me-1"></i>Customer
                                        </label>
                                    </div>
                                    <div class="form-check custom-radio">
                                        <input class="form-check-input" type="radio" name="role" id="owner" value="2">
                                        <label class="form-check-label" for="owner">
                                            <i class="fas fa-store me-1"></i>Restaurant Owner
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">
                                <i class="fas fa-rocket me-2"></i>Create Account
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        Already have an account? <a href="login.php" class="highlight">Login here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
    
    <script>
        // Add some interactive sparkle effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 5px 25px rgba(255, 107, 107, 0.2)';
                });
                
                input.addEventListener('blur', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(255, 107, 107, 0.2)';
                });
            });
        });
    </script>
</body>
</html>