@extends('layouts.app')

@section('title', 'Semua Produk | Lp_Beauty')
@section('page-title', 'Semua Produk')

@section('content')

<div class="page-heading">
    <h2>Daftar Produk ♡</h2>
</div>

{{-- PENCARIAN DAN FILTER KATEGORI --}}
<div class="panel">
    <form action="{{ route('products.index') }}" method="GET">
        <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:end;">

            <div style="flex:1; min-width:200px;">
                <label for="search" style="display:block; margin-bottom:8px; font-weight:600;">
                    Cari Produk
                </label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    placeholder="Nama produk, brand, atau SKU..."
                    value="{{ $search ?? '' }}"
                    style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #e6cbd4; border-radius:8px;"
                >
            </div>

            <div style="flex:1; min-width:180px;">
                <label for="category" style="display:block; margin-bottom:8px; font-weight:600;">
                    Kategori
                </label>

                <select
                    id="category"
                    name="category"
                    style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #e6cbd4; border-radius:8px;"
                >
                    <option value="">Semua Kategori</option>

                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected((string) ($selectedCategory ?? '') === (string) $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn">Cari</button>

            <a href="{{ route('products.index') }}" class="btn-light">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- TOMBOL TAMBAH PRODUK KHUSUS ADMIN --}}
@if(auth()->user()->role === 'admin')
    <div style="display:flex; justify-content:flex-end; margin:20px 0;">
        <a href="{{ route('products.create') }}" class="btn">
            + Tambah Produk
        </a>
    </div>
@endif

{{-- TABEL PRODUK --}}
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        {{-- SKU --}}
                        <td style="white-space:nowrap;">
                            <span style="display:inline-block; background:#fff0f5; color:#a44c6b; padding:5px 9px; border-radius:6px; font-weight:600; font-size:13px;">
                                {{ $product->sku ?: '-' }}
                            </span>
                        </td>

                        {{-- Nama Produk --}}
                        <td>
                            <a
                                href="{{ route('products.show', $product->id) }}"
                                style="color:#a64d79; text-decoration:none; font-weight:600;"
                            >
                                {{ $product->name }}
                            </a>
                        </td>

                        <td>{{ $product->brand ?: '-' }}</td>

                        <td>{{ $product->category->name ?? '-' }}</td>

                        <td style="white-space:nowrap; color:#b65779;">
                            <strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong>
                        </td>

                        <td>
                            <span style="display:inline-block; background:#fce8ef; color:#a44c6b; padding:5px 10px; border-radius:20px;">
                                {{ $product->stock }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            @if(auth()->user()->role === 'admin')
                                <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        style="display:inline-block; background:#f5d5e0; color:#8c3f5b; text-decoration:none; padding:8px 12px; border-radius:6px;"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini dari Lp_Beauty?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            style="background:#c96e8c; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="muted">Lihat saja</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:35px 15px;">
                            <h3 style="color:#a84f70;">
                                {{ !empty($search) || !empty($selectedCategory) ? 'Produk Tidak Ditemukan ♡' : 'Belum Ada Produk ♡' }}
                            </h3>

                            <p class="muted">
                                {{ !empty($search) || !empty($selectedCategory)
                                    ? 'Coba ubah kata pencarian atau kategori.'
                                    : (auth()->user()->role === 'admin'
                                        ? 'Tambahkan produk makeup atau skincare pertamamu.'
                                        : 'Belum ada produk yang ditampilkan.') }}
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection