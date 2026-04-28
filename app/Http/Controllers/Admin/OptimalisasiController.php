<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OptimalisasiController extends Controller
{
    public function index()
    {
    if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
        $leadTime = 1;
        $topProducts = DB::table('sale_details as sd')
            ->join('produks as p', 'sd.idProduk', '=', 'p.id')
            ->select('sd.idProduk', 'p.nama as nama_produk', DB::raw('SUM(sd.quantity) as total_qty'))
            ->groupBy('sd.idProduk', 'p.nama')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        $hasilMinMax = [];
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

            $T = $data->avg('total_qty'); // rata-rata per bulan
            $max = $data->max('total_qty'); // penjualan maksimum

            $SS = ($max - $T) * $leadTime;
            $maxStock = (2 * ($T * $leadTime)) + $SS;
            $minStock = ($T * $leadTime) + $SS;

            $hasilMinMax[] = [
                'idProduk' => $produk->idProduk,
                'nama_produk' => $produk->nama_produk,
                'rata_rata' => round($T, 2),
                'maksimum' => $max,
                'safety_stock' => round($SS, 2),
                'max_stock' => round($maxStock, 2),
                'min_stock' => round($minStock, 2),
            ];
        }

        return view('pages.optimalisasi.index', compact('hasilMinMax'));
    }
}
