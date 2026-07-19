<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Member Login - Foundation MS</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0E7490;
            --secondary: #16A34A;
            --accent: #F59E0B;
            --dark: #0F172A;
            --light: #F8FAFC;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            min-height: 550px;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0,0,0,0.3);
            display: flex;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--dark) 0%, #1E293B 100%);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            animation: pulse 10s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .login-left-content {
            position: relative;
            z-index: 1;
        }

        .login-left h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
        }

        .login-left p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .login-features {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .login-feature {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }

        .login-feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .login-feature-text h5 {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-feature-text p {
            font-size: 0.9rem;
            margin-bottom: 0;
            opacity: 0.7;
        }

        .login-right {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .login-logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
        }

        .login-logo-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--dark);
        }

        .login-form h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 2rem;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .login-form p {
            color: #64748b;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 10px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: var(--light);
            border: 1px solid #e2e8f0;
            border-right: none;
            padding: 15px 20px;
            border-radius: 15px 0 0 15px;
            color: var(--primary);
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-left: none;
            padding: 15px 20px;
            border-radius: 0 15px 15px 0;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 116, 144, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(22, 163, 74, 0.4);
            color: white;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fef2f2;
            color: #dc2626;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        @media (max-width: 991px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }

            .login-left {
                padding: 40px;
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .login-right {
                padding: 40px;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .login-left, .login-right {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side - Branding -->
        <div class="login-left">
            <div class="login-left-content">
                <h1>Member Portal</h1>
                <p>Access your personal dashboard to manage your profile, contributions, and more.</p>
                
                <div class="login-features">
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="login-feature-text">
                            <h5>My Profile</h5>
                            <p>Update your personal information</p>
                        </div>
                    </div>
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="login-feature-text">
                            <h5>Contributions</h5>
                            <p>View your payment history</p>
                        </div>
                    </div>
                    <div class="login-feature">
                        <div class="login-feature-icon">
                            <i class="bi bi-bell"></i>
                        </div>
                        <div class="login-feature-text">
                            <h5>Notifications</h5>
                            <p>Stay updated with announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-logo">
                <div class="login-logo-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="login-logo-text">Foundation MS</span>
            </div>

            <div class="login-form">
                <h2>Member Login</h2>
                <p>Enter your credentials to access your member portal</p>

                @if($errors->any())
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('member.login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" style="color: var(--primary);">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login to Portal
                    </button>
                </form>

                <div class="back-link">
                    <p><a href="{{ route('login') }}"><i class="bi bi-arrow-left me-2"></i>Back to Admin Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
