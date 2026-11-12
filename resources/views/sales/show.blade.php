
@extends('layouts.app')

@section('title', 'Detail Penjualan | Lp_Beauty')
@section('page-title', 'Detail Penjualan')

@section('content')
    <div class="page-heading">
        <h2>Detail Penjualan ♡</h2>
        <p>Rincian produk terjual dan riwayat pengembalian barang.</p>
    </div>

    @if(session('success'))
        <div class="panel" style="color:#28784b;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="panel" style="color:#b42345;">
            <strong>Proses belum berhasil:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <p>
            <strong>No. Invoice:</strong>
            {{ $sale->invoice_number }}
        </p>

        <p>
            <strong>Tanggal:</strong>
            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y') }}
        </p>

        <p>
            <strong>Dicatat oleh:</strong>
            {{ $sale->user->name ?? '-' }}
        </p>

        <p>
            <strong>Status:</strong>
            {{ ucfirst($sale->status) }}
        </p>

        @if($sale->notes)
            <p>
                <strong>Catatan:</strong>
                {{ $sale->notes }}
            </p>
        @endif
    </div>

    <div class="panel">
        <h3>Produk yang Terjual</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Jumlah Beli</th>
                        <th>Sudah Diretur</th>
                        <th>Sisa Bisa Diretur</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($sale->items as $item)
                        @php
                            $returnedQuantity = \App\Models\SaleReturnItem::where(
                                'sale_item_id',
                                $item->id
                            )->sum('quantity');

                            $remainingQuantity = max(
                                0,
                                $item->quantity - $returnedQuantity
                            );
                        @endphp

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item->product->name ?? 'Produk tidak tersedia' }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>{{ $returnedQuantity }}</td>

                            <td>{{ $remainingQuantity }}</td>

                            <td>
                                Rp{{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                Belum ada detail produk pada transaksi ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="6">Total Penjualan</th>
                        <th>
                            Rp{{ number_format((float) $sale->total_amount, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        @php
            $hasReturnableItems = $sale->items->contains(function ($item) {
                $returned = \App\Models\SaleReturnItem::where(
                    'sale_item_id',
                    $item->id
                )->sum('quantity');

                return $returned < $item->quantity;
            });
        @endphp

        @if($hasReturnableItems)
            <a href="{{ route('sales.returns.create', $sale->id) }}"
               style="display:inline-block;margin-top:18px;padding:10px 16px;background:#e9a9c1;color:#542d40;border-radius:8px;text-decoration:none;font-weight:600;">
                + Proses Retur
            </a>
        @else
            <p style="margin-top:18px;color:#87445e;">
                Semua jumlah produk pada invoice ini sudah diretur.
            </p>
        @endif

        <a href="{{ route('sales.index') }}"
           style="display:inline-block;margin-top:16px;margin-left:10px;color:#87445e;">
            ← Kembali ke Penjualan
        </a>
    </div>
@endsection
