@extends('layouts.app')

@section('title', 'Edit Kategori | LP Beauty')
@section('page-title', 'Edit Kategori')

@section('content')

<div class="page-heading">
    <h2 style="color: #8f526b; margin-bottom: 8px;">
        ✏️ Edit Kategori
    </h2>

    <p style="color: #927985; margin-top: 0;">
        Ubah nama kategori tanpa menghapus produk yang terhubung.
    </p>
</div>

@if ($errors->any())
    <div class="alert-error">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="panel" style="max-width: 650px; margin: 0 auto;">

    <div style="width: 55px; height: 55px; background: #fbe7ef; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 27px; margin-bottom: 18px;">
        🌷
    </div>

    <h3 style="color: #8f526b; margin-top: 0;">
        Ubah Nama Kategori
    </h3>

    <p style="color: #927985; font-size: 14px; line-height: 1.7;">
        Kategori ini memiliki
        <strong>{{ $category->products()->count() }} produk</strong>.
        Mengubah nama tidak akan menghapus produk maupun riwayat transaksi.
    </p>

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin: 22px 0;">
            <label
                for="name"
                style="display: block; margin-bottom: 8px; font-weight: 600; color: #604653;"
            >
                Nama Kategori
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $category->name) }}"
                placeholder="Masukkan nama kategori"
                maxlength="100"
                required
                autofocus
                style="width: 100%; box-sizing: border-box; padding: 13px 14px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc; color: #604653; font-size: 14px;"
            >
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 10px;">

            <button
                type="submit"
                class="btn"
                style="background: #c76f95; color: white;"
            >
                💾 Simpan Perubahan
            </button>

            <a
                href="{{ route('categories.index') }}"
                class="btn"
                style="background: #f3e5eb; color: #8f526b; text-decoration: none;"
            >
                ← Kembali
            </a>

        </div>
    </form>

</div>

@endsection
