<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIFASMA</title>

    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/gundarlogo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, #a18cd1 0%, #fbc2eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            border-radius: 20px;
            padding: 2.5rem 2rem 2rem 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13);
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(4px);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
        }

        .login-card img {
            filter: drop-shadow(0 2px 8px rgba(161, 140, 209, 0.15));
        }

        .auth-title {
            font-size: 2.1rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #6c3cc4;
            letter-spacing: 1px;
        }

        .auth-subtitle {
            color: #7b6f8e;
            font-size: 1rem;
        }

        .form-control {
            border-radius: 10px;
            border: 1.5px solid #e0d7f3;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #a18cd1;
            box-shadow: 0 0 0 0.15rem rgba(161, 140, 209, 0.18);
        }

        .btn-primary {
            border-radius: 10px;
            background: linear-gradient(90deg, #a18cd1 0%, #fbc2eb 100%);
            border: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 8px rgba(161, 140, 209, 0.09);
        }

        .btn-primary:hover {
            box-shadow: 0 4px 16px rgba(161, 140, 209, 0.18);
            transform: translateY(-2px) scale(1.03);
        }

        .btn-outline-secondary {
            border-radius: 10px;
        }

        .form-check label {
            margin-left: 0.3rem;
        }

        .input-group-text,
        .btn-outline-secondary {
            background: #f8f6fa;
            border: 1.5px solid #e0d7f3;
        }

        .alert {
            border-radius: 10px;
        }

        @media (max-width: 600px) {
            .login-card {
                padding: 1.2rem 0.5rem;
            }

            .auth-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-3">
            <img src="/assets/images/logo-SIFASMA.png" alt="Logo" style="width: 100%; height: auto;">
        </div>
        <h1 class="auth-title text-center">Login</h1>
        <p class="auth-subtitle text-center mb-4">Silakan login dengan email & password Anda.</p>

        {{-- ALERTS --}}
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        {{-- LOGIN FORM --}}
        <form action="{{ url('/login') }}" method="POST">
            @csrf
            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required
                    autofocus placeholder="Masukkan Email Anda">
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" required
                        placeholder="Masukkan Password Anda">
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                        <i class="bi bi-eye" id="iconEye"></i>
                    </button>
                </div>
            </div>
            {{-- Remember Me --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>

            {{-- Submit --}}
            <div class="d-grid">
                <button class="btn btn-primary">Login</button>
            </div>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">Belum punya akun/lupa password? Hubungi Admin.</small>
        </div>
    </div>
    {{-- Script langsung di bawah --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById("togglePassword");
            const passwordField = document.getElementById("password");
            const iconEye = document.getElementById("iconEye");

            toggleBtn.addEventListener("click", function() {
                const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                passwordField.setAttribute("type", type);
                iconEye.classList.toggle("bi-eye");
                iconEye.classList.toggle("bi-eye-slash");
            });
        });
    </script>
    <script src="/assets/js/feather-icons/feather.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>
