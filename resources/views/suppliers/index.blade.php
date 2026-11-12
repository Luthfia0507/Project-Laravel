
@extends('layouts.app')

@section('title', 'Supplier | Lp_Beauty')
@section('page-title', 'Manajemen Supplier')

@section('content')
    <div class="page-heading">
        <h2>Daftar Supplier ♡</h2>
    </div>

    @if ($errors->any())
        <div class="panel" style="color:#a12650; background:#fff0f5;">
            <strong>Periksa kembali data yang kamu masukkan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:flex; justify-content:flex-end; margin-bottom:18px;">
        <button
            type="button"
            class="btn"
            id="toggleSupplierForm"
            onclick="toggleSupplierForm()"
        >
            + Tambah Supplier
        </button>
    </div>

    <div class="panel" id="supplierFormPanel"
         style="display:{{ $errors->any() ? 'block' : 'none' }};">
        <h3>Tambah Supplier ♡</h3>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <label for="name">Nama Supplier *</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                maxlength="255"
                required
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;"
            >

            <label for="contact_person">Nama Kontak</label>
            <input
                type="text"
                id="contact_person"
                name="contact_person"
                value="{{ old('contact_person') }}"
                maxlength="255"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;"
            >

            <label for="phone">Nomor Telepon</label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                maxlength="30"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;"
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                maxlength="255"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;"
            >

            <label for="address">Alamat</label>
            <textarea
                id="address"
                name="address"
                rows="3"
                style="width:100%; padding:11px; margin:8px 0 12px; border:1px solid #e6cbd4; border-radius:8px;"
            >{{ old('address') }}</textarea>

            <button type="submit" class="btn">
                Simpan Supplier
            </button>

            <button
                type="button"
                class="btn"
                onclick="toggleSupplierForm()"
                style="margin-left:8px;"
            >
                Batal
            </button>
        </form>
    </div>

    <div class="panel">
        <h3>Data Supplier</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Supplier</th>
                        <th>Nama Kontak</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_person ?: '-' }}</td>
                            <td>{{ $supplier->phone ?: '-' }}</td>
                            <td>{{ $supplier->email ?: '-' }}</td>
                            <td>{{ $supplier->address ?: '-' }}</td>

                            <td style="white-space:nowrap;">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                class="btn"
                                style="text-decoration:none;">
                                    Edit
                                </a>

                                <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn"
                                            style="background:#a94464;">
                                        Hapus
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;">
                                Belum ada data supplier ♡
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleSupplierForm() {
            const form = document.getElementById('supplierFormPanel');
            const button = document.getElementById('toggleSupplierForm');

            if (form.style.display === 'none') {
                form.style.display = 'block';
                button.textContent = '− Tutup Formulir';
                form.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            } else {
                form.style.display = 'none';
                button.textContent = '+ Tambah Supplier';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('supplierFormPanel');
            const button = document.getElementById('toggleSupplierForm');

            if (form.style.display !== 'none') {
                button.textContent = '− Tutup Formulir';
            }
        });
    </script>
@endsection
