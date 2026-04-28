@extends('layout.main')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Produk</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Produk</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
@if (session('success'))
<script>
    Swal.fire({
        title: "Berhasil",
        text: "{{ session('success') }}",
        icon: "success",
        confirmButtonColor: '#3085d6'
    });
</script>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-end align-items-center">
        <!-- Search bar -->
        <form method="GET" action="{{ route('produk.index') }}"
            class="d-flex me-2" style="max-width: 300px;">
            <input type="text" name="search" class="form-control form-control-sm me-2"
                placeholder="Cari produk..." value="{{ old('search', $search ?? '') }}">
            <button class="btn btn-sm btn-outline-secondary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>

        @ownerOrManager
        <!-- Tombol tambah barang -->
        <a href="/produk/create" class="btn btn-sm btn-primary ml-3">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
        @endownerOrManager
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th style="width: 210px;">Stok</th>
                        @ownerOrManager
                        <th style="width: 130px;">Aksi</th>
                        @endownerOrManager
                        <th>Updated By</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($produks as $produk)
                <tr>
                    <td class="text-center">
                        {{ ($produks->currentPage() - 1) * $produks->perPage() + $loop->index + 1 }}
                    </td>
                    <td>{{ $produk->nama }}</td>
                    <td class="text-center">{{ $produk->satuan }}</td>
                    <td class="text-right">
                        Rp {{ number_format($produk->harga, 2, ',', '.') }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="me-2">{{ $produk->stok }}</span>
                            @ownerOrManager
                            <form action="{{ route('produk.updateStok', $produk->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf
                                <input type="hidden" name="page" value="{{ request()->get('page') }}">
                                <input type="number" name="jumlah" value="1" min="1" class="form-control form-control-sm me-1" style="width: 55px;">
                                <button type="submit" name="aksi" value="tambah" class="btn btn-success btn-sm me-1 px-2" title="Tambah">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="submit" name="aksi" value="kurang" class="btn btn-danger btn-sm px-2" title="Kurangi">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </form>
                            @endownerOrManager
                        </div>
                    </td>

                    @ownerOrManager
                    <td class="text-center">
                        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm me-1" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('produk.destroy', $produk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                    @endownerOrManager

                    <td class="text-center">{{ $produk->updated_by ?? '-' }}</td>
                    <td class="text-center">
                        {{ $produk->updated_at ? \Carbon\Carbon::parse($produk->updated_at)->format('d M Y H:i') : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->check() && in_array(auth()->user()->role, ['owner','manager']) ? 8 : 6 }}"
                        class="text-center text-muted py-4">
                        Belum ada data produk.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer text-center">
        {{ $produks->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
