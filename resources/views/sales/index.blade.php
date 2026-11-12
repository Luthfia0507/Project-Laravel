@extends('layouts.app')

@section('title', 'Riwayat Penjualan | Lp_Beauty')
@section('page-title', 'Riwayat Penjualan')

@section('content')
<div class="sales-page">

    <div class="sales-heading">
        <div>
            <span class="sales-eyebrow">Lp_Beauty · Transaksi</span>
            <h2>Riwayat Penjualan ♡</h2>
        </div>

        <a href="{{ route('sales.create') }}" class="sales-primary-btn">
            <span>＋</span> Tambah Penjualan
        </a>
    </div>

    <div class="sales-summary">
        <div class="summary-icon">🛍️</div>
        <div>
            <span>Total Transaksi Lp_Beauty</span>
            <strong>{{ $sales->total() }} transaksi</strong>
        </div>
    </div>

    <div class="sales-panel">
        <div class="panel-heading">
            <div>
                <h3>Daftar Transaksi</h3>
                <p>Informasi invoice, tanggal, kasir, dan total penjualan.</p>
            </div>
            <span class="sales-badge">♡ Penjualan</span>
        </div>

        <div class="sales-table-wrap">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sales->firstItem() + $loop->index }}</td>

                            <td>
                                <span class="invoice-number">
                                    {{ $sale->invoice_number }}
                                </span>
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                            </td>

                            <td>{{ $sale->user->name ?? '-' }}</td>

                            <td>
                                <strong class="sales-amount">
                                    Rp{{ number_format($sale->total_amount, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                <span class="status-completed">
                                    ● {{ ucfirst($sale->status) }}
                                </span>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('sales.show', $sale->id) }}"
                                   class="sales-detail-btn">
                                    ♡ Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sales-empty">
                                    <div>🛍️</div>
                                    <strong>Belum ada transaksi</strong>
                                    <p>Transaksi penjualan yang berhasil akan muncul di sini.</p>
                                    <a href="{{ route('sales.create') }}"
                                       class="sales-primary-btn">
                                        Mulai Penjualan
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="sales-pagination">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

    <div class="sales-footer-note">
        ♡ Pastikan setiap transaksi sesuai dengan produk dan jumlah yang dibeli pelanggan.
    </div>

</div>

<style>
.sales-page {
    display: block;
    position: static;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
    padding-top: 10px;
    color: #49363d;
    box-sizing: border-box;
}

.sales-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 22px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}

.sales-eyebrow {
    color: #c96e8c;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 2px;
}

.sales-heading h2 {
    margin: 9px 0;
    color: #704451;
    font-size: 27px;
}

.sales-heading p,
.panel-heading p {
    margin: 0;
    color: #987d87;
    line-height: 1.7;
    font-size: 13px;
}

.sales-primary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px 19px;
    background: #c96e8c;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: bold;
    box-shadow: 0 5px 13px rgba(201, 110, 140, .16);
    transition: .2s;
}

.sales-primary-btn:hover {
    background: #ad5272;
    color: white;
    transform: translateY(-1px);
}

.sales-summary {
    display: flex;
    align-items: center;
    gap: 17px;
    padding: 23px 25px;
    margin-bottom: 26px;
    background: linear-gradient(120deg, #fff 0%, #fff0f5 100%);
    border: 1px solid #f3dce5;
    border-radius: 16px;
}

.summary-icon {
    width: 54px;
    height: 54px;
    display: grid;
    place-items: center;
    border-radius: 14px;
    background: #f7e1e8;
    font-size: 24px;
}

.sales-summary span,
.sales-summary small {
    display: block;
    color: #987d87;
    font-size: 12px;
}

.sales-summary strong {
    display: block;
    margin: 5px 0;
    font-size: 21px;
    color: #a44c6b;
}

.sales-panel {
    background: white;
    border: 1px solid #f0dce2;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(180, 110, 135, .06);
}

.panel-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 23px;
}

.panel-heading h3 {
    margin: 0 0 7px;
    color: #704451;
    font-size: 18px;
}

.sales-badge {
    background: #fff1f5;
    color: #a44c6b;
    padding: 9px 13px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.sales-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.sales-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    min-width: 850px;
}

.sales-table th {
    padding: 16px 15px;
    text-align: left;
    background: #fff1f5;
    color: #8c5268;
    font-size: 12px;
    font-weight: bold;
    white-space: nowrap;
    border-bottom: 1px solid #f0dce2;
}

.sales-table th:first-child {
    border-radius: 9px 0 0 0;
}

.sales-table th:last-child {
    border-radius: 0 9px 0 0;
}

.sales-table td {
    padding: 19px 15px;
    border-bottom: 1px solid #f5e9ed;
    font-size: 13px;
    vertical-align: middle;
}

.sales-table tbody tr:hover {
    background: #fffafb;
}

.invoice-number {
    color: #a44c6b;
    font-weight: bold;
    white-space: nowrap;
}

.sales-amount {
    color: #704451;
    white-space: nowrap;
}

.status-completed {
    display: inline-block;
    padding: 7px 10px;
    border-radius: 20px;
    background: #e8f7ed;
    color: #287a45;
    font-size: 11px;
    white-space: nowrap;
}

.sales-detail-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 9px 13px;
    background: #fff0f5;
    color: #a44c6b;
    border: 1px solid #f1d2df;
    border-radius: 9px;
    font-size: 12px;
    font-weight: bold;
    text-decoration: none;
    white-space: nowrap;
    transition: .2s;
}

.sales-detail-btn:hover {
    background: #c96e8c;
    border-color: #c96e8c;
    color: white;
}

.sales-empty {
    padding: 45px 15px;
    text-align: center;
}

.sales-empty div {
    font-size: 35px;
    margin-bottom: 12px;
}

.sales-empty strong {
    display: block;
    color: #704451;
    font-size: 16px;
}

.sales-empty p {
    margin: 8px 0 20px;
    color: #987d87;
    font-size: 13px;
}

.sales-pagination {
    padding-top: 22px;
}

.sales-footer-note {
    margin-top: 20px;
    color: #987d87;
    font-size: 12px;
    line-height: 1.8;
}

@media (max-width: 600px) {
    .sales-heading h2 {
        font-size: 23px;
    }

    .sales-primary-btn {
        width: 100%;
    }

    .sales-panel {
        padding: 17px;
    }

    .sales-summary {
        padding: 18px;
    }
}
</style>
@endsection