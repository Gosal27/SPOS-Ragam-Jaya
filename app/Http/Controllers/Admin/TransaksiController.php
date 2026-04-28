<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        $cart = session()->get('cart', []);
        return view('pages.transaksi.index', compact('produks', 'cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'qty' => 'required|integer|min:1'
        ]);

        $produk = Produk::where('nama', $request->nama)->first();

        if (!$produk) {
            return back()->with('error', 'Produk tidak ditemukan!');
        }

        $cart = session()->get('cart', []);

        // Tambah atau update data di keranjang
        $cart[$produk->id] = [
            "nama" => $produk->nama,
            "harga" => $produk->harga,
            "quantity" => isset($cart[$produk->id]) ? $cart[$produk->id]['quantity'] + $request->qty : $request->qty
        ];

        session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function resetCart()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang telah dikosongkan!');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong tidak dapat melakukan transaksi');
        }

        $subtotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['quantity']);

        $sale = Sale::create([
            'user_id' => Auth::id(),
            'subtotal' => $subtotal,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        foreach ($cart as $produkId => $item) {
            SaleDetail::create([
                'idSales' => $sale->idSales,
                'idProduk' => $produkId,
                'quantity' => $item['quantity'],
                'subtotal' => $item['harga'] * $item['quantity'],
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);
        $produk = Produk::find($produkId);
        if ($produk) {
            // validasi stok tidak boleh minus
            if ($produk->stok < $item['quantity']) {
                return back()->with('error', "Stok {$produk->nama} tidak mencukupi!");
            }
            $produk->stok -= $item['quantity'];
            $produk->save();
        }
        }
        session()->forget('cart');
        return redirect()
            ->route('transaksi.index')
            ->with('sale_id', $sale->idSales);
    }

public function nota($id)
{
    $sale = Sale::with('user')->findOrFail($id);

    // ambil detail; pastikan relasi nama model SaleDetail dan kolom sesuai
    $details = SaleDetail::with('produk')->where('idSales', $id)->get();

    return view('pages.transaksi.nota', compact('sale', 'details'));
}

}
