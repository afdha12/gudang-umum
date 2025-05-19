<?php

namespace App\Http\Controllers;

use App\Models\Stationery;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getStationeryByJenis(Request $request)
    {
        // Validasi input: hanya menerima nilai 1 atau 2
        $request->validate([
            'jenis' => 'required|in:1,2'
        ]);

        // Ambil data berdasarkan jenis_barang
        $stationery = Stationery::where('jenis_barang', (int) $request->jenis)->get();

        // Cek apakah ada data
        if ($stationery->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Tidak ada barang ditemukan']);
        }

        return response()->json($stationery);
        // dd($request->jenis);
    }

    public function getStationeries()
    {
        return response()->json(Stationery::all(['id', 'nama_barang', 'stok']));
    }

}
