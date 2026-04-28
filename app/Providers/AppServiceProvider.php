<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StockService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function boot(){
    view()->composer('layout.components.navbar', function ($view) {
        $service = app(StockService::class);
        $produk = $service->hitungMinMax();

        $notif = collect($produk)->map(function($p) {
            if ($p['stok'] < $p['min_stock']) {
                $p['status'] = 'danger'; // merah
                $p['keterangan'] = 'Di bawah minimal';
            } elseif ($p['stok'] < $p['safety_stock']) {
                $p['status'] = 'warning'; // kuning
                $p['keterangan'] = 'Di bawah safety';
            } elseif ($p['stok'] > $p['max_stock']) {
                $p['status'] = 'primary'; // biru
                $p['keterangan'] = 'Kelebihan stok';
            } else {
                $p['status'] = 'secondary'; // abu (aman)
                $p['keterangan'] = 'Aman';
            }
            return $p;
        })->filter(function($p) {
            return in_array($p['status'], ['danger','warning','primary']);
        });

        $view->with('notifProduk', $notif);
    });

            Blade::if('ownerOrManager', function () {
            return in_array(Auth::user()->role ?? '', ['owner', 'manager']);
        });
}
}
