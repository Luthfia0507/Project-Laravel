<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lp_Beauty')</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff7f8;
            color: #49363d;
        }

        .app-container { display: flex; min-height: 100vh; }

        .sidebar {
            width: 245px;
            min-width: 245px;
            background: #fff;
            border-right: 1px solid #f0dce2;
            padding: 25px 16px;
        }

        .brand {
            text-align: center;
            padding: 10px 5px 28px;
            border-bottom: 1px solid #f3e2e7;
        }

        .brand h1 {
            color: #c96e8c;
            font-size: 27px;
            margin: 0 0 8px;
        }

        .brand p { color: #a88b95; font-size: 12px; margin: 0; }

        .menu-title {
            margin: 25px 12px 10px;
            font-size: 11px;
            font-weight: bold;
            color: #b49aa3;
            letter-spacing: 1.5px;
        }

        .menu { display: flex; flex-direction: column; gap: 8px; }

        .menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            text-decoration: none;
            color: #765b65;
            border-radius: 10px;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover { background: #fff1f5; color: #b65779; }

        .menu a.active {
            background: #f7e1e8;
            color: #a44c6b;
            font-weight: bold;
        }

        .sidebar-note {
            margin-top: 35px;
            padding: 15px;
            border-radius: 12px;
            background: #fff1f5;
            color: #8c6574;
            font-size: 12px;
            line-height: 1.7;
        }

        .main-area { flex: 1; min-width: 0; }

        .topbar {
            min-height: 75px;
            background: #fff;
            border-bottom: 1px solid #f0dce2;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .topbar h2 { margin: 0; font-size: 20px; color: #704451; }

        .account-area {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .account-info {
            color: #8b7079;
            font-size: 12px;
            line-height: 1.6;
        }

        .role-label {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 20px;
            background: #f7e1e8;
            color: #a44c6b;
            font-weight: bold;
            text-transform: capitalize;
        }

        .logout-button {
            padding: 9px 12px;
            background: #fff1f5;
            color: #a44c6b;
            border: 1px solid #f0dce2;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
        }

        .logout-button:hover { background: #f7e1e8; }

        .content { padding: 30px; }

        .page-heading { margin-bottom: 25px; }
        .page-heading h2 { margin: 0 0 8px; }
        .page-heading p { margin: 0; color: #8b7079; line-height: 1.6; }

        .btn {
            display: inline-block;
            padding: 12px 17px;
            background: #c96e8c;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover { background: #ad5272; }

        .btn-light {
            display: inline-block;
            padding: 11px 15px;
            background: #f7e1e8;
            color: #8c3f5b;
            text-decoration: none;
            border-radius: 8px;
        }

        .panel {
            background: white;
            padding: 22px;
            border-radius: 14px;
            margin-bottom: 22px;
            box-shadow: 0 5px 20px rgba(180, 110, 135, 0.07);
        }

        .panel h3 { margin-top: 0; color: #704451; }
        .muted { color: #8b7079; }

        .alert-success {
            background: #e8f7ed;
            color: #287a45;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fff0f0;
            color: #b4233c;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f7e1e8; color: #704451; }

        th, td {
            padding: 13px 10px;
            text-align: left;
            border-bottom: 1px solid #f0dce2;
        }

        .table-wrap { overflow-x: auto; }

        footer {
            text-align: center;
            color: #a88b95;
            font-size: 12px;
            padding: 20px;
        }

        @media (max-width: 800px) {
            .sidebar { width: 190px; min-width: 190px; padding: 20px 10px; }
            .content { padding: 20px 15px; }
            .topbar { padding: 18px 15px; }
        }

        @media (max-width: 560px) {
            .app-container { flex-direction: column; }
            .sidebar {
                width: 100%;
                min-width: 0;
                padding: 15px;
                border-right: none;
                border-bottom: 1px solid #f0dce2;
            }
            .brand { padding: 5px 0 15px; }
            .menu-title { margin-top: 15px; }
            .menu { flex-direction: row; flex-wrap: wrap; }
            .menu a { padding: 10px; font-size: 12px; }
            .sidebar-note { display: none; }
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="app-container">
    <aside class="sidebar">
        <div class="brand">
            <h1>Lp_Beauty ♡</h1>
            <p>Your Beauty is Yourself</p>
        </div>

        <div class="menu-title">MENU UTAMA</div>

        <nav class="menu">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                   class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('suppliers.index') }}"
                    class="{{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    🏢 Supplier
                </a>
            @endif

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('purchases.index') }}"
                class="{{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    🛍️ Pembelian
                </a>
            @endif

            <a href="{{ route('products.index') }}"
               class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                💄 Semua Produk
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('categories.index') }}"
                   class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    🗂️ Kategori
                </a>
            @endif

            <a href="{{ route('stock-movements.index') }}"
               class="{{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                📦 Manajemen Stok
            </a>

            <a href="{{ route('sales.index') }}"
               class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                🛍️ Penjualan
            </a>
        </nav>

        <div class="sidebar-note">
            <strong>Beauty begins here ♡</strong>
            <br>
            @if(auth()->user()->role === 'admin')
                Kamu masuk sebagai Admin. Kelola produk, kategori, dan persediaan toko di sini.
            @else
                Kamu masuk sebagai Staff. Lihat produk dan bantu mengelola persediaan toko.
            @endif
        </div>
    </aside>

    <div class="main-area">
        <header class="topbar">
            <h2>@yield('page-title', 'Lp_Beauty')</h2>

            <div class="account-area">
                <div class="account-info">
                    <strong>{{ auth()->user()->name }}</strong><br>
                    <span class="role-label">{{ auth()->user()->role }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST"
                      onsubmit="return confirm('Yakin ingin logout dari Lp_Beauty?')">
                    @csrf
                    <button type="submit" class="logout-button">
                        ↪ Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="content">
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </main>

        <footer>
            © {{ date('Y') }} Lp_Beauty. Made with love ♡
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>
