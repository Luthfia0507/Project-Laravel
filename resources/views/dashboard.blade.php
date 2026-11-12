@extends('layouts.app')

@section('title', 'Dashboard | Lp_Beauty')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .dashboard-welcome {
        background: linear-gradient(135deg, #fff0f5, #fffaf4);
        border: 1px solid #f4dce5;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .dashboard-welcome h2 {
        color: #a84f70;
        margin: 0 0 8px;
    }

    .dashboard-welcome p {
        margin: 0;
    }

    /* Enam kartu menu: 3 kolom x 2 baris */
    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }

    .stat-card {
        display: block;
        text-decoration: none;
        color: inherit;
        border-radius: 16px;
        padding: 22px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 22px rgba(168, 79, 112, .14);
    }

    .stat-card:focus-visible {
        outline: 3px solid #d982a0;
        outline-offset: 4px;
    }

    .stat-icon {
        font-size: 29px;
        margin-bottom: 12px;
    }

    .stat-title {
        font-size: 14px;
        color: #856b77;
        margin-bottom: 8px;
    }

    .stat-number {
        font-size: 30px;
        font-weight: 700;
        color: #a84f70;
        margin: 0 0 8px;
        overflow-wrap: anywhere;
    }

    .stat-hint {
        color: #a84f70;
        font-size: 12px;
        font-weight: 600;
        margin: 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .dashboard-section h3 {
        color: #a84f70;
        margin-top: 0;
    }

    .dashboard-section p {
        margin-bottom: 16px;
    }

    .stock-badge {
        display: inline-block;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-warning {
        background: #fff0d6;
        color: #8a5a12;
    }

    .badge-danger {
        background: #ffe5e8;
        color: #b4233c;
    }

    .dashboard-empty {
        text-align: center;
        padding: 20px;
        color: #987e88;
    }

    @media (max-width: 900px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .dashboard-stats,
        .dashboard-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-welcome {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')

{{-- Sambutan --}}
<div class="dashboard-welcome">
    <h2>Selamat Datang di Lp_Beauty ♡</h2>
    <p class="muted">
        Pantau inventaris makeup dan skincare kamu dalam satu tempat.
    </p>
</div>

{{-- Enam kartu menu --}}
<div class="dashboard-stats">

    {{-- 1. Produk --}}
    <a href="{{ url('/products') }}"
       class="panel stat-card"
       style="border-top: 4px solid #d982a0;"
       aria-label="Buka menu produk">

        <div class="stat-icon">💄</div>
        <p class="stat-title">Total Produk</p>
        <h2 class="stat-number">{{ $totalProducts }}</h2>
        <p class="stat-hint">Lihat produk →</p>
    </a>

    {{-- 2. Stok --}}
    <a href="{{ url('/stock-movements') }}"
       class="panel stat-card"
       style="border-top: 4px solid #e9b8a5;"
       aria-label="Buka menu manajemen stok">

        <div class="stat-icon">📦</div>
        <p class="stat-title">Total Stok</p>
        <h2 class="stat-number">
            {{ number_format($totalStock, 0, ',', '.') }}
        </h2>
        <p class="stat-hint">Lihat pergerakan stok →</p>
    </a>

    {{-- 3. Kategori --}}
    <a href="{{ url('/categories') }}"
       class="panel stat-card"
       style="border-top: 4px solid #c9a7d8;"
       aria-label="Buka menu kategori">

        <div class="stat-icon">🌷</div>
        <p class="stat-title">Total Kategori</p>
        <h2 class="stat-number">{{ $totalCategories }}</h2>
        <p class="stat-hint">Lihat kategori →</p>
    </a>

    {{-- 4. Supplier --}}
    <a href="{{ url('/suppliers') }}"
       class="panel stat-card"
       style="border-top: 4px solid #a7c9b0;"
       aria-label="Buka menu supplier">

        <div class="stat-icon">🏢</div>
        <p class="stat-title">Total Supplier</p>
        <h2 class="stat-number">{{ $totalSuppliers }}</h2>
        <p class="stat-hint">Lihat supplier →</p>
    </a>

    {{-- 5. Pembelian --}}
    <a href="{{ url('/purchases') }}"
       class="panel stat-card"
       style="border-top: 4px solid #e7c77c;"
       aria-label="Buka menu pembelian">

        <div class="stat-icon">🛒</div>
        <p class="stat-title">Total Pembelian</p>
        <h2 class="stat-number">{{ $totalPurchases }}</h2>
        <p class="stat-hint">Lihat transaksi pembelian →</p>
    </a>

    {{-- 6. Penjualan --}}
    <a href="{{ url('/sales') }}"
       class="panel stat-card"
       style="border-top: 4px solid #efa6bb;"
       aria-label="Buka menu penjualan">

        <div class="stat-icon">🧾</div>
        <p class="stat-title">Total Penjualan</p>
        <h2 class="stat-number">{{ $totalSales }}</h2>
        <p class="stat-hint">Lihat transaksi penjualan →</p>
    </a>

</div>

{{-- Ringkasan kondisi stok --}}
<div class="dashboard-grid">

    {{-- Stok menipis --}}
    <div class="panel dashboard-section">
        <h3>⚠️ Produk dengan Stok Menipis</h3>

        <p class="muted">
            Produk yang memiliki stok 1–3 unit.
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <span class="stock-badge badge-warning">
                                    {{ $product->stock }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="dashboard-empty">
                                Tidak ada produk dengan stok menipis ♡
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Stok habis --}}
    <div class="panel dashboard-section">
        <h3>❌ Produk dengan Stok Habis</h3>

        <p class="muted">
            Produk yang tidak memiliki stok tersedia.
        </p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($outOfStockProducts as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td>
                                <span class="stock-badge badge-danger">
                                    Habis
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="dashboard-empty">
                                Semua produk masih memiliki stok ♡
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection