<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SaleDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error-unauthorized', 'Silahkan Login Terlbih Dahulu');
        }

        if (!in_array(Auth::user()->role, ['owner'])) {
            return redirect('/produk');
        }

        $start = $request->from;
        $end = $request->to;

        // Produk terlaris
        $produkTerlaris = SaleDetail::join('produks', 'sale_details.idProduk', '=', 'produks.id')
            ->select('produks.nama', DB::raw('SUM(sale_details.quantity) as total_terjual'))
            ->when($start && $end, fn($q) => $q->whereBetween('sale_details.tanggal_transaksi', [$start, $end]))
            ->groupBy('sale_details.idProduk', 'produks.nama')
            ->having('total_terjual', '>', 0)
            ->orderByDesc('total_terjual')
            ->first();

        // Produk paling sedikit terjual
        $produkPalingSedikit = SaleDetail::join('produks', 'sale_details.idProduk', '=', 'produks.id')
            ->select('produks.nama', DB::raw('SUM(sale_details.quantity) as total_terjual'))
            ->when($start && $end, fn($q) => $q->whereBetween('sale_details.tanggal_transaksi', [$start, $end]))
            ->groupBy('sale_details.idProduk', 'produks.nama')
            ->having('total_terjual', '>', 0)
            ->orderBy('total_terjual')
            ->first();

        // Grafik produk terlaris (10 besar)
        $produkTerlarisChart = SaleDetail::join('produks', 'sale_details.idProduk', '=', 'produks.id')
            ->select('produks.nama', DB::raw('SUM(sale_details.quantity) as total'))
            ->when($start && $end, fn($q) => $q->whereBetween('sale_details.tanggal_transaksi', [$start, $end]))
            ->groupBy('produks.nama')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->pluck('total', 'nama');

        // 📊 Grafik Penjualan Bulanan (perubahan utama)
        $penjualanBulanan = SaleDetail::select(
                DB::raw("DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan"),
                DB::raw('SUM(subtotal) as total')
            )
            ->when($start && $end, fn($q) => $q->whereBetween('tanggal_transaksi', [$start, $end]))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->pluck('total', 'bulan');

        // Total Penjualan
        $totalPenjualan = SaleDetail::when($start && $end, fn($q) => $q->whereBetween('tanggal_transaksi', [$start, $end]))
            ->sum('subtotal');

        // Produk Terjual
        $produkTerjual = SaleDetail::when($start && $end, fn($q) => $q->whereBetween('tanggal_transaksi', [$start, $end]))
            ->sum('quantity');

        // Jumlah Transaksi
        $jumlahTransaksi = SaleDetail::when($start && $end, fn($q) => $q->whereBetween('tanggal_transaksi', [$start, $end]))
            ->distinct('idSales')
            ->count('idSales');

        // Rata-rata Penjualan Bulanan
        $rataRataPenjualan = $penjualanBulanan->count() > 0
            ? round($totalPenjualan / $penjualanBulanan->count())
            : 0;

        return view('pages.dashboard.index', [
            'produkTerlaris' => $produkTerlaris,
            'produkPalingSedikit' => $produkPalingSedikit,
            'produkTerlarisChart' => $produkTerlarisChart,
            'penjualanBulanan' => $penjualanBulanan,
            'totalPenjualan' => $totalPenjualan,
            'produkTerjual' => $produkTerjual,
            'jumlahTransaksi' => $jumlahTransaksi,
            'rataRataPenjualan' => $rataRataPenjualan,
            'produkTerlarisNama' => $produkTerlaris->nama ?? '-',
            'produkTerlarisJumlah' => $produkTerlaris->total_terjual ?? 0,
            'produkPalingSedikitNama' => $produkPalingSedikit->nama ?? '-',
            'produkPalingSedikitJumlah' => $produkPalingSedikit->total_terjual ?? 0,
            'from' => $start,
            'to' => $end,
        ]);
    }

        public function exportCsv(Request $request)
        {
            $start = $request->query('from');
            $end = $request->query('to');

            $filename = 'laporan_penjualan_' . now()->format('Ymd_His') . '.csv';

            return new StreamedResponse(function () use ($start, $end) {
                // Buka output stream
                $handle = fopen('php://output', 'w');

                // Tulis header CSV
                fputcsv($handle, ['Tanggal', 'Nama Produk', 'Jumlah Terjual', 'Total Penjualan (Rp)']);

                // Query sesuai struktur yang dipakai di index()
                $query = DB::table('sale_details')
                    ->join('produks', 'sale_details.idProduk', '=', 'produks.id')
                    ->select(
                        DB::raw('DATE(sale_details.tanggal_transaksi) as tanggal'),
                        'produks.nama as produk',
                        DB::raw('SUM(sale_details.quantity) as total_terjual'),
                        DB::raw('SUM(sale_details.subtotal) as total_penjualan')
                    )
                    ->groupBy(DB::raw('DATE(sale_details.tanggal_transaksi)'), 'produks.nama')
                    ->orderBy(DB::raw('DATE(sale_details.tanggal_transaksi)'), 'asc');

                // Jika user mengisi from & to, filter
                if (!empty($start) && !empty($end)) {
                    // jika tanggal disimpan termasuk waktu, pastikan format/penyesuaian bila perlu
                    $query->whereBetween('sale_details.tanggal_transaksi', [$start, $end]);
                }

                $rows = $query->get();

                // Tulis data baris demi baris
                foreach ($rows as $row) {
                    // Pastikan nilai numeric tidak mengandung koma yang merusak CSV,
                    // tapi fputcsv akan handle quoting.
                    fputcsv($handle, [
                        $row->tanggal,
                        $row->produk,
                        $row->total_terjual,
                        $row->total_penjualan
                    ]);
                }

                fclose($handle);
            }, 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-store, no-cache, must-revalidate',
                'Pragma' => 'no-cache',
            ]);
        }
}
