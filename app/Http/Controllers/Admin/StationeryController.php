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
    public function index()
    {
        $data = Stationery::paginate(20);
        return view('admin.stationery.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil kode barang yang akan digunakan
        $lastItem = Stationery::latest('id')->first();
        $kode_barang = Stationery::generateNextKode($lastItem ? $lastItem->kode_barang : null);
        return view('admin.stationery.create', compact('kode_barang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|string|max:255',
            'satuan' => 'required|integer',
            'masuk' => 'required|integer',
            'keluar' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        Stationery::create($validated);

        return redirect()->route('stationery.index')->with('success', 'Stationery berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stationery $stationery)
    {
        return view('admin.stationery.show', compact('stationery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stationery $stationery)
    {
        return view('admin.stationery.edit', compact('stationery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stationery $stationery)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga_barang' => 'required|string|max:255',
            'satuan' => 'required|integer',
            'masuk' => 'required|integer',
            'keluar' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        $stationery->update($validated);

        return redirect()->route('stationery.index')->with('success', 'Stationery berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stationery $stationery)
    {
        $stationery->delete();

        return redirect()->route('stationery.index')->with('success', 'Stationery berhasil dihapus.');
    }
}
