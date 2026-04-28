@extends('layout.main')

@section('content')
<div class="container py-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-receipt"></i> Detail Transaksi</h4>
        </div>
        <div class="card-body">

            {{-- Info Transaksi --}}
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Tanggal Transaksi:</label>
                    <div class="text-muted">{{ $sale->tanggal_transaksi }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold">Total Transaksi:</label>
                    <div class="text-success h5">Rp{{ number_format($sale->subtotal, 0, ',', '.') }}</div>
                </div>
            </div>

            <hr>

            {{-- Daftar Produk --}}
            <h5 class="mb-3">🛒 Produk yang Dibeli:</h5>
            <ul class="list-group rounded-3 shadow-sm">
                @foreach ($sale->details as $detail)
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold">{{ $detail->produk->nama }}</div>
                            <small class="text-muted">Qty: {{ $detail->quantity }}</small>
                        </div>
                        <span class="badge bg-success rounded-pill fs-6">
                            Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="text-end mt-4">
        <a href="{{ route('history.index') }}" class="btn btn-outline-secondary">
            ← Kembali ke Riwayat
        </a>
    </div>
</div>
@endsection
