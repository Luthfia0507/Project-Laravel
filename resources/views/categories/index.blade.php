@extends('layouts.app')

@section('title', 'Kategori | LP Beauty')
@section('page-title', 'Kategori')

@section('content')

<div class="page-heading">
    <h2 style="color: #8f526b; margin-bottom: 8px;">
        Kelola Kategori Produk 🌸
    </h2>
    <p style="color: #927985; margin-top: 0;">
    </p>
</div>

{{-- FORM TAMBAH KATEGORI --}}
<div class="panel" style="margin-bottom: 25px;">
    <h3 style="color: #8f526b; margin-top: 0;">
        🌷 Tambah Kategori Baru
    </h3>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 18px;">
            <label for="name"
                   style="display: block; margin-bottom: 8px; font-weight: 600;">
                Nama Kategori
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Contoh: Make-up atau Skincare"
                required
                maxlength="100"
                style="width: 100%; box-sizing: border-box; padding: 12px 14px; border: 1px solid #e8cbd7; border-radius: 10px; background: #fffafc; color: #604653; font-size: 14px;"
            >
        </div>

        <button type="submit" class="btn">
            + Simpan Kategori
        </button>
    </form>
</div>

{{-- DAFTAR KATEGORI --}}
<div class="panel">

    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
        <h3 style="color: #8f526b; margin: 0;">
            🌸 Daftar Kategori
        </h3>

        <span style="background: #fbe7ef; color: #8f526b; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
            {{ $categories->count() }} Kategori
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px;">

        @forelse ($categories as $category)

            <div style="box-sizing: border-box; background: #fff9fb; border: 1px solid #f0dce5; border-radius: 15px; padding: 22px;">

                <a
                    href="{{ route('products.index', ['category' => $category->id]) }}"
                    aria-label="Lihat produk kategori {{ $category->name }}"
                    style="display: block; text-decoration: none;"
                >
                    <div style="width: 48px; height: 48px; background: #fbe7ef; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 15px;">
                        🌷
                    </div>

                    <h4 style="color: #8f526b; font-size: 17px; margin: 0 0 10px;">
                        {{ $category->name }} →
                    </h4>

                    <p style="color: #927985; font-size: 13px; margin: 0 0 18px;">
                        {{ $category->products_count }} produk terdaftar
                    </p>
                </a>

                {{-- TOMBOL EDIT --}}
                <a
                    href="{{ route('categories.edit', $category->id) }}"
                    class="btn"
                    style="display: block; box-sizing: border-box; width: 100%; text-align: center; text-decoration: none; margin-bottom: 10px; background: #d98baa; color: white;"
                >
                    ✏️ Edit
                </a>

                {{-- TOMBOL HAPUS --}}
                @if ($category->products_count > 0)

                    <button
                        type="button"
                        class="btn"
                        disabled
                        title="Kategori masih digunakan oleh produk"
                        style="background: #e5d9df; color: #806d77; cursor: not-allowed; width: 100%;"
                    >
                        Hapus
                    </button>

                @else

                    <form
                        action="{{ route('categories.destroy', $category->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?');"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn"
                            style="background: #a94464; color: white; width: 100%;"
                        >
                            Hapus Kategori
                        </button>
                    </form>

                @endif

            </div>

        @empty

            <div style="grid-column: 1 / -1; text-align: center; padding: 35px 20px; background: #fff9fb; border: 1px dashed #e8cbd7; border-radius: 15px;">

                <div style="font-size: 38px; margin-bottom: 10px;">
                    🌸
                </div>

                <h4 style="color: #8f526b; margin-bottom: 8px;">
                    Belum Ada Kategori
                </h4>

                <p class="muted">
                    Silakan tambahkan kategori menggunakan formulir di atas.
                </p>

            </div>

        @endforelse

    </div>
</div>

@endsection