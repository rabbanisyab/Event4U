
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Event 4U</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: #f4f7fc;
        }

        .auth-wrapper {
            width: 100%;
            height: 100vh;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 850px;
            height: 540px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(20, 40, 80, .10);
        }

        .auth-banner {
            height: 100%;
            background: linear-gradient(145deg, #102c57, #1769aa);
            color: white;
            padding: 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .auth-banner::before,
        .auth-banner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.07);
        }

        .auth-banner::before {
            width: 220px;
            height: 220px;
            top: -90px;
            right: -90px;
        }

        .auth-banner::after {
            width: 170px;
            height: 170px;
            bottom: -70px;
            left: -70px;
        }

        .brand {
            font-size: 21px;
            font-weight: 800;
            position: relative;
            z-index: 1;
        }

        .banner-content {
            position: relative;
            z-index: 1;
        }

        .banner-content h2 {
            font-size: 27px;
            font-weight: 700;
            line-height: 1.4;
        }

        .banner-content p {
            font-size: 13px;
            line-height: 1.8;
        }

        .banner-icon {
            font-size: 65px;
            margin: 20px 0;
            color: #b9e3ff;
        }

        .auth-form {
            height: 100%;
            padding: 30px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-form h3 {
            font-size: 24px;
            font-weight: 700;
        }

        .auth-form p {
            font-size: 13px;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .form-control {
            padding: 10px 13px;
            font-size: 13px;
            border-radius: 9px;
            border: 1px solid #dce3ed;
        }

        .form-control:focus {
            border-color: #2676c5;
            box-shadow: 0 0 0 3px rgba(38,118,197,.12);
        }

        .btn-auth {
            background: #1769aa;
            color: white;
            padding: 11px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            border: none;
        }

        .btn-auth:hover {
            background: #10558f;
            color: white;
        }

        .auth-form .mb-3 {
            margin-bottom: 14px !important;
        }

        @media (max-width: 767px) {
            .auth-wrapper {
                height: auto;
                min-height: 100vh;
                padding: 15px;
            }

            .auth-card {
                height: auto;
                max-width: 450px;
            }

            .auth-banner {
                display: none;
            }

            .auth-form {
                padding: 35px 25px;
            }
        }

        @media (max-height: 650px) and (min-width: 768px) {
            .auth-wrapper {
                min-height: 0;
                padding: 10px;
            }

            .auth-card {
                height: 95vh;
            }

            .auth-form {
                padding: 18px 35px;
            }

            .banner-icon {
                margin: 10px 0;
            }
        }
    </style>

</head>

<body>
<div class="auth-wrapper">
    <div class="auth-card row g-0">

        <div class="col-md-5 auth-banner">
            <div class="brand mb-5">
                <i class="bi bi-calendar-event me-2"></i> Event 4U
            </div>

            <div class="banner-content">
                <h2 class="fw-bold">Make Your Event Extraordinary!</h2>
                <p class="text-white-50 mt-3">
                    Temukan dan sewa berbagai perlengkapan event
                    dengan mudah dalam satu platform.
                </p>

                <div class="banner-icon">
                    <i class="bi bi-camera-reels"></i>
                    <i class="bi bi-speaker ms-3"></i>
                </div>

                <p class="small text-white-50">
                    Satu platform untuk semua kebutuhan acaramu.
                </p>
            </div>
        </div>

        <div class="col-md-7 auth-form">
            <div class="mb-4">
                <h3 class="fw-bold">Selamat Datang!</h3>
                <p class="text-secondary">
                    Masuk ke akun Event 4U untuk melanjutkan.
                </p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email kamu" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="Masukkan password" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">
                            Ingat saya
                        </label>
                    </div>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="small text-decoration-none">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn btn-auth w-100">
                    Masuk <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <p class="text-center text-secondary mt-4 mb-0">
                Belum punya akun?
                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                    Daftar sekarang
                </a>
            </p>

            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="small text-secondary text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>
</div>
</body>
</html>
