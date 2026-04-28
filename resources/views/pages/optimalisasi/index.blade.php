@extends('layout.main')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">
        <i class="fas fa-boxes me-2"></i> Perhitungan Stok
    </h2>
    <p class="text-muted">
        Menampilkan stok saat ini, batas minimum, maksimum, dan rekomendasi pesan untuk 20 produk teratas.
    </p>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i> Tabel Optimalisasi Stok
            </h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-success">
                    <tr>
                        <th>Produk</th>
                        <th>Rata-rata</th>
                        <th>Maksimum</th>
                        <th>Safety Stock</th>
                        <th>Max Stock</th>
                        <th>Min Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilMinMax as $row)
                        <tr>
                            <td class="text-start">{{ $row['nama_produk'] }}</td>
                            <td>{{ number_format($row['rata_rata'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['maksimum'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['safety_stock'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['max_stock'], 0, ',', '.') }}</td>
                            <td>{{ number_format($row['min_stock'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">Tidak ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
