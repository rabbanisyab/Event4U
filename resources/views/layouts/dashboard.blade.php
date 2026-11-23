
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - Event 4U</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --sidebar: #0f172a;
            --background: #f5f7fb;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: #1e293b;
            font-size: 14px;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            background: var(--sidebar);
            color: white;
            z-index: 1000;
        }

        .brand {
            font-size: 23px;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }

        .sidebar-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            margin: 28px 0 12px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: #cbd5e1;
            text-decoration: none;
            transition: .2s;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: var(--primary);
            color: white;
        }

        .sidebar-link i {
            font-size: 17px;
        }

        .main-content {
            min-width: 0;
            flex: 1;
            margin-left: 260px;
        }

        .topbar {
            background: white;
            padding: 18px 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .content-area {
            padding: 30px;
        }

        .dashboard-card {
            background: white;
            border: none;
            border-radius: 14px;
            padding: 22px;
            box-shadow: 0 2px 10px rgba(0,0,0,.03);
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #eff6ff;
            color: var(--primary);
            font-size: 21px;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 20px 15px;
            }

            .topbar {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@php
    $role = auth()->user()->role;

    $menus = [];

    if ($role === 'admin') {
        $menus = [
            ['label' => 'Dashboard', 'icon' => 'bi-grid', 'route' => 'admin.dashboard'],
            ['label' => 'Pengguna', 'icon' => 'bi-people', 'route' => 'admin.users.index'],
            ['label' => 'Kategori', 'icon' => 'bi-tags', 'route' => 'admin.categories.index'],
            ['label' => 'Produk', 'icon' => 'bi-box-seam', 'route' => 'admin.products.index'],
            ['label' => 'Pesanan', 'icon' => 'bi-receipt', 'route' => 'admin.bookings.index'],
        ];
    } elseif ($role === 'vendor') {
        $menus = [
            ['label' => 'Dashboard', 'icon' => 'bi-grid', 'route' => 'vendor.dashboard'],
            ['label' => 'Produk Saya', 'icon' => 'bi-box-seam', 'route' => 'vendor.products.index'],
            ['label' => 'Pesanan Masuk', 'icon' => 'bi-receipt', 'route' => 'vendor.orders.index'],
        ];
        } else {
        $menus = [
            ['label' => 'Dashboard', 'icon' => 'bi-grid', 'route' => 'dashboard'],
            ['label' => 'Katalog Produk', 'icon' => 'bi-shop', 'route' => 'products.index'],
            ['label' => 'Keranjang', 'icon' => 'bi-cart3', 'route' => 'customer.cart.index'],
        ];
    }
@endphp

<div class="d-lg-flex">

    <aside class="sidebar flex-shrink-0">

        <div class="p-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('home') }}" class="brand">
                Event <span class="text-primary">4U.</span>
            </a>

            <button class="btn btn-outline-light d-lg-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu">
                <i class="bi bi-list"></i>
            </button>
        </div>

        <div class="collapse d-lg-block" id="sidebarMenu">
            <div class="px-3 pb-4">

                <div class="sidebar-label">MENU UTAMA</div>

                @foreach($menus as $menu)
                    <a href="{{ route($menu['route']) }}"
                       class="sidebar-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                        <i class="bi {{ $menu['icon'] }}"></i>
                        {{ $menu['label'] }}
                    </a>
                @endforeach

                <div class="sidebar-label">AKUN</div>

                <a href="{{ route('profile.edit') }}"
                   class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    Profil Saya
                </a>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                        <i class="bi bi-box-arrow-left"></i>
                        Keluar
                    </button>
                </form>

            </div>
        </div>
    </aside>

    <main class="main-content">

        <header class="topbar d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1">@yield('page-title', 'Dashboard')</h6>
                <small class="text-secondary">Event 4U Management System</small>
            </div>

            <div class="d-flex align-items-center gap-2">
                <div class="text-end">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <small class="text-secondary text-capitalize">{{ $role }}</small>
                </div>

                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px">
                    <i class="bi bi-person"></i>
                </div>
            </div>
        </header>

        <section class="content-area">
            @yield('content')
        </section>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
