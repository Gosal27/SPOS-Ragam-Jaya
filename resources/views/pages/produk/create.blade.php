@extends('layout.main')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Tambah Produk</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/produk">Produk</a></li>
                <li class="breadcrumb-item active">Tambah Produk</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm rounded">
        <form action="/produk/store" method="POST">
            @csrf
            <div class="card-body">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Produk</label>
                    <input list="nama-list" name="nama" id="nama"
                        class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" autofocus>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <datalist id="nama-list">
                        @foreach ($namas as $nama)
                            <option value="{{ $nama }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input list="satuan-list" name="satuan" id="satuan"
                        class="form-control @error('satuan') is-invalid @enderror"
                        value="{{ old('satuan') }}">
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <datalist id="satuan-list">
                        @foreach ($satuans as $satuan)
                            <option value="{{ $satuan }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga Produk</label>
                    <input type="text" inputmode="numeric" name="harga" id="harga"
                        class="form-control @error('harga') is-invalid @enderror"
                        value="{{ old('harga') }}">
                    @error('harga')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="form-label">Jumlah Stok</label>
                    <input type="text" inputmode="numeric" name="stok" id="stok"
                        class="form-control @error('stok') is-invalid @enderror"
                        value="{{ old('stok') }}">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-end">
                <a href="/produk" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const formatRupiah = (el) => {
        el.addEventListener('input', function () {
            let value = this.value.replace(/[^\d]/g, "");
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    };

    formatRupiah(document.getElementById('harga'));
    formatRupiah(document.getElementById('stok'));
</script>
@endpush
