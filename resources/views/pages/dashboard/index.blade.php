@extends('layout.main')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard Penjualan</h2>

    <!-- Filter dan Export -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET" action="{{ route('dashboard') }}">
                <div class="col-md-4">
                    <label for="from" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <!-- Tombol untuk buka modal -->
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exportModal">
                        <i class="fas fa-file-csv"></i> Cetak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Export CSV -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Export Data Penjualan</h5>
                    <!-- Tombol close Bootstrap 4 -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('dashboard.export') }}" method="GET">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fromExport">Dari Tanggal</label>
                            <input type="date" name="from" id="fromExport" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="toExport">Sampai Tanggal</label>
                            <input type="date" name="to" id="toExport" class="form-control">
                        </div>
                        <p class="text-muted mb-0">Kosongkan tanggal jika ingin mengekspor semua data.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Export</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Penjualan</h5>
                    <p class="card-text fs-4">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Produk Terjual</h5>
                    <p class="card-text fs-4">{{ $produkTerjual }} Produk</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Transaksi</h5>
                    <p class="card-text fs-4">{{ $jumlahTransaksi }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h5 class="card-title">Produk Terlaris</h5>
                    <p class="card-text fs-5">{{ $produkTerlarisNama }} ({{ number_format($produkTerlarisJumlah) }} Terjual)</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">Produk Paling Jarang Dibeli</h5>
                    <p class="card-text fs-5">{{ $produkPalingSedikitNama }} ({{ number_format($produkPalingSedikitJumlah) }} Terjual)</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card text-white bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Rata-rata Penjualan Bulanan</h5>
                    <p class="card-text fs-5">Rp {{ number_format($rataRataPenjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-5">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-dark">Grafik Penjualan per Bulan</h6>
                </div>
                <div class="card-body">
                    <canvas id="penjualanBulananChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h6 class="m-0 font-weight-bold text-dark">10 Produk Terlaris</h6>
                </div>
                <div class="card-body">
                    <canvas id="produkTerlarisChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const penjualanBulanan = @json($penjualanBulanan);
    const produkTerlaris = @json($produkTerlarisChart);

    const labelBulan = Object.keys(penjualanBulanan).map(bulan => {
        const [tahun, bln] = bulan.split('-');
        const namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        return `${namaBulan[parseInt(bln) - 1]} ${tahun}`;
    });

    new Chart(document.getElementById('penjualanBulananChart'), {
        type: 'bar',
        data: {
            labels: labelBulan,
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: Object.values(penjualanBulanan),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgb(75, 192, 192)',
                borderWidth: 1,
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById('produkTerlarisChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(produkTerlaris),
            datasets: [{
                label: 'Jumlah Terjual',
                data: Object.values(produkTerlaris),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });
</script>
@endsection
