
@extends('layouts.app')

@section('title', 'Transaksi Pembelian | Lp_Beauty')
@section('page-title', 'Transaksi Pembelian')

@section('content')
    <div class="page-heading">
        <h2>Riwayat Pembelian ♡</h2>
    </div>

    @if(session('success'))
        <div class="panel" style="color:#276749;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="panel" style="color:#b42318;">
            <strong>Transaksi belum berhasil:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel">
        <a href="{{ route('purchases.create') }}" class="btn">
            + Catat Pembelian
        </a>
    </div>

    <div class="panel">
        <h3>Daftar Transaksi</h3>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Dicatat Oleh</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $purchase->invoice_number }}</td>
                            <td>{{ $purchase->purchase_date->format('d-m-Y') }}</td>
                            <td>{{ $purchase->supplier->name }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>Rp{{ number_format((float) $purchase->total_amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($purchase->status) }}</td>             
                        <td>
                            <a href="{{ route('purchases.show', $purchase->id) }}"
                            style="color:#87445e;">
                                Lihat Detail
                            </a>
                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Belum ada transaksi pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
