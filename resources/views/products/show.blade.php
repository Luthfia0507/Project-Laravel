@extends('layouts.app')

@section('content')
<div class="panel">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
        <div>
            <h2 style="margin-bottom: 6px;">{{ $product->name }}</h2>
            <p class="muted" style="margin: 0;">
                {{ $product->brand ?: 'Tanpa merek' }}
            </p>
        </div>

        <a href="{{ route('products.edit', $product->id) }}" class="btn">
            Edit Produk
        </a>
    </div>

    <hr style="border: 0; border-top: 1px solid #f0e3e7; margin: 24px 0;">

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
        <div>
            <p class="muted">Kategori</p>
            <h3>{{ $product->category->name ?? '-' }}</h3>
        </div>

        <div>
            <p class="muted">Harga</p>
            <h3>Rp{{ number_format($product->price, 0, ',', '.') }}</h3>
        </div>

        <div>
            <p class="muted">Stok</p>
            <h3>{{ $product->stock }} unit</h3>
        </div>
    </div>

    <div style="margin-top: 28px;">
        <h3>Deskripsi Produk</h3>

        <p style="line-height: 1.8; white-space: pre-line;">
            {{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}
        </p>
    </div>

    <div style="margin-top: 28px;">
        <a href="{{ route('products.index') }}" class="btn btn-light">
            Kembali
        </a>
    </div>
</div>
@endsection