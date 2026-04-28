<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
   public function index(Request $request)
    {
        $query = DB::table('produks')
            ->leftJoin('users', 'produks.user_id', '=', 'users.id')
            ->select('produks.*', 'users.name as updated_by'); // ✅ ambil nama user

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('produks.nama', 'like', "%{$search}%");
        }

        $produk = $query->paginate(8)->appends(['search' => $request->search]);

        return view('pages.produk.index', [
            "produks" => $produk,
            "search" => $request->search
        ]);
    }

    public function create() {

    if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
        $namas = DB::table('produks')
            ->whereNotNull('nama')
            ->distinct()
            ->orderBy('nama')
            ->pluck('nama')
            ->toArray();

        $satuans = DB::table('produks')
            ->whereNotNull('satuan')
            ->distinct()
            ->orderBy('satuan')
            ->pluck('satuan')
            ->toArray();

        return view('pages.produk.create', [
            'namas' => $namas,
            'satuans' => $satuans,
        ]);
    }

    public function store(Request $request) {

    if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
        $validated = $request->validate([
            'nama'=> 'required|min:3|unique:produks,nama',
            'satuan'=> 'required',
            'harga'=> 'required|min:4',
            'stok'=> 'required',
        ], [
            'nama.unique' => 'Produk dengan nama ini sudah ada.',
        ]);

        Produk::create([
            'nama'=> $request->input('nama'),
            'satuan'=> $request->input('satuan'),
            'harga'=> $request->input('harga'),
            'stok'=> $request->input('stok'),
            'user_id' => Auth::id(),
        ]);

        return redirect('/produk')->with('success','Berhasil menambahkan produk');
    }

    public function destroy($id)
    {
    if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function edit($id)
    {
    if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
        $produk = Produk::findOrFail($id);
        $namas = Produk::distinct()->pluck('nama');
        $satuans = Produk::distinct()->pluck('satuan');

        return view('pages.produk.edit', compact('produk', 'namas', 'satuans'));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $request->validate([
            'nama' => 'required',
            'satuan' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'nama' => $request->nama,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'user_id' => Auth::id(), // siapa yang update
            'updated_at' => now(),   // waktu update
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function updateStok(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['owner', 'manager'])) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $aksi = $request->input('aksi');
        $jumlah = (int) $request->input('jumlah');
        $produk = Produk::findOrFail($id);

        if ($aksi === 'tambah') {
            $produk->stok += $jumlah;
        } elseif ($aksi === 'kurang') {
            $produk->stok = max(0, $produk->stok - $jumlah);
        }

        $produk->user_id = Auth::id();
        $produk->updated_at = now();
        $produk->save();

        return redirect()->route('produk.index', ['page' => $request->page])
                        ->with('success', 'Stok berhasil diperbarui');
    }

}
