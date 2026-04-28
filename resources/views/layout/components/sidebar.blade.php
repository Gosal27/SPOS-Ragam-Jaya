@php
    $menus = [
        (object) [
            "title" => "Dashboard",
            "path" => "",
            "icon" => "fas fa-tachometer-alt",
            "roles" => ["owner"]

        ],
        (object) [
            "title" => "Produk",
            "path" => "produk",
            "icon" => "fas fa-box",
            "roles" => ["karyawan", "manager", "owner"]

        ],
        (object) [
            "title" => "Peramalan Penjualan",
            "path" => "peramalan",
            "icon" => "fas fa-chart-line",
            "roles" => ["manager", "owner"]
        ],
        (object) [
            "title" => "Optimalisasi Stok",
            "path" => "optimalisasi",
            "icon" => "fas fa-sliders-h",
            "roles" => ["manager", "owner"]
        ],
        (object) [
            "title" => "User Manajamen",
            "path" => "users",
            "icon" => "fas fa-users-cog",
            "roles" => ["owner"]
        ],
        (object) [
            "title" => "Laporan Penjualan",
            "path" => "history",
            "icon" => "fas fa-history",
            "roles" => ["manager", "owner"]
        ],
        (object) [
            "title" => "Transaksi",
            "path" => "transaksi",
            "icon" => "fas fa-exchange-alt",
            "roles" => ["karyawan", "manager", "owner"]
        ],
        (object) [
            // "title" => "Backup & Restore",
            "title" => "Backup",
            "path" => "backup",
            "icon" => "fas fa-database",
            "roles" => ["owner"]
        ]
    ]
@endphp

@php
    $userRole = auth()->check() ? auth()->user()->role : null;
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link d-flex align-items-center justify-content-center" style="padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
    <img src="{{asset('templates/dist/img/LogoRagam.png')}}"
         alt="Ragam Jaya Logo"
         style="max-height: 40px; width: 100%; object-fit: contain; opacity: .9;">
  </a>

  <!-- Sidebar -->
  <div class="sidebar px-3">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-3 mb-3">
      <div class="input-group w-100" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Menu item -->
            @foreach ($menus as $menu)
                @if(in_array($userRole, $menu->roles))
                    <li class="nav-item">
                        <a href="/{{$menu->path}}" class="nav-link {{ request()->path() === $menu->path ? 'active' : '' }}">
                            <i class="nav-icon {{ $menu->icon }}"></i>
                            <p class="mb-0">{{ $menu->title }}</p>
                        </a>
                    </li>
                @endif
            @endforeach
      </ul>
    </nav>
  </div>
</aside>
