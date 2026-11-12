
@extends('layouts.app')

@section('title', 'Proses Retur | Lp_Beauty')
@section('page-title', 'Proses Retur')

@section('content')
    <div class="page-heading">
        <h2>Pengembalian Barang ♡</h2>
        <p>Catat barang yang dikembalikan pelanggan dengan teliti.</p>
    </div>

    @if($errors->any())
        <div class="panel" style="color:#b42345;">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <h3>Informasi Invoice</h3>

        <p>
            <strong>No. Invoice:</strong>
            {{ $sale->invoice_number }}
        </p>

        <p>
            <strong>Tanggal Penjualan:</strong>
            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}
        </p>

        <p>
            <strong>Total Penjualan:</strong>
            Rp{{ number_format((float) $sale->total_amount, 0, ',', '.') }}
        </p>
    </div>

    <form action="{{ route('sales.returns.store', $sale->id) }}"
          method="POST">
        @csrf

        <div class="panel">
            <h3>Informasi Retur</h3>

            <div style="margin-bottom:16px;">
                <label for="return_date"
                       style="display:block;margin-bottom:6px;font-weight:600;">
                    Tanggal Retur
                </label>

                <input type="date"
                       id="return_date"
                       name="return_date"
                       value="{{ old('return_date', date('Y-m-d')) }}"
                       required
                       style="width:100%;max-width:350px;padding:10px;border:1px solid #e4c8d3;border-radius:8px;">
            </div>

            <div style="margin-bottom:16px;">
                <label for="resolution"
                       style="display:block;margin-bottom:6px;font-weight:600;">
                    Jenis Penyelesaian
                </label>

                <select name="resolution"
                        id="resolution"
                        required
                        style="width:100%;max-width:400px;padding:10px;border:1px solid #e4c8d3;border-radius:8px;">
                    <option value="return_only"
                        {{ old('resolution', 'return_only') === 'return_only' ? 'selected' : '' }}>
                        Pengembalian Barang
                    </option>

                    <option value="refund"
                        {{ old('resolution') === 'refund' ? 'selected' : '' }}>
                        Pengembalian Uang (Refund)
                    </option>

                    <option value="exchange"
                        {{ old('resolution') === 'exchange' ? 'selected' : '' }}>
                        Penukaran Barang
                    </option>
                </select>

                <p style="font-size:13px;color:#806574;margin-top:6px;">
                    Pilih satu jenis penyelesaian untuk seluruh item dalam pengajuan ini.
                </p>
            </div>

            <div>
                <label for="reason"
                       style="display:block;margin-bottom:6px;font-weight:600;">
                    Alasan Retur
                </label>

                <textarea name="reason"
                          id="reason"
                          rows="3"
                          placeholder="Contoh: produk rusak saat diterima..."
                          style="width:100%;padding:10px;border:1px solid #e4c8d3;border-radius:8px;">{{ old('reason') }}</textarea>
            </div>
        </div>

        <div class="panel">
            <h3>Produk yang Dikembalikan</h3>

            <p style="color:#806574;font-size:14px;">
                Centang produk yang dikembalikan, lalu isi jumlah dan kondisi barangnya.
                Produk yang tidak dicentang tidak akan diproses.
            </p>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Pilih</th>
                            <th>Nama Produk</th>
                            <th>Dibeli</th>
                            <th>Sudah Diretur</th>
                            <th>Sisa Retur</th>
                            <th>Jumlah Retur</th>
                            <th>Kondisi Barang</th>
                            <th>Produk Pengganti</th>
                            <th>Jumlah Pengganti</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sale->items as $item)
                            @php
                                $returned = (int) ($returnedQuantities[$item->id] ?? 0);
                                $remaining = max(0, $item->quantity - $returned);
                                $oldItems = old('items', []);
                                $oldLine = collect($oldItems)->firstWhere(
                                    'sale_item_id',
                                    $item->id
                                );
                            @endphp

                            @if($remaining > 0)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               name="items[{{ $item->id }}][selected]"
                                               value="1"
                                               class="return-select"
                                               data-row="{{ $item->id }}"
                                               {{ $oldLine ? 'checked' : '' }}>
                                    </td>

                                    <td>
                                        {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                        <input type="hidden"
                                               name="items[{{ $item->id }}][sale_item_id]"
                                               value="{{ $item->id }}">
                                    </td>

                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $returned }}</td>
                                    <td>{{ $remaining }}</td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $item->id }}][quantity]"
                                               class="return-field return-quantity"
                                               data-row="{{ $item->id }}"
                                               min="1"
                                               max="{{ $remaining }}"
                                               value="{{ $oldLine['quantity'] ?? 1 }}"
                                               disabled
                                               style="width:75px;padding:7px;border:1px solid #e4c8d3;border-radius:6px;">
                                    </td>

                                    <td>
                                        <select name="items[{{ $item->id }}][condition]"
                                                class="return-field"
                                                data-row="{{ $item->id }}"
                                                disabled
                                                style="padding:7px;border:1px solid #e4c8d3;border-radius:6px;">
                                            <option value="resellable"
                                                {{ ($oldLine['condition'] ?? '') === 'resellable' ? 'selected' : '' }}>
                                                Layak jual
                                            </option>

                                            <option value="damaged"
                                                {{ ($oldLine['condition'] ?? '') === 'damaged' ? 'selected' : '' }}>
                                                Rusak
                                            </option>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="items[{{ $item->id }}][replacement_product_id]"
                                                class="return-field replacement-field"
                                                data-row="{{ $item->id }}"
                                                disabled
                                                style="min-width:150px;padding:7px;border:1px solid #e4c8d3;border-radius:6px;">
                                            <option value="">Pilih produk</option>

                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ (string) ($oldLine['replacement_product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} (Stok: {{ $product->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number"
                                               name="items[{{ $item->id }}][replacement_quantity]"
                                               class="return-field replacement-field"
                                               data-row="{{ $item->id }}"
                                               min="1"
                                               value="{{ $oldLine['replacement_quantity'] ?? 1 }}"
                                               disabled
                                               style="width:75px;padding:7px;border:1px solid #e4c8d3;border-radius:6px;">
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p style="font-size:13px;color:#806574;margin-top:12px;">
                Barang layak jual menambah stok. Barang rusak tidak menambah stok jual.
                Jika penukaran dipilih, stok produk pengganti akan berkurang.
            </p>
        </div>

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:18px;">
            <button type="submit"
                    style="padding:11px 18px;background:#e9a9c1;color:#542d40;border:0;border-radius:8px;font-weight:600;cursor:pointer;">
                Simpan Retur
            </button>

            <a href="{{ route('sales.show', $sale->id) }}"
               style="display:inline-block;padding:11px 18px;color:#87445e;text-decoration:none;">
                Batal
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resolution = document.getElementById('resolution');
            const checkboxes = document.querySelectorAll('.return-select');

            function updateRows() {
                checkboxes.forEach(function (checkbox) {
                    const row = checkbox.dataset.row;
                    const fields = document.querySelectorAll(
                        '.return-field[data-row="' + row + '"]'
                    );

                    fields.forEach(function (field) {
                        field.disabled = !checkbox.checked;

                        if (field.classList.contains('replacement-field')) {
                            field.required =
                                checkbox.checked &&
                                resolution.value === 'exchange';
                        }
                    });
                });
            }

            function updateResolution() {
                const isExchange = resolution.value === 'exchange';

                document.querySelectorAll('.replacement-field').forEach(function (field) {
                    const row = field.dataset.row;
                    const checkbox = document.querySelector(
                        '.return-select[data-row="' + row + '"]'
                    );

                    field.disabled = !isExchange || !checkbox.checked;
                    field.required = isExchange && checkbox.checked;
                });
            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateRows);
            });

            resolution.addEventListener('change', function () {
                updateRows();
                updateResolution();
            });

            updateRows();
            updateResolution();
        });
    </script>
@endsection
