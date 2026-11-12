
@extends('layouts.app')

@section('title', 'Detail Pembelian | Lp_Beauty')
@section('page-title', 'Detail Pembelian')

@section('content')
    <div class="page-heading">
        <h2>Detail Pembelian ♡</h2>
        <p>Rincian produk yang dibeli dari supplier.</p>
    </div>

    <div class="panel">
        <p><strong>No. Faktur:</strong> {{ $purchase->invoice_number }}</p>
        <p><strong>Tanggal:</strong> {{ $purchase->purchase_date->format('d-m-Y') }}</p>
        <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
        <p><strong>Dicatat oleh:</strong> {{ $purchase->user->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>

        @if($purchase->notes)
            <p><strong>Catatan:</strong> {{ $purchase->notes }}</p>
        @endif
    </div>

    <div class="panel">
        <h3>Produk yang Dibeli</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>
                                Rp{{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp{{ number_format((float) $item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Pembelian</th>
                        <th>
                            Rp{{ number_format((float) $purchase->total_amount, 0, ',', '.') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <a href="{{ route('purchases.index') }}"
           style="display:inline-block;margin-top:16px;color:#87445e;">
            ← Kembali ke Pembelian
        </a>
    </div>
@endsection
