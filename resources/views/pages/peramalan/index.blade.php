@extends('layout.main')

@section('content')
<div class="container-fluid mt-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-chart-line text-primary me-2"></i>
                Data Penjualan & Peramalan (Least Square)
            </h4>
            <p class="text-muted small mb-0">
                Pilih jumlah bulan peramalan, lalu tekan tombol "Lakukan Peramalan".
            </p>
        </div>
    </div>

    <!-- Form Pilihan Bulan -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('peramalan.store') }}" class="d-flex align-items-center gap-3">
                @csrf
                <label class="fw-semibold mb-0">Jumlah Bulan Peramalan:</label>

                <input type="number"
                    name="bulan_peramalan"
                    class="form-control w-auto"
                    min="1"
                    max="24"
                    value="{{ $bulanPeramalan }}"
                    required>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calculator me-1"></i> Lakukan Peramalan
                </button>
            </form>
        </div>
    </div>

    <!-- ========================= -->
    <!-- 1. DATA PENJUALAN HISTORY -->
    <!-- ========================= -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white py-2 d-flex align-items-center">
            <i class="fas fa-table me-2"></i>
            <h6 class="mb-0 fw-semibold">Data Penjualan 12 Bulan Terakhir</h6>
        </div>

        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center small mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Produk</th>

                            @php
                                $bulanList = collect();
                                foreach ($hasilPeramalan as $p) {
                                    foreach ($p['data_bulan'] as $row) {
                                        $bulanList->push($row->bulan);
                                    }
                                }
                                $bulanList = $bulanList->unique()->sort()->values();
                            @endphp

                            @foreach ($bulanList as $bulan)
                                <th>{{ $bulan }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($hasilPeramalan as $produk)
                            <tr>
                                <td class="text-start fw-semibold">{{ $produk['nama_produk'] }}</td>

                                @foreach ($bulanList as $bulan)
                                    @php
                                        $found = collect($produk['data_bulan'])->firstWhere('bulan', $bulan);
                                    @endphp
                                    <td>{{ $found ? number_format($found->total_qty) : '-' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- ========================= -->
    <!-- 2. HASIL PERAMALAN BARU   -->
    <!-- ========================= -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="card-header bg-success text-white py-2 d-flex align-items-center">
            <i class="fas fa-chart-bar me-2"></i>
            <h6 class="mb-0 fw-semibold">Hasil Peramalan {{ $bulanPeramalan }} Bulan ke Depan</h6>
        </div>

        <div class="card-body p-2">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center small mb-0">
                    <thead class="table-success">
                        <tr>
                            <th>Nama Produk</th>

                            @php
                                $bulanPrediksi = collect();
                                foreach ($hasilPeramalan as $p) {
                                    foreach ($p['forecast'] as $row) {
                                        $bulanPrediksi->push($row['bulan']);
                                    }
                                }
                                $bulanPrediksi = $bulanPrediksi->unique()->sort()->values();
                            @endphp

                            @foreach ($bulanPrediksi as $bulan)
                                <th>{{ $bulan }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($hasilPeramalan as $produk)
                            <tr>
                                <td class="text-start fw-semibold">{{ $produk['nama_produk'] }}</td>

                                @foreach ($bulanPrediksi as $bulan)
                                    @php
                                        $found = collect($produk['forecast'])->firstWhere('bulan', $bulan);
                                    @endphp

                                    <td class="bg-warning bg-opacity-25 fw-bold">
                                        {{ $found ? number_format($found['prediksi_qty']) : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-2 small text-muted">
                <i class="fas fa-info-circle me-1"></i>
                <span class="fw-semibold text-dark">Keterangan:</span> Sel kuning = hasil prediksi.
            </div>

        </div>
    </div>

<!-- ===================================================== -->
<!-- 3. HISTORY PERAMALAN                                  -->
<!-- ===================================================== -->
<div class="card border-0 shadow-lg rounded-4 mb-5">
    <div class="card-header bg-dark text-white py-4 rounded-top-4">
        <div class="d-flex align-items-center">
            <span class="bg-white bg-opacity-10 p-2 rounded-circle me-3">
                <i class="fas fa-history fs-4 text-white"></i>
            </span>
            <h4 class="mb-0 fw-bold">History Peramalan</h4>
        </div>
    </div>

    <div class="card-body px-4 pb-4">

        @if ($batches->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-box-open fs-1 text-muted mb-3"></i>
                <p class="text-muted fs-6">Belum ada history peramalan.</p>
            </div>
        @endif

        <div class="accordion" id="historyAccordion">

            @foreach ($batches as $index => $h)

                @php
                    $detail = $detailPerBatch[$h->batch_id] ?? collect([]);
                    $bulanDetail = $detail->pluck('bulan')->unique()->sort()->values();
                @endphp

                <div class="accordion-item border-0 shadow-sm mb-2 rounded-3">

    <!-- HEADER -->
    <h2 class="accordion-header" id="heading{{ $index }}">
        <button class="accordion-button collapsed bg-white py-2 px-3 border-0"
            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">

            <div class="w-100">

                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-semibold">
                        <i class="fas fa-folder-open text-primary me-2"></i>
                        Batch #{{ $h->batch_id }}
                    </span>

                    <span class="badge bg-primary rounded-pill px-2 py-1">
                        {{ $detail->count() }} data
                    </span>
                </div>

                <div class="text-muted d-flex align-items-center small gap-3">

                    <span>
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($h->generated_at)->format('d M Y H:i') }}
                    </span>

                    <span>
                        <i class="fas fa-user-circle me-1"></i>
                        User: <b>{{ $h->user_name }}</b>
                    </span>

                </div>

            </div>

        </button>
    </h2>

    <!-- BODY -->
    <div id="collapse{{ $index }}" class="accordion-collapse collapse"
        aria-labelledby="heading{{ $index }}" data-bs-parent="#historyAccordion">

        <div class="accordion-body bg-white px-3 pb-3">

            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle text-center small mb-0">
                    <thead class="table-dark text-white">
                        <tr>
                            <th class="text-start px-3">Nama Produk</th>
                            @foreach ($bulanDetail as $bulan)
                                <th>{{ $bulan }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detail->groupBy('idProduk') as $produkId => $rows)
                            <tr>
                                <td class="text-start fw-semibold px-3">
                                    <i class="fas fa-tag text-secondary me-1"></i>
                                    {{ $rows->first()->nama_produk }}
                                </td>

                                @foreach ($bulanDetail as $bulan)
                                    @php
                                        $found = $rows->firstWhere('bulan', $bulan);
                                    @endphp

                                    <td class="fw-bold {{ $found ? 'text-primary' : 'text-muted' }}">
                                        {{ $found ? number_format($found->prediksi_qty) : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>


            @endforeach

        </div>

    </div>
</div>


</div>
@endsection
