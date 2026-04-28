@extends('layout.main')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-cart-check"></i> Keranjang Belanja</h3>
        <a href="{{ route('transaksi.reset') }}" class="btn btn-outline-danger">
            <i class="bi bi-trash3"></i> Kosongkan
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-basket"></i> Daftar Produk
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @forelse($cart as $id => $item)
                        @php
                            $subtotal = $item['harga'] * $item['quantity'];
                            $total += $subtotal;
                        @endphp
                        <tr class="text-center">
                            <td class="text-start">{{ $item['nama'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>Rp{{ number_format($item['harga']) }}</td>
                            <td>Rp{{ number_format($subtotal) }}</td>
                            <td>
                                <a href="{{ route('transaksi.hapus', $id) }}" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Keranjang kosong</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-primary text-end fw-bold">
                        <td colspan="3">Total</td>
                        <td colspan="2">Rp{{ number_format($total) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <form action="{{ route('transaksi.checkout') }}" method="POST" class="mt-4 row g-3">
        @csrf
        <div class="d-flex flex-column flex-md-row align-items-start">
            <div class="d-flex-grow-1 mr-3">
                <input type="date" name="tanggal_transaksi"
                    class="form-control @error('tanggal_transaksi') is-invalid @enderror"
                    value="{{ old('tanggal_transaksi') }}">
                @error('tanggal_transaksi')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-bag-check"></i> Checkout Sekarang
            </button>
        </div>
    </form>

    <hr class="my-4">

    <h5 class="mb-3"><i class="bi bi-plus-circle"></i> Tambah Produk</h5>
    <form action="{{ route('transaksi.tambah') }}" method="POST" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label for="nama" class="form-label">Nama Produk</label>
            <input list="produk-list" id="nama" name="nama" class="form-control">
            <datalist id="produk-list">
                @foreach($produks as $produk)
                    <option value="{{ $produk->nama }}">
                @endforeach
            </datalist>
        </div>

        <div class="col-md-2">
            <label for="qty" class="form-label">Jumlah</label>
            <input type="number" id="qty" name="qty" class="form-control" value="1">
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-plus-circle-dotted"></i> Tambahkan
            </button>
        </div>
    </form>
</div>


{{-- ========================================================= --}}
{{-- ===============  MODAL NOTA SETELAH CHECKOUT  ============ --}}
{{-- ========================================================= --}}

<div class="modal fade" id="notaModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Nota Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="nota-body">
        <div class="text-center py-4">Memuat nota...</div>
      </div>

      <div class="modal-footer">
        <a href="" id="btn-print" target="_blank" class="btn btn-primary">Cetak Nota</a>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@if (session('sale_id'))
<script>
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        let id = "{{ session('sale_id') }}";
        let url = "{{ route('transaksi.nota', ':id') }}".replace(':id', id);

        let popup = window.open(url, "NotaTransaksi", "width=800,height=600,scrollbars=yes");

        if (!popup) {
            alert("Browser memblokir pop-up. Aktifkan pop-up untuk situs ini.");
        }
    }, 300);
});
</script>
@endif
@endsection

