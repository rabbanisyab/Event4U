
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Event 4U</title>

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
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    body {
        background: #f4f7fc;
    }

    .auth-wrapper {
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .auth-card {
        width: 100%;
        max-width: 900px;
        height: 550px;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(20, 40, 80, .10);
    }

    .auth-banner {
        height: 100%;
        background: linear-gradient(145deg, #102c57, #1769aa);
        color: white;
        padding: 30px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .auth-banner::before {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        top: -90px;
        right: -90px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
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
        font-size: 25px;
        font-weight: 700;
        line-height: 1.4;
    }

    .banner-content p {
        font-size: 12px;
        line-height: 1.7;
    }

    .banner-icon {
        font-size: 60px;
        margin: 15px 0;
        color: #b9e3ff;
    }

    .auth-form {
        height: 100%;
        padding: 25px 45px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-form h3 {
        font-size: 22px;
        font-weight: 700;
    }

    .auth-form p {
        font-size: 12px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .form-control {
        height: 40px;
        padding: 8px 12px;
        font-size: 12px;
        border-radius: 8px;
        border: 1px solid #dce3ed;
    }

    .form-control:focus {
        border-color: #2676c5;
        box-shadow: 0 0 0 3px rgba(38,118,197,.12);
    }

    .auth-form .mb-3 {
        margin-bottom: 10px !important;
    }

    .auth-form .mb-4 {
        margin-bottom: 12px !important;
    }

    .btn-auth {
        background: #1769aa;
        color: white;
        height: 42px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
    }

    .btn-auth:hover {
        background: #10558f;
        color: white;
    }

    @media (max-width: 767px) {
        html, body {
            height: auto;
            overflow: auto;
        }

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
            padding: 30px 25px;
        }
    }

    @media (max-height: 600px) and (min-width: 768px) {
        .auth-card {
            height: 96vh;
        }

        .auth-form {
            padding: 12px 35px;
        }

        .auth-form .mb-3 {
            margin-bottom: 6px !important;
        }

        .auth-form .mb-4 {
            margin-bottom: 8px !important;
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
                <h2 class="fw-bold">Join Event 4U Today!</h2>
                <p class="text-white-50 mt-3">
                    Buat akun dan temukan berbagai perlengkapan
                    untuk mewujudkan acara impianmu.
                </p>

                <div class="banner-icon">
                    <i class="bi bi-stars"></i>
                </div>

                <p class="small text-white-50">
                    Your event, our solution.
                </p>
            </div>
        </div>

        <div class="col-md-7 auth-form">
            <div class="mb-4">
                <h3 class="fw-bold">Buat Akun Baru</h3>
                <p class="text-secondary">
                    Daftar sekarang dan mulai gunakan Event 4U.
                </p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Masukkan email aktif" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password"
                           class="form-control"
                           placeholder="Minimal 8 karakter" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control"
                           placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-auth w-100">
                    Daftar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>

            <p class="text-center text-secondary mt-4 mb-0">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                    Masuk di sini
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
