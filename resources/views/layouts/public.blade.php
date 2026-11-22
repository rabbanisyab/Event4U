
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Event 4U')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --dark: #0f172a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            font-size: 14px;
        }

        .navbar {
            background: white;
            padding: 12px 0;
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .navbar-brand:hover {
            color: #1d4ed8;
        }

        .nav-link {
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            transition: .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
        }

        .navbar .btn {
            font-size: 13px;
            font-weight: 500;
            padding: 8px 17px;
            border-radius: 8px;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,.09);
            padding: 8px;
        }

        .dropdown-item {
            font-size: 13px;
            padding: 9px 12px;
            border-radius: 6px;
        }

        .dropdown-item i {
            margin-right: 8px;
        }

        .footer {
            background: var(--dark);
            color: #cbd5e1;
            padding: 40px 0 20px;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                padding-top: 12px;
                align-items: stretch !important;
                gap: 5px !important;
            }

            .navbar-nav .btn {
                margin-top: 5px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="navbar navbar-expand-lg shadow-sm sticky-top">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            Event 4U<span class="text-dark">.</span>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMenu"
                aria-controls="navbarMenu"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                {{-- MENU UMUM --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}">
                        Katalog
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#tentang">
                        Tentang Kami
                    </a>
                </li>

                @auth

                    {{-- MENU CUSTOMER --}}
                    @if(auth()->user()->role === 'customer')

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.cart.index') }}">
                                <i class="bi bi-cart3 me-1"></i>
                                Keranjang
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.orders.index') }}">
                                Pesanan Saya
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.history.index') }}">
                                Riwayat
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center"
                               href="#"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <i class="bi bi-person-circle me-2 fs-5"></i>
                                {{ auth()->user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="{{ route('customer.profile.edit') }}">
                                        <i class="bi bi-person"></i>
                                        Profil Saya
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </li>

                            </ul>
                        </li>

                    {{-- MENU ADMIN --}}
                    @elseif(auth()->user()->role === 'admin')

                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Dashboard Admin
                            </a>
                        </li>

                    {{-- MENU VENDOR --}}
                    @elseif(auth()->user()->role === 'vendor')

                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('vendor.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Dashboard Vendor
                            </a>
                        </li>

                    @endif

                @else

                    {{-- MENU PENGUNJUNG --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Masuk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-primary" href="{{ route('register') }}">
                            Daftar
                        </a>
                    </li>

                @endauth

            </ul>
        </div>
    </div>
</nav>

{{-- KONTEN HALAMAN --}}
@yield('content')

{{-- BOOTSTRAP JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>