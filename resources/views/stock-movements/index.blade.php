@extends('layouts.app')

@section('title', 'Manajemen Stok | Lp_Beauty')
@section('page-title', 'Manajemen Stok')

@section('content')

<div class="page-heading">
    <h2 style="color:#8f526b;">Manajemen Stok ♡</h2>
    <p style="color:#927985;"></p>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert-error">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- FORM PENCATATAN STOK --}}
<div class="panel" style="margin-bottom:25px;">
    <h3 style="color:#8f526b;margin-top:0;">🌷 Catat Perubahan Stok</h3>

    <form action="{{ route('stock-movements.store') }}" method="POST">
        @csrf

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:18px;">

            <div>
                <label for="product_id" style="display:block;margin-bottom:8px;font-weight:600;">
                    Pilih Produk
                </label>

                <select id="product_id" name="product_id" required
                    style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;">
                    <option value="">-- Pilih produk --</option>

                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ (string)old('product_id') === (string)$product->id ? 'selected' : '' }}>
                            {{ $product->name }} — Stok: {{ $product->stock }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" style="display:block;margin-bottom:8px;font-weight:600;">
                    Jenis Transaksi
                </label>

                <select id="type" name="type" required
                    style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;">
                    <option value="">-- Pilih jenis --</option>
                    <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>Stok Masuk</option>
                    <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Stok Keluar</option>
                </select>
            </div>

            <div>
                <label for="quantity" style="display:block;margin-bottom:8px;font-weight:600;">
                    Jumlah Unit
                </label>

                <input type="number" id="quantity" name="quantity"
                    value="{{ old('quantity') }}" min="1" step="1" required
                    style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;">
            </div>
        </div>

        <div style="margin-top:18px;">
            <label for="description" style="display:block;margin-bottom:8px;font-weight:600;">
                Keterangan (Opsional)
            </label>

            <textarea id="description" name="description" rows="3" maxlength="1000"
                placeholder="Contoh: Restok dari supplier atau barang keluar untuk pesanan."
                style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn" {{ $products->isEmpty() ? 'disabled' : '' }}>
                Simpan Perubahan Stok
            </button>
        </div>

        @if($products->isEmpty())
            <p class="muted">Belum ada produk. Tambahkan produk terlebih dahulu.</p>
        @endif
    </form>
</div>

{{-- TABEL RIWAYAT --}}
<div class="panel">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;margin-bottom:20px;">
        <h3 style="color:#8f526b;margin:0;">📋 Riwayat Pergerakan Stok</h3>
        <span style="background:#fbe7ef;color:#8f526b;padding:8px 14px;border-radius:20px;font-size:13px;font-weight:600;">
            {{ $movements->count() }} Transaksi
        </span>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Stok Sebelum</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="white-space:nowrap;">
                            {{ $movement->created_at?->format('d/m/Y H:i') ?? '-' }}
                        </td>
                        <td>{{ $movement->product->name ?? 'Produk tidak tersedia' }}</td>
                        <td>
                            @if($movement->type === 'in')
                                <span style="background:#e4f5e8;color:#287545;padding:5px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                    Stok Masuk
                                </span>
                            @else
                                <span style="background:#fce8ef;color:#a44c6b;padding:5px 10px;border-radius:20px;font-size:12px;font-weight:600;">
                                    Stok Keluar
                                </span>
                            @endif
                        </td>
                        <td>{{ $movement->quantity }} unit</td>
                        <td>{{ $movement->stock_before }} unit</td>
                        <td>{{ $movement->stock_after }} unit</td>
                        <td>{{ $movement->description ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:30px;">
                            <p style="color:#8f526b;font-weight:600;">Belum ada riwayat stok ♡</p>
                            <p class="muted">Transaksi yang kamu simpan akan muncul di sini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection