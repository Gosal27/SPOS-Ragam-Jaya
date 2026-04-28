@extends('layout.main')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <h3 class="mb-4 fw-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>Laporan Penjualan
            </h3>

            <!-- Filter -->
            <form method="GET" action="{{ route('history.index') }}" class="mb-4">
                <div class="row g-3">
                    {{-- Tanggal Awal + Tombol --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="form-control mb-2" value="{{ request('tanggal_awal') }}">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-50 mr-2">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm w-50">
                                <i class="bi bi-x-circle"></i> Reset
                            </a>
                        </div>
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                    </div>

                    {{-- Subtotal Min --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Subtotal Min</label>
                        <input type="number" name="harga_min" class="form-control" value="{{ request('harga_min') }}">
                    </div>

                    {{-- Subtotal Max --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Subtotal Max</label>
                        <input type="number" name="harga_max" class="form-control" value="{{ request('harga_max') }}">
                    </div>

                    {{-- Sorting --}}
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Urutkan</label>
                        <div class="d-flex align-items-start h-100">
                            <select name="sort" class="form-select" style="height: 38px;">
                                <option value="">Pilih</option>
                                <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Termurah</option>
                                <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Termahal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Riwayat Transaksi -->
            @forelse ($sales as $sale)
                <div class="card mb-3 shadow-sm border-start border-5 border-primary rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($sale->tanggal_transaksi)->format('d M Y') }}
                                {{-- , H:i --}}
                            </h6>
                            <div class="fw-semibold">Total Transaksi:
                                <span class="text-success">Rp{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('history.show', $sale->idSales) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="alert alert-info text-center mt-4">
                    <i class="bi bi-info-circle"></i> Tidak ada transaksi ditemukan.
                </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $sales->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
