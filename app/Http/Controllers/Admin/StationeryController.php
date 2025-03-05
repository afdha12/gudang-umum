<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stationery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StationeryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        // $data = Stationery::paginate(20);
        // return view('admin.stationery.index', compact('data'));
        // Ambil query parameter 'type' dari URL
        $type = $request->query('type', 'alat-tulis'); // Default ke 'alat-tulis'

        // Ambil data yang sesuai (bisa disesuaikan dengan tipe yang dimaksud)
        if ($type === '1') {
            $data = Stationery::where('jenis_barang', '1')->paginate(20);
            return view('admin.stationeries.index', compact('data', 'type'));
        } elseif ($type === '2') {
            $data = Stationery::where('jenis_barang', '2')->paginate(20);
            return view('admin.supplies.index', compact('data', 'type'));
        }

        abort(404); // Jika tidak sesuai, tampilkan error 404
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Ambil kode barang yang akan digunakan
        $lastItem = Stationery::latest('id')->first();
        $kode_barang = Stationery::generateNextKode($lastItem ? $lastItem->kode_barang : null);
        // return view('admin.stationery.create', compact('kode_barang'));

        // Ambil parameter 'type' dari URL (default ke 'stationeries' jika tidak ada)
        $type = $request->query('type', '1');

        // Pastikan hanya menerima 'stationeries' atau 'supplies'
        if (!in_array($type, ['1', '2'])) {
            abort(404);
        }

        // Pilih view berdasarkan type
        $view = $type === '1' ? 'admin.stationeries.create' : 'admin.supplies.create';

        return view($view, compact('type', 'kode_barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255',
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|string|max:255',
            'jenis_barang' => 'required|string|max:255',
            'stok' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            // 'masuk' => 'required|integer',
            // 'keluar' => 'required|integer',
            // 'stok' => 'required|integer',
        ]);

        $validated['harga_barang'] = preg_replace('/\D/', '', $request->harga_barang);
        Stationery::create($validated);

        // return redirect()->route('stationeries.index')->with('success', 'Stationery berhasil ditambahkan.');
        return redirect()->route('stationeries.index', ['type' => $request->jenis_barang])
            ->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stationery $stationery)
    {
        // return view('admin.stationery.show', compact('stationery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stationery $stationery)
    {
        // return view('admin.stationeries.edit', compact('stationery'));

        // Tentukan type berdasarkan nilai jenis_barang
        $type = $stationery->jenis_barang == 1 ? 'stationeries' : 'supplies';

        return view("admin.$type.edit", compact('stationery', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stationery $stationery)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            'stok' => 'required|integer',
            'masuk' => 'nullable|integer',
        ]);

        // Format harga_barang agar hanya angka (menghapus karakter non-digit)
        $validated['harga_barang'] = preg_replace('/\D/', '', $request->harga_barang);

        // Update stok dan jumlah masuk berdasarkan input tambah
        $validated['stok'] = $stationery->stok + $request->tambah;
        $validated['masuk'] = $stationery->masuk + $request->tambah;

        // Simpan perubahan
        $stationery->update($validated);

        // Tentukan tipe berdasarkan jenis_barang (1 = stationeries, 2 = supplies)
        $type = $stationery->jenis_barang;

        // Redirect ke halaman index sesuai jenis barang
        return redirect()->route('stationeries.index', ['type' => $type])
            ->with('success', 'Stationery berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stationery $stationery)
    {
        $type = $stationery->jenis_barang; // Ambil kategori sebelum dihapus

        $stationery->delete();

        // Redirect ke index dengan type yang sesuai
        return redirect()->route('stationeries.index', ['type' => $type])
            ->with('success', 'Data berhasil dihapus!');
        // return redirect()->route('stationeries.index')->with('success', 'Stationery berhasil dihapus.');
    }
}
