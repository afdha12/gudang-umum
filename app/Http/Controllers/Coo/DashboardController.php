<?php

namespace App\Http\Controllers\Coo;

use App\Models\ItemDemand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah pengajuan dari divisi yang menunggu persetujuan
        $menunggu = ItemDemand::where('manager_approval',1)->where('coo_approval', 0)
            ->count();

        // Hitung jumlah pengajuan dari divisi yang sudah disetujui
        $disetujui = ItemDemand::where('manager_approval',1)->where('coo_approval', 1)
            ->count();

        // Ambil 5 aktivitas terbaru dari divisinya
        $aktivitas = ItemDemand::with('stationery', 'user')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_barang' => ucwords(strtolower($item->stationery->nama_barang)),
                    'jumlah' => $item->amount,
                    'satuan' => $item->stationery->satuan,
                    'waktu' => $item->created_at->diffForHumans(),
                    'status' => $item->status,
                    'disetujui' => $item->coo_approval,
                    'pengaju' => $item->user->name, // tambahan: nama pengaju
                ];
            });

        return view('coo.dashboard', compact('menunggu', 'disetujui', 'aktivitas'));
        // return view('manager.dashboard');
    }
}
