<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeramalanController extends Controller
{
        // ========================
        // 1) HALAMAN UTAMA (GET)
        // ========================
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $bulanPeramalan = $request->get('bulan_peramalan', 1);

        // Hitung peramalan preview
        $hasilPeramalan = $this->hitungPeramalan($bulanPeramalan);

        // ================================
        // AMBIL HISTORY + USER NAME
        // ================================
        $batches = DB::table('peramalan')
            ->join('users', 'users.id', '=', 'peramalan.generated_by')
            ->select(
                'peramalan.batch_id',
                DB::raw('MIN(peramalan.generated_at) as generated_at'),
                'users.name as user_name',
                DB::raw('COUNT(*) as total_rows')
            )
            ->groupBy('peramalan.batch_id', 'users.name')
            ->orderByDesc('generated_at')
            ->paginate(6)
            ->withQueryString();

        // ================================
        // DETAIL DARI SEMUA BATCH SEKALIGUS
        // ================================
        $detailPerBatch = DB::table('peramalan')
            ->join('produks', 'produks.id', '=', 'peramalan.idProduk')
            ->select(
                'peramalan.batch_id',
                'peramalan.idProduk',
                'produks.nama as nama_produk',
                'peramalan.bulan',
                'peramalan.prediksi_qty'
            )
            ->orderBy('produks.nama')
            ->get()
            ->groupBy('batch_id');

        return view('pages.peramalan.index', compact(
            'hasilPeramalan',
            'bulanPeramalan',
            'batches',
            'detailPerBatch'
        ));
    }

    // ========================
    // 2) PROSES SIMPAN (POST)
    // ========================
    public function store(Request $request)
    {
        $bulanPeramalan = $request->input('bulan_peramalan', 1);

        // Hitung peramalan lagi untuk disimpan
        $hasilPeramalan = $this->hitungPeramalan($bulanPeramalan);

        // Simpan hasilnya
        $this->simpanPeramalan($hasilPeramalan, $bulanPeramalan);

        return redirect()->route('peramalan.index')
                         ->with('success', 'Peramalan berhasil disimpan!');
    }

    // ========================
    // 3) FUNGSI HITUNG PERAMALAN
    // ========================
    private function hitungPeramalan($bulanPeramalan)
    {
        if ($bulanPeramalan < 1) $bulanPeramalan = 1;
        if ($bulanPeramalan > 24) $bulanPeramalan = 24;

        $topProducts = DB::table('sale_details as sd')
            ->join('produks as p', 'sd.idProduk', '=', 'p.id')
            ->select('sd.idProduk', 'p.nama as nama_produk', DB::raw('SUM(sd.quantity) as total_qty'))
            ->groupBy('sd.idProduk', 'p.nama')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();

        $hasilPeramalan = [];
        $today = now();
        $lastFullMonth = $today->copy()->startOfMonth()->subMonth();

        foreach ($topProducts as $produk) {
            $data = DB::table('sale_details as sd')
                ->selectRaw('DATE_FORMAT(sd.tanggal_transaksi, "%Y-%m") as bulan, SUM(sd.quantity) as total_qty')
                ->where('sd.idProduk', $produk->idProduk)
                ->where('sd.tanggal_transaksi', '<=', $lastFullMonth->endOfMonth())
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->limit(9)
                ->get();

            $n = count($data);
            if ($n < 2) continue;

            // Hitung X dan Y
            $X = [];
            $Y = [];

            if ($n % 2 === 0) {
                $start = -($n - 1);
                for ($i = 0; $i < $n; $i++) $X[] = $start + ($i * 2);
            } else {
                $start = -floor($n / 2);
                for ($i = 0; $i < $n; $i++) $X[] = $start + $i;
            }

            foreach ($data as $row) $Y[] = $row->total_qty;

            $sumY = array_sum($Y);
            $sumXY = array_sum(array_map(fn($x, $y) => $x * $y, $X, $Y));
            $sumX2 = array_sum(array_map(fn($x) => $x ** 2, $X));

            if ($sumX2 == 0) continue;

            $a = $sumY / $n;
            $b = $sumXY / $sumX2;

            // Forecast
            $forecast = [];
            $lastDataMonth = Carbon::createFromFormat('Y-m', $data->last()->bulan);
            $lastX = max($X);

            for ($i = 1; $i <= $bulanPeramalan; $i++) {
                $forecastX = $n % 2 === 0 ? $lastX + ($i * 2) : $lastX + $i;

                $forecast[] = [
                    'bulan' => $lastDataMonth->copy()->addMonths($i)->format('Y-m'),
                    'prediksi_qty' => max(0, round($a + $b * $forecastX))
                ];
            }

            $hasilPeramalan[] = [
                'idProduk' => $produk->idProduk,
                'nama_produk' => $produk->nama_produk,
                'data_bulan' => $data,
                'forecast' => $forecast
            ];
        }

        return $hasilPeramalan;
    }

    // ========================
    // 4) FUNGSI SIMPAN KE DB
    // ========================
    private function simpanPeramalan($hasilPeramalan, $bulanPeramalan)
    {
        if (empty($hasilPeramalan)) return;

        $lastBatch = DB::table('peramalan')->max('batch_id');
        $batchId = $lastBatch ? $lastBatch + 1 : 1;

        $now = now();
        $userId = Auth::id();

        $toInsert = [];

        foreach ($hasilPeramalan as $h) {
            foreach ($h['forecast'] as $f) {
                $toInsert[] = [
                    'batch_id' => $batchId,
                    'idProduk' => $h['idProduk'],
                    'bulan' => $f['bulan'],
                    'prediksi_qty' => $f['prediksi_qty'],
                    'bulan_peramalan' => $bulanPeramalan,
                    'generated_at' => $now,
                    'generated_by' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
        }

        DB::table('peramalan')->insert($toInsert);
    }
}
