<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stationery;
use Illuminate\Http\Request;
use App\Models\BarangHistory;
use App\Http\Controllers\Controller;

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

        $type = $request->query('type', '1'); // Default ke '1' (alat tulis)
        $query = $request->query('q'); // Ambil parameter pencarian

        // Query dasar berdasarkan jenis barang
        $queryBuilder = Stationery::where('jenis_barang', $type);

        // Jika ada pencarian, filter berdasarkan nama atau kode barang
        if ($query) {
            $queryBuilder->where(function ($q) use ($query) {
                $q->where('nama_barang', 'like', "%{$query}%")
                    ->orWhere('kode_barang', 'like', "%{$query}%");
            });
        }

        // Ambil data dengan pagination
        $data = $queryBuilder->paginate(10);

        // Tentukan view yang sesuai berdasarkan jenis barang
        if ($type === '1') {
            return view('admin.stationeries.index', compact('data', 'type'));
        } elseif ($type === '2') {
            return view('admin.supplies.index', compact('data', 'type'));
        }

        abort(404); // Jika jenis barang tidak valid, tampilkan error 404
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
            'stok' => 'required|integer',
            'satuan' => 'required|string|max:255',
        ]);

        $validated['harga_barang'] = preg_replace('/\D/', '', $request->harga_barang);
        $validated['masuk'] = $validated['stok'];
        $validated['nama_barang'] = strtolower($validated['nama_barang']);

        // Simpan data barang ke tabel stationery
        $stationery = Stationery::create($validated);

        // Simpan ke history barang masuk
        BarangHistory::create([
            'stationery_id' => $stationery->id,
            'jenis' => 'masuk', // Barang masuk
            'jumlah' => $request->stok,
            'tanggal' => now(), // Ambil tanggal sekarang
        ]);

        return redirect()->route('stationeries.index', ['type' => $request->jenis_barang])
            ->with('success', 'Data berhasil ditambahkan dan history tercatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show($stationery_id)
    {
        // return view('admin.stationery.show', compact('stationery'));
        $detailedItem = BarangHistory::with('stationery')
            ->where('stationery_id', $stationery_id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.stationeries.history', compact('detailedItem'));
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
            'tambah' => 'nullable|integer', // pastikan ini divalidasi juga
        ]);

        // Format harga_barang agar hanya angka
        $validated['harga_barang'] = preg_replace('/\D/', '', $request->harga_barang);

        if ($request->filled('tambah') && $request->tambah > 0) {
            // Jika menambah stok
            $validated['stok'] = $stationery->stok + $request->tambah;
            $validated['masuk'] = $stationery->masuk + $request->tambah;

            // Simpan ke history
            BarangHistory::create([
                'stationery_id' => $stationery->id,
                'jenis' => 'masuk',
                'jumlah' => $request->tambah,
                'tanggal' => now(),
            ]);
        } else {
            // Tidak menambah stok, pakai stok lama
            $validated['stok'] = $stationery->stok;
            $validated['masuk'] = $stationery->masuk;
        }

        $stationery->update($validated);

        $type = $stationery->jenis_barang;

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
