<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportSales(): StreamedResponse
    {
        $sales = DB::table('sales')
            ->select('id', 'tanggal', 'total')
            ->get();

        // Buat response streamed menggunakan callback
        return response()->streamDownload(function () use ($sales) {
            $writer = SimpleExcelWriter::streamDownload('php://output')
                ->addRows($sales->toArray());
            $writer->close(); // Penting untuk menutup stream
        }, 'sales.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}

