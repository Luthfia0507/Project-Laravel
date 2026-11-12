
@extends('layouts.app')

@section('title', 'Catat Pembelian | Lp_Beauty')
@section('page-title', 'Catat Pembelian')

@section('content')
    <div class="page-heading">
        <h2>Catat Pembelian Baru ♡</h2>
        <p>Stok produk akan bertambah otomatis setelah transaksi berhasil disimpan.</p>
    </div>

    @if($errors->any())
        <div class="panel" style="color:#b42318;">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        @if($suppliers->isEmpty())
            <p>Belum ada supplier. Tambahkan supplier terlebih dahulu melalui menu Supplier.</p>
        @elseif($products->isEmpty())
            <p>Belum ada produk. Tambahkan produk terlebih dahulu melalui menu Produk.</p>
        @else
            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf

                <label for="supplier_id">Supplier *</label>
                <select id="supplier_id" name="supplier_id" required
                    style="width:100%;padding:11px;margin:8px 0 16px;border:1px solid #e6cbd4;border-radius:8px;">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>

                <label for="purchase_date">Tanggal Pembelian *</label>
                <input type="date" id="purchase_date" name="purchase_date"
                    value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required
                    style="width:100%;padding:11px;margin:8px 0 16px;border:1px solid #e6cbd4;border-radius:8px;">

                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="3"
                    style="width:100%;padding:11px;margin:8px 0 16px;border:1px solid #e6cbd4;border-radius:8px;">{{ old('notes') }}</textarea>

                <h3>Produk yang Dibeli</h3>

                <div id="purchase-items">
                    <div class="purchase-item" style="border:1px solid #f0dce3;border-radius:10px;padding:14px;margin:12px 0;">
                        <label>Produk *</label>
                        <select name="items[0][product_id]" required
                            style="width:100%;padding:11px;margin:8px 0 12px;border:1px solid #e6cbd4;border-radius:8px;">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (stok saat ini: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>

                        <label>Jumlah *</label>
                        <input type="number" name="items[0][quantity]" min="1" step="1" required
                            style="width:100%;padding:11px;margin:8px 0 12px;border:1px solid #e6cbd4;border-radius:8px;">

                        <label>Harga Beli per Unit (Rp) *</label>
                        <input type="number" name="items[0][unit_price]" min="0.01" step="0.01" required
                            style="width:100%;padding:11px;margin:8px 0 12px;border:1px solid #e6cbd4;border-radius:8px;">
                    </div>
                </div>

                <button type="button" id="add-item"
                    style="padding:10px 14px;border:1px solid #d98eaa;border-radius:8px;background:#fff0f5;color:#87445e;cursor:pointer;">
                    + Tambah Produk
                </button>

                <div style="margin-top:20px;">
                    <button type="submit" class="btn">Simpan Pembelian</button>
                    <a href="{{ route('purchases.index') }}"
                        style="margin-left:12px;color:#87445e;">Kembali</a>
                </div>
            </form>
        @endif
    </div>

    <script>
        document.getElementById('add-item')?.addEventListener('click', function () {
            const container = document.getElementById('purchase-items');
            const index = container.querySelectorAll('.purchase-item').length;
            const template = container.querySelector('.purchase-item');
            const clone = template.cloneNode(true);

            clone.querySelectorAll('select, input').forEach(function (field) {
                field.name = field.name.replace(/items\[\d+\]/, 'items[' + index + ']');
                field.value = '';
            });

            container.appendChild(clone);
        });
    </script>
@endsection
