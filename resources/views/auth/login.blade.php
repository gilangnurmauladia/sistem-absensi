<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sunset Bridge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sb-primary: #C17D3C;
            --sb-bg: #D4C4B0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--sb-bg);
            min-height: 100vh;
            display: flex;
        }

        .login-container {
            width: 100%;
            display: flex;
        }

        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
        }

        .login-right {
            flex: 1;
            background-image: url("{{ asset('images/login-bg.png') }}"); /* Gambar ilustrasi kafe senja */
            background-size: 90%;
            background-position: right center;
            position: relative;
        }

        .login-right::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to right, rgba(212, 196, 176, 1) 0%, rgba(212, 196, 176, 0) 100%);
        }

        .brand-section {
            text-align: center;
            margin-bottom: 40px;
            margin-top: -60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .brand-logo-img {
            width: 180px;
            height: auto;
            margin-bottom: 10px;
            animation: logoFloat 3s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        }

        @keyframes logoFloat{
            0%{transform: translateY(0px);}
            50%{transform: translateY(-6px);}
            100%{transform: translateY(0px);}
}

        .brand-title {
            font-size: 28px;
            font-weight: 800;
            color: #1A1A1A;
            margin: 0;
            letter-spacing: -0.5px;
            animation: textFloat 4s ease-in-out infinite;
        }

        @keyframes textFloat{
    0%{
        transform: translateY(0px);
    }

    50%{
        transform: translateY(-6px);
    }

    100%{
        transform: translateY(0px);
    }
}

        .brand-subtitle {
            font-size: 16px;
            color: #4A4A4A;
            animation: textFloat 4s ease-in-out infinite;
        }

        @keyframes textFloat{
    0%{
        transform: translateY(0px);
    }

    50%{
        transform: translateY(-6px);
    }

    100%{
        transform: translateY(0px);
    }
}
        .login-card {
            background: #F8F9FB;
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            position: relative;
            z-index: 10;
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating{
            0%{transform: translateY(0px);}
            50%{transform: translateY(-6px);}
            100%{transform: translateY(0px);}
        }

        .welcome-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            color: #1A1A1A;
            margin-bottom: 4px;
        }

        .welcome-subtitle {
            font-size: 14px;
            font-weight: 700;
            font-style: italic;
            color: #1A1A1A;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 8px;
        }

        .form-control {
            background: transparent;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 14px;
            color: #1A1A1A;
        }

        .form-control:focus {
            background: white;
            border-color: var(--sb-primary);
            box-shadow: 0 0 0 4px rgba(193, 125, 60, 0.1);
        }

        .btn-login {
            background: #ED8B2B;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 15px;
            font-weight: 700;
            width: 100%;
            margin-top: 24px;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: #D47A22;
            transform: translateY(-1px);
        }

        .forgot-link {
            font-size: 12px;
            font-weight: 700;
            color: #1A1A1A;
            text-decoration: none;
        }

        .forgot-link:hover {
            color: var(--sb-primary);
        }

        .login-footer {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #4A4A4A;
            font-weight: 600;
            padding-top: 20px;
            border-top: 1px solid #D1D5DB;
        }

        @media (max-width: 991px) {
            .login-right { display: none; }
            .login-left { padding: 20px; }
            .login-card { padding: 32px; }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <div class="brand-section">
            <svg class="brand-logo-img" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Simple placeholder logo SVG for Sunset Bridge -->
                <img src="{{ asset('logosunsetbaru.png') }}"
                class="brand-logo-img"
     a          lt="Logo">        
            </svg>
            <h1 class="brand-title">Sunset Bridge</h1>
            <div class="brand-subtitle">Coffee & Eatry</div>
        </div>

        <div class="login-card">
            <h2 class="welcome-title">Hello there,</h2>
            <div class="welcome-subtitle">welcome to end-to-end HR platform</div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" style="font-size:13px; border-radius:8px;">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" style="font-size:13px; border-radius:8px;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email address">
                    @error('email')
                        <div class="invalid-feedback" style="font-size:12px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" id="togglePassword" class="btn position-absolute end-0 top-50 translate-middle-y border-0" style="background:transparent; color:#6B6B6B;">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback" style="font-size:12px; display:block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check" style="display:none;">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="login-footer">
                <span>Privacy Policy</span>
                <span>Terms of Us</span>
            </div>
        </div>
    </div>
    
    <div class="login-right">
        <!-- Background image covers this area -->
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>
</html>
