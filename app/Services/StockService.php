<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockService
{
    public function hitungMinMax($leadTime = 1, $limitProduk = 20)
    {
        $topProducts = DB::table('sale_details as sd')
            ->join('produks as p', 'sd.idProduk', '=', 'p.id')
            ->select('sd.idProduk', 'p.nama as nama_produk', DB::raw('SUM(sd.quantity) as total_qty'))
            ->groupBy('sd.idProduk', 'p.nama')
            ->orderByDesc('total_qty')
            ->limit($limitProduk)
            ->get();

        $hasil = [];
        $today = now();
        $lastFullMonth = $today->copy()->startOfMonth()->subMonth();

        foreach ($topProducts as $produk) {
            $data = DB::table('sale_details as sd')
                ->selectRaw('DATE_FORMAT(sd.tanggal_transaksi, "%Y-%m") as bulan, SUM(sd.quantity) as total_qty')
                ->where('sd.idProduk', $produk->idProduk)
                ->where('sd.tanggal_transaksi', '<=', $lastFullMonth->endOfMonth())
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->limit(12)
                ->get();

            if (count($data) < 1) continue;

            $T = $data->avg('total_qty');
            $max = $data->max('total_qty');

            $SS = ($max - $T) * $leadTime;
            $maxStock = (2 * ($T * $leadTime)) + $SS;
            $minStock = ($T * $leadTime) + $SS;

            // ambil stok saat ini
            $stokSekarang = DB::table('produks')->where('id', $produk->idProduk)->value('stok');

            $hasil[] = [
                'idProduk' => $produk->idProduk,
                'nama_produk' => $produk->nama_produk,
                'stok' => $stokSekarang,
                'safety_stock' => round($SS, 2),
                'max_stock' => round($maxStock, 2),
                'min_stock' => round($minStock, 2),
            ];
        }

        return $hasil;
    }
}
