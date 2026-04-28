<nav class="main-header navbar navbar-expand bg-dark navbar-dark" style="height: 72.9px">
    <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link">Home</a>
        </li>
        {{-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> --}}
    </ul>

    <ul class="navbar-nav ml-auto align-items-center">

        <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            @if(!empty($notifProduk) && $notifProduk->count() > 0)
            <span class="badge badge-warning navbar-badge">{{ $notifProduk->count() }}</span>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">{{ $notifProduk->count() }} Produk Bermasalah</span>
            <div class="dropdown-divider"></div>

            @forelse($notifProduk as $n)
                <a href="{{ url('/produk?search=' . urlencode($n['nama_produk'])) }}" class="dropdown-item">
                    <span class="badge badge-{{ $n['status'] }} mr-2">{{ $n['keterangan'] }}</span>
                    {{ $n['nama_produk'] }}
                    <span class="float-right text-muted text-sm">Stok: {{ $n['stok'] }}</span>
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <span class="dropdown-item text-center text-muted">Tidak ada notifikasi</span>
            @endforelse

            <a href="{{ route('optimalisasi.index') }}" class="dropdown-item dropdown-footer">Lihat Detail</a>
        </div>
        </li>


        @auth
            <li class="nav-item mr-2">
                <span class="nav-link">Hai, {{ Auth::user()->name }}</span>
            </li>
            <li class="nav-item">
                <form action="/logout" method="POST">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </li>
        @endauth
    </ul>
</nav>
