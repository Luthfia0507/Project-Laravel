@extends('layouts.app')

@section('title', 'Tambah Produk | Lp_Beauty')
@section('page-title', 'Tambah Produk')

@section('content')

<div class="page-heading">
    <h2 style="color: #8f526b; margin-bottom: 8px;">
        Tambah Produk Baru 💗
    </h2>

    <p style="color: #927985; margin-top: 0;">
        Lengkapi informasi produk makeup dan skincare.
    </p>
</div>

{{-- Pesan validasi --}}
@if ($errors->any())
    <div class="alert-error">
        <strong>Ups! Periksa kembali data berikut:</strong>

        <ul style="margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Form Tambah Produk --}}
<div class="panel" style="max-width: 800px;">

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        {{-- Nama Produk --}}
        <div style="margin-bottom: 20px;">
            <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Nama Produk <span style="color: #c96e8c;">*</span>
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Moisturizer Hydrating"
                required
                maxlength="255"
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc;"
            >
        </div>

        {{-- SKU Otomatis --}}
        <div style="margin-bottom: 20px;">
            <label for="sku" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Kode SKU
            </label>

            <input
                type="text"
                id="sku"
                name="sku"
                value=""
                placeholder="Pilih kategori terlebih dahulu"
                readonly
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fff0f5; color: #8f526b; font-weight: 600;"
            >

            <small id="sku-message" style="display: block; margin-top: 6px; color: #927985;">
                SKU akan dibuat otomatis sesuai kategori yang dipilih.
            </small>
        </div>

        {{-- Brand --}}
        <div style="margin-bottom: 20px;">
            <label for="brand" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Brand / Merek
            </label>

            <input
                type="text"
                id="brand"
                name="brand"
                value="{{ old('brand') }}"
                placeholder="Contoh: Wardah, Skintific, Emina"
                maxlength="255"
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc;"
            >
        </div>

        {{-- Kategori --}}
        <div style="margin-bottom: 20px;">
            <label for="category_id" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Kategori <span style="color: #c96e8c;">*</span>
            </label>

            <select
                id="category_id"
                name="category_id"
                required
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc;"
            >
                <option value="">Pilih kategori produk</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id') == $category->id)
                    >
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Harga --}}
        <div style="margin-bottom: 20px;">
            <label for="price" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Harga (Rp) <span style="color: #c96e8c;">*</span>
            </label>

            <input
                type="number"
                id="price"
                name="price"
                min="0.01"
                step="0.01"
                value="{{ old('price') }}"
                placeholder="Contoh: 35000"
                required
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc;"
            >

            <small style="display: block; margin-top: 6px; color: #927985;">
                Masukkan angka tanpa tanda titik atau Rp.
            </small>
        </div>

        {{-- Stok --}}
        <div style="margin-bottom: 20px;">
            <label for="stock" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Jumlah Stok <span style="color: #c96e8c;">*</span>
            </label>

            <input
                type="number"
                id="stock"
                name="stock"
                min="0"
                value="{{ old('stock', 0) }}"
                placeholder="Contoh: 20"
                required
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc;"
            >
        </div>

        {{-- Deskripsi --}}
        <div style="margin-bottom: 25px;">
            <label for="description" style="display: block; margin-bottom: 8px; font-weight: 600;">
                Deskripsi Produk
            </label>

            <textarea
                id="description"
                name="description"
                rows="4"
                placeholder="Tuliskan deskripsi produk..."
                style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc; resize: vertical;"
            >{{ old('description') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div style="display: flex; flex-wrap: wrap; gap: 12px;">
            <button type="submit" id="submit-button" class="btn">
                💗 Simpan Produk
            </button>

            <a
                href="{{ route('products.index') }}"
                class="btn btn-light"
                style="text-decoration: none; display: inline-flex; align-items: center;"
            >
                ← Kembali
            </a>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const skuInput = document.getElementById('sku');
    const skuMessage = document.getElementById('sku-message');
    const submitButton = document.getElementById('submit-button');

    let requestNumber = 0;

    async function updateSku() {
        const categoryId = categorySelect.value;
        const currentRequest = ++requestNumber;

        skuInput.value = '';
        submitButton.disabled = true;

        if (!categoryId) {
            skuInput.placeholder = 'Pilih kategori terlebih dahulu';
            skuMessage.textContent = 'SKU akan dibuat otomatis sesuai kategori yang dipilih.';
            return;
        }

        skuInput.placeholder = 'Sedang membuat SKU...';
        skuMessage.textContent = 'Mohon tunggu sebentar...';

        try {
            const url = new URL(
                @json(route('products.next-sku')),
                window.location.origin
            );

            url.searchParams.set('category_id', categoryId);

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (currentRequest !== requestNumber) {
                return;
            }

            if (!response.ok) {
                throw new Error(
                    data.message || 'SKU tidak berhasil dibuat.'
                );
            }

            skuInput.value = data.sku;
            skuMessage.textContent = 'SKU dibuat otomatis berdasarkan kategori.';
            submitButton.disabled = false;

        } catch (error) {
            if (currentRequest !== requestNumber) {
                return;
            }

            skuInput.value = '';
            skuInput.placeholder = 'SKU belum tersedia';
            skuMessage.textContent = error.message
                || 'Gagal membuat SKU. Silakan pilih kategori kembali.';

            submitButton.disabled = true;
        }
    }

    categorySelect.addEventListener('change', updateSku);

    // Jika kategori sudah terpilih setelah validasi gagal,
    // buat ulang SKU untuk kategori tersebut.
    if (categorySelect.value) {
        updateSku();
    }
});
</script>

@endsection