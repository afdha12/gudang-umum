<?php

namespace App\Http\Controllers;

use App\Models\Stationery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Hitung jumlah barang yang sedang dalam proses pengajuan (pending)
        // yaitu yang belum di-approve admin, belum di-reject, dan belum di-cancel
        $pendingDemands = \App\Models\ItemDemand::select('stationery_id', DB::raw('SUM(amount) as total_pending'))
            ->whereNull('status')                    // Admin belum approve/reject
            ->where(function ($q) {
                $q->whereNull('manager_approval')    // Belum diproses manager
                    ->orWhere('manager_approval', 1); // Atau sudah disetujui manager
            })
            ->where(function ($q) {
                $q->whereNull('coo_approval')        // Belum diproses COO
                    ->orWhere('coo_approval', 1);     // Atau sudah disetujui COO
            })
            ->where(function ($q) {
                $q->whereNull('is_cancelled')
                    ->orWhere('is_cancelled', 0);     // Belum di-cancel
            })
            ->groupBy('stationery_id')
            ->pluck('total_pending', 'stationery_id');

        $stationeries = Stationery::where('status_barang', 1)
            ->orderBy('nama_barang')
            ->get(['id', 'nama_barang', 'stok', 'harga_barang']);

        // Hitung stok yang tersedia (stok asli - pending)
        $stationeries->each(function ($item) use ($pendingDemands) {
            $pending = $pendingDemands->get($item->id, 0);
            $item->available_stock = max(0, $item->stok - $pending);
        });

        return response()->json($stationeries);
    }

}
