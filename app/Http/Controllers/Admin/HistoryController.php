<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;


class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('details.produk');

        // Filter tanggal
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_akhir);
        }

        // Filter subtotal
        if ($request->filled('harga_min')) {
            $query->where('subtotal', '>=', $request->harga_min);
        }

        if ($request->filled('harga_max')) {
            $query->where('subtotal', '<=', $request->harga_max);
        }

        // Sorting
        switch ($request->sort) {
            case 'terbaru':
                $query->orderByDesc('tanggal_transaksi');
                break;
            case 'terlama':
                $query->orderBy('tanggal_transaksi');
                break;
            case 'termurah':
                $query->orderBy('subtotal');
                break;
            case 'termahal':
                $query->orderByDesc('subtotal');
                break;
            default:
                $query->orderByDesc('tanggal_transaksi'); // default sort
        }

        $sales = $query->orderByDesc('tanggal_transaksi')->get();
        $sales = $query->paginate(5)->withQueryString();

        return view('pages.history.index', compact('sales'));
    }


    public function show($id)
    {
        $sale = Sale::with('details.produk')->findOrFail($id);
        return view('pages.history.show', compact('sale'));
    }
}

