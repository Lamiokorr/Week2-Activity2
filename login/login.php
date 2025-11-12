<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KultureKart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        .background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(252, 176, 69, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(74, 144, 226, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        .background::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 40%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.5), transparent);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .container {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo-section {
            text-align: left;
            margin-bottom: 40px;
        }

        .logo-text {
            font-size: 12px;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 300;
            margin-bottom: 5px;
        }

        h1 {
            font-size: 42px;
            color: white;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            margin-bottom: 5px;
        }

        form {
            margin-top: 30px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        input {
            width: 100%;
            padding: 15px 20px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
        }

        input::placeholder {
            color: #999;
        }

        input:focus {
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.6);
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 13px;
        }

        .links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: color 0.3s;
        }

        .links a:hover {
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    
    <div class="container">
        <div class="login-box">
            <div class="logo-section">
                <div class="logo-text">KULTUREKART</div>
                <h1>WELCOME<br>BACK</h1>
                <p class="subtitle">Discover your next adventure</p>
            </div>
            
            <form method="POST" action="../actions/login_customer_action.php" class="mt-4" id="login-form">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address <i class="fa fa-envelope"></i></label>
                    <input type="email" placeholder="Email Address" required>
                </div>
                <div class="input-group">
                    <input type="password" placeholder="Password" required>
                </div>
                <button type="submit" class="button">Login</button>
                
                <div class="card-footer" style="margin-top: 15px; text-align: center; background: transparent; border: none; color: rgba(255, 255, 255, 0.9); font-size: 14px;">
                        New to KultureKart? <a href="register.php" class="highlight">Create an account</a>
                    </div>

            </form>
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