@extends('layout.main')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">Backup & Restore Database</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5>Backup Database</h5>
            <form action="{{ route('backup.process') }}" method="POST">
                @csrf
                <button class="btn btn-primary">
                    <i class="fas fa-download"></i> Backup Sekarang
                </button>
            </form>
        </div>
    </div>

<div class="card">
    <div class="card-body">

        <h5 class="mb-3">Restore Database</h5>

        {{-- NOTIFIKASI --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        {{-- FORM RESTORE --}}
        <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
            onsubmit="return confirm('Yakin ingin melakukan restore? Semua data di database akan digantikan.');">
            @csrf

            <label class="mb-2">Upload File SQL:</label>
            <input type="file" name="backup_file"
                   class="form-control @error('backup_file') is-invalid @enderror"
                   accept=".sql" required>

            @error('backup_file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-success mt-3">
                <i class="fas fa-upload"></i> Restore Database
            </button>
        </form>

    </div>
</div>

</div>
@endsection
