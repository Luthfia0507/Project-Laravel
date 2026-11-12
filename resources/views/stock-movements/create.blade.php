@extends('layouts.app')

@section('title', 'Catat Stok | LP Beauty')
@section('page-title', 'Catat Perubahan Stok')

@section('content')
<div class="page-heading">
    <h2 style="color:#8f526b;">Catat Perubahan Stok ♡</h2>
    <p style="color:#927985;"></p>
</div>

@if($errors->any())
    <div class="alert-error">
        <ul style="margin:0;padding-left:20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel" style="max-width:750px;">
    <form action="{{ route('stock-movements.store') }}" method="POST">
        @csrf

        <div style="margin-bottom:20px;">
            <label for="product_id" style="display:block;margin-bottom:8px;font-weight:600;">
                Pilih Produk
            </label>

            <select
                id="product_id"
                name="product_id"
                required
                style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;"
            >
                <option value="">-- Pilih produk --</option>

                @foreach($products as $product)
                    <option
                        value="{{ $product->id }}"
                        {{ (string)old('product_id') === (string)$product->id ? 'selected' : '' }}
                    >
                        {{ $product->name }} — Stok saat ini: {{ $product->stock }}
                    </option>
                @endforeach
            </select>

            @if($products->isEmpty())
                <p style="color:#b65779;">Belum ada produk. Tambahkan produk terlebih dahulu.</p>
            @endif
        </div>

        <div style="margin-bottom:20px;">
            <label for="type" style="display:block;margin-bottom:8px;font-weight:600;">
                Jenis Transaksi
            </label>

            <select
                id="type"
                name="type"
                required
                style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;"
            >
                <option value="">-- Pilih jenis transaksi --</option>
                <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>
                    Stok Masuk
                </option>
                <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>
                    Stok Keluar
                </option>
            </select>
        </div>

        <div style="margin-bottom:20px;">
            <label for="quantity" style="display:block;margin-bottom:8px;font-weight:600;">
                Jumlah Unit
            </label>

            <input
                type="number"
                id="quantity"
                name="quantity"
                value="{{ old('quantity') }}"
                min="1"
                step="1"
                required
                style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;"
            >
        </div>

        <div style="margin-bottom:24px;">
            <label for="description" style="display:block;margin-bottom:8px;font-weight:600;">
                Keterangan (Opsional)
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                maxlength="1000"
                placeholder="Contoh: Restok dari supplier atau barang keluar untuk pesanan."
                style="width:100%;box-sizing:border-box;padding:12px;border:1px solid #e8cbd7;border-radius:10px;background:#fffafc;"
            >{{ old('description') }}</textarea>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button type="submit" class="btn" {{ $products->isEmpty() ? 'disabled' : '' }}>
                Simpan Transaksi
            </button>

            <a href="{{ route('stock-movements.index') }}" class="btn-light">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection