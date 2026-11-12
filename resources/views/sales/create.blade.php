@extends('layouts.app')

@section('title', 'Tambah Penjualan | Lp_Beauty')
@section('page-title', 'Tambah Penjualan')

@section('content')
<div class="sale-create-page">

    <div class="sale-page-heading">
        <div>
            <span class="sale-eyebrow">Lp_Beauty · Transaksi</span>
            <h2>Tambah Penjualan ♡</h2>
        </div>

        <a href="{{ route('sales.index') }}" class="sale-back-button">
            ← Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="sale-error-box">
            <strong>Periksa kembali data penjualanmu.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
        @csrf

        {{-- INFORMASI TRANSAKSI --}}
        <section class="sale-panel">
            <div class="sale-panel-heading">
                <div class="sale-section-icon">♡</div>
                <div>
                    <h3>Informasi Transaksi</h3>
                    <p>Lengkapi tanggal dan catatan penjualan.</p>
                </div>
            </div>

            <div class="sale-fields">
                <div class="sale-field">
                    <label for="sale_date">Tanggal Penjualan <span>*</span></label>
                    <input
                        type="date"
                        name="sale_date"
                        id="sale_date"
                        value="{{ old('sale_date', now()->format('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="sale-field">
                    <label for="notes">Catatan <small>(Opsional)</small></label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="3"
                        placeholder="Contoh: pembelian langsung di toko..."
                    >{{ old('notes') }}</textarea>
                </div>
            </div>
        </section>

        {{-- PRODUK --}}
        <section class="sale-panel">
            <div class="sale-panel-heading sale-product-heading">
                <div class="sale-panel-title">
                    <div class="sale-section-icon">🛍️</div>
                    <div>
                        <h3>Produk yang Dibeli</h3>
                    </div>
                </div>

                <span class="sale-required-note">* Wajib diisi</span>
            </div>

            <div id="sale-items">
                <div class="sale-item-row">
                    <div class="sale-item-top">
                        <span class="sale-item-number">PRODUK 01</span>
                        <span class="sale-item-caption">Detail produk</span>
                    </div>

                    <div class="sale-item-fields">
                        <div class="sale-field sale-product-field">
                            <label>Nama Produk <span>*</span></label>

                            <select
                                name="items[0][product_id]"
                                class="sale-product-select"
                                required
                            >
                                <option value="">Pilih produk</option>

                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        {{ (string) old('items.0.product_id') === (string) $product->id ? 'selected' : '' }}
                                        {{ $product->stock < 1 ? 'disabled' : '' }}
                                    >
                                        {{ $product->name }}
                                        — Stok {{ $product->stock }}
                                    </option>
                                @endforeach
                            </select>

                            <small class="sale-stock-info">
                                Pilih produk untuk melihat informasi stok.
                            </small>
                        </div>

                        <div class="sale-field sale-quantity-field">
                            <label>Jumlah <span>*</span></label>

                            <input
                                type="number"
                                name="items[0][quantity]"
                                class="sale-quantity-input"
                                min="1"
                                value="{{ old('items.0.quantity', 1) }}"
                                required
                            >

                            <small class="sale-quantity-hint">
                                Minimal 1 produk
                            </small>
                        </div>

                        <div class="sale-remove-wrapper">
                            <label class="sale-remove-label">Aksi</label>
                            <button
                                type="button"
                                class="sale-remove-button"
                                disabled
                            >
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sale-add-area">
                <button type="button" id="sale-add-item" class="sale-add-button">
                    ＋ Tambah Produk
                </button>

                <p>Kamu bisa menambahkan beberapa produk dalam satu transaksi.</p>
            </div>

            {{-- TOTAL --}}
            <div class="sale-total-card">
                <div>
                    <span class="sale-total-label">TOTAL HARGA BARANG</span>
                </div>

                <strong id="sale-total">Rp0</strong>
            </div>
        </section>

        {{-- TOMBOL --}}
        <div class="sale-form-actions">
            <a href="{{ route('sales.index') }}" class="sale-cancel-button">
                Batal
            </a>

            <button type="submit" class="sale-submit-button">
                ♡ Simpan Penjualan
            </button>
        </div>
    </form>

    <div class="sale-bottom-note">
        ♡ Pastikan produk dan jumlah sudah benar sebelum menyimpan transaksi.
    </div>
</div>

<style>
.sale-create-page {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    color: #49363d;
    font-size: 15px;
}

.sale-create-page * {
    box-sizing: border-box;
}

.sale-page-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.sale-eyebrow {
    color: #c96e8c;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 1.8px;
}

.sale-page-heading h2 {
    margin: 10px 0;
    font-size: 28px;
    line-height: 1.4;
    color: #704451;
}

.sale-page-heading p {
    margin: 0;
    color: #8b7079;
    font-size: 15px;
    line-height: 1.8;
}

.sale-back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 17px;
    border: 1px solid #f0dce2;
    border-radius: 9px;
    background: #fff;
    color: #a44c6b;
    font-size: 14px;
    text-decoration: none;
    transition: .2s;
}

.sale-back-button:hover,
.sale-cancel-button:hover {
    background: #fff1f5;
}

.sale-error-box {
    padding: 18px 22px;
    margin-bottom: 24px;
    border: 1px solid #f1cbd4;
    border-radius: 11px;
    background: #fff3f4;
    color: #a33e57;
    font-size: 14px;
    line-height: 1.8;
}

.sale-error-box ul {
    margin-bottom: 0;
    padding-left: 22px;
}

.sale-panel {
    padding: 30px;
    margin-bottom: 26px;
    border: 1px solid #f0dce2;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(180, 110, 135, .06);
}

.sale-panel-heading {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 27px;
}

.sale-section-icon {
    display: flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #fff1f5;
    color: #b65779;
    font-size: 23px;
}

.sale-panel-heading h3 {
    margin: 0 0 7px;
    color: #704451;
    font-size: 19px;
}

.sale-panel-heading p {
    margin: 0;
    color: #8b7079;
    font-size: 14px;
    line-height: 1.7;
}

.sale-fields {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 25px;
}

.sale-field {
    min-width: 0;
    margin-bottom: 0;
}

.sale-field label {
    display: block;
    margin-bottom: 11px;
    color: #704451;
    font-size: 14px;
    font-weight: bold;
}

.sale-field label span {
    color: #c96e8c;
}

.sale-field label small {
    color: #a88b95;
    font-weight: normal;
}

.sale-field input,
.sale-field select,
.sale-field textarea {
    display: block;
    width: 100%;
    min-height: 47px;
    padding: 12px 14px;
    border: 1px solid #ead4dd;
    border-radius: 9px;
    outline: none;
    background: #fffdfd;
    color: #49363d;
    font-family: Arial, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    transition: border-color .2s, box-shadow .2s;
}

.sale-field textarea {
    min-height: 100px;
    resize: vertical;
}

.sale-field input:focus,
.sale-field select:focus,
.sale-field textarea:focus {
    border-color: #c96e8c;
    box-shadow: 0 0 0 3px rgba(201, 110, 140, .12);
}

.sale-product-heading {
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.sale-panel-title {
    display: flex;
    align-items: center;
    gap: 15px;
}

.sale-required-note {
    color: #a88b95;
    font-size: 12px;
}

.sale-item-row {
    padding: 24px;
    margin-bottom: 20px;
    border: 1px solid #f0dce2;
    border-radius: 12px;
    background: #fffafb;
}

.sale-item-top {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 23px;
    padding-bottom: 16px;
    border-bottom: 1px solid #f3e2e7;
}

.sale-item-number {
    padding: 7px 11px;
    border-radius: 6px;
    background: #f7e1e8;
    color: #a44c6b;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: .8px;
}

.sale-item-caption {
    color: #987d87;
    font-size: 13px;
}

.sale-item-fields {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(120px, .8fr) 90px;
    align-items: start;
    gap: 22px;
}

.sale-stock-info,
.sale-quantity-hint {
    display: block;
    margin-top: 10px;
    color: #987d87;
    font-size: 12px;
    line-height: 1.7;
}

.sale-remove-label {
    visibility: hidden;
}

.sale-remove-button {
    width: 100%;
    min-height: 45px;
    padding: 10px;
    border: 1px solid #edc8d4;
    border-radius: 8px;
    background: #fff;
    color: #b45370;
    font-family: Arial, sans-serif;
    font-size: 13px;
    cursor: pointer;
}

.sale-remove-button:hover:not(:disabled) {
    background: #fff1f5;
}

.sale-remove-button:disabled {
    opacity: .45;
    cursor: not-allowed;
}

.sale-add-area {
    padding-top: 3px;
}

.sale-add-button {
    padding: 13px 17px;
    border: 1px solid #e9c8d5;
    border-radius: 9px;
    background: #fff1f5;
    color: #a44c6b;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: .2s;
}

.sale-add-button:hover {
    background: #f7e1e8;
}

.sale-add-area p {
    margin: 12px 0 0;
    color: #987d87;
    font-size: 13px;
    line-height: 1.7;
}

.sale-total-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 30px;
    padding: 25px;
    border: 1px solid #f0dce2;
    border-radius: 12px;
    background: linear-gradient(110deg, #fff1f5, #fff9fb);
}

.sale-total-label {
    color: #a44c6b;
    font-size: 12px;
    font-weight: bold;
    letter-spacing: 1.3px;
}

.sale-total-card p {
    margin: 8px 0 0;
    color: #8b7079;
    font-size: 13px;
    line-height: 1.7;
}

.sale-total-card strong {
    color: #a44c6b;
    font-size: 29px;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.sale-form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 14px;
    margin-top: 30px;
}

.sale-cancel-button,
.sale-submit-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
    padding: 13px 21px;
    border-radius: 9px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}

.sale-cancel-button {
    border: 1px solid #f0dce2;
    background: #fff;
    color: #8c5268;
}

.sale-submit-button {
    border: none;
    background: #c96e8c;
    color: #fff;
    font-weight: bold;
}

.sale-submit-button:hover {
    background: #ad5272;
}

.sale-bottom-note {
    margin-top: 25px;
    padding: 17px 20px;
    border-radius: 10px;
    background: #fff1f5;
    color: #8c6574;
    font-size: 13px;
    line-height: 1.8;
}

@media (max-width: 760px) {
    .sale-page-heading h2 {
        font-size: 24px;
    }

    .sale-fields {
        grid-template-columns: 1fr;
        gap: 23px;
    }

    .sale-item-fields {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .sale-remove-label {
        display: none !important;
    }

    .sale-remove-button {
        width: auto;
        min-width: 100px;
    }

    .sale-panel {
        padding: 22px;
    }

    .sale-item-row {
        padding: 19px;
    }
}

@media (max-width: 480px) {
    .sale-page-heading {
        align-items: stretch;
    }

    .sale-back-button {
        align-self: flex-start;
    }

    .sale-panel {
        padding: 17px;
    }

    .sale-panel-heading h3 {
        font-size: 17px;
    }

    .sale-panel-heading p {
        font-size: 13px;
    }

    .sale-total-card {
        padding: 19px;
    }

    .sale-total-card strong {
        font-size: 25px;
    }

    .sale-form-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .sale-cancel-button,
    .sale-submit-button {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('sale-items');
    const addButton = document.getElementById('sale-add-item');
    const totalDisplay = document.getElementById('sale-total');
    const form = document.getElementById('sale-form');

    let nextIndex = 1;

    function rupiah(value) {
        return 'Rp' + Math.round(value).toLocaleString('id-ID');
    }

    function updateRow(row) {
        const select = row.querySelector('.sale-product-select');
        const quantity = row.querySelector('.sale-quantity-input');
        const stockInfo = row.querySelector('.sale-stock-info');
        const option = select.options[select.selectedIndex];

        if (option && option.value) {
            const stock = Number(option.dataset.stock);
            const price = Number(option.dataset.price);

            quantity.max = stock;

            stockInfo.textContent =
                'Stok tersedia: ' + stock + ' produk · Harga: ' + rupiah(price);

            if (Number(quantity.value) > stock) {
                quantity.setCustomValidity('Jumlah melebihi stok tersedia.');
            } else {
                quantity.setCustomValidity('');
            }
        } else {
            quantity.removeAttribute('max');
            quantity.setCustomValidity('');
            stockInfo.textContent =
                'Pilih produk untuk melihat informasi stok.';
        }
    }

    function updateOptions() {
        const rows = Array.from(list.querySelectorAll('.sale-item-row'));

        rows.forEach(function (row) {
            const select = row.querySelector('.sale-product-select');
            const currentValue = select.value;

            Array.from(select.options).forEach(function (option) {
                if (!option.value) return;

                const selectedElsewhere = rows.some(function (otherRow) {
                    const otherSelect =
                        otherRow.querySelector('.sale-product-select');

                    return otherSelect !== select &&
                        otherSelect.value === option.value;
                });

                option.disabled =
                    Number(option.dataset.stock) < 1 ||
                    selectedElsewhere;

                if (option.value === currentValue) {
                    option.disabled = false;
                }
            });
        });
    }

    function updateTotal() {
        let total = 0;

        list.querySelectorAll('.sale-item-row').forEach(function (row) {
            const select = row.querySelector('.sale-product-select');
            const quantity = row.querySelector('.sale-quantity-input');
            const option = select.options[select.selectedIndex];

            if (option && option.value) {
                total += Number(option.dataset.price) *
                    Math.max(0, Number(quantity.value) || 0);
            }

            updateRow(row);
        });

        totalDisplay.textContent = rupiah(total);

        const rows = list.querySelectorAll('.sale-item-row');

        rows.forEach(function (row) {
            row.querySelector('.sale-remove-button').disabled =
                rows.length === 1;
        });
    }

    function refresh() {
        updateOptions();
        updateTotal();
    }

    function updateItemNumbers() {
        list.querySelectorAll('.sale-item-row').forEach(function (row, index) {
            row.querySelector('.sale-item-number').textContent =
                'PRODUK ' + String(index + 1).padStart(2, '0');
        });
    }

    addButton.addEventListener('click', function () {
        const firstRow = list.querySelector('.sale-item-row');
        const newRow = firstRow.cloneNode(true);

        const select = newRow.querySelector('.sale-product-select');
        const quantity = newRow.querySelector('.sale-quantity-input');

        select.name = `items[${nextIndex}][product_id]`;
        select.value = '';

        quantity.name = `items[${nextIndex}][quantity]`;
        quantity.value = 1;
        quantity.setCustomValidity('');
        quantity.removeAttribute('max');

        newRow.querySelector('.sale-stock-info').textContent =
            'Pilih produk untuk melihat informasi stok.';

        list.appendChild(newRow);
        nextIndex++;

        updateItemNumbers();
        refresh();
    });

    list.addEventListener('change', refresh);
    list.addEventListener('input', refresh);

    list.addEventListener('click', function (event) {
        if (event.target.classList.contains('sale-remove-button')) {
            const rows = list.querySelectorAll('.sale-item-row');

            if (rows.length > 1) {
                event.target.closest('.sale-item-row').remove();
                updateItemNumbers();
                refresh();
            }
        }
    });

    form.addEventListener('submit', function (event) {
        const rows = list.querySelectorAll('.sale-item-row');
        const selectedProducts = [];
        let valid = true;

        rows.forEach(function (row) {
            const select = row.querySelector('.sale-product-select');
            const quantity = row.querySelector('.sale-quantity-input');
            const productId = select.value;
            const amount = Number(quantity.value);
            const option = select.options[select.selectedIndex];
            const stock = option ? Number(option.dataset.stock) : 0;

            if (!productId || !Number.isInteger(amount) ||
                amount < 1 || amount > stock ||
                selectedProducts.includes(productId)) {
                valid = false;
            }

            selectedProducts.push(productId);
        });

        if (!valid) {
            event.preventDefault();
            alert(
                'Periksa kembali produk dan jumlahnya. ' +
                'Pastikan produk tidak dipilih dua kali dan jumlah tidak melebihi stok.'
            );
        }
    });

    refresh();
});
</script>
@endsection