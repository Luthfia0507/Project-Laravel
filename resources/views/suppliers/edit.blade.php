
@extends('layouts.app')

@section('title', 'Edit Supplier | Lp_Beauty')
@section('page-title', 'Edit Supplier')

@section('content')
    <div class="page-heading">
        <h2>Edit Supplier ♡</h2>
        <p>Perbarui informasi pemasok produk Lp_Beauty.</p>
    </div>

    @if ($errors->any())
        <div class="panel" style="color:#a12650; background:#fff0f5;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <form action="{{ route('suppliers.update', $supplier->id) }}"
              method="POST">
            @csrf
            @method('PUT')

            <label for="name">Nama Supplier *</label>
            <input type="text" id="name" name="name"
                value="{{ old('name', $supplier->name) }}"
                maxlength="255" required
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;">

            <label for="contact_person">Nama Kontak</label>
            <input type="text" id="contact_person" name="contact_person"
                value="{{ old('contact_person', $supplier->contact_person) }}"
                maxlength="255"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;">

            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone"
                value="{{ old('phone', $supplier->phone) }}"
                maxlength="30"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;">

            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                value="{{ old('email', $supplier->email) }}"
                maxlength="255"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;">

            <label for="address">Alamat</label>
            <textarea id="address" name="address" rows="3"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;">{{ old('address', $supplier->address) }}</textarea>

            <button type="submit" class="btn">Simpan Perubahan</button>

            <a href="{{ route('suppliers.index') }}"
               class="btn"
               style="margin-left:8px; text-decoration:none;">
                Batal
            </a>
        </form>
    </div>
@endsection
