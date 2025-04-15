<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
use App\Models\ItemDemand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $manager = Auth::user(); // user yang sedang login, diasumsikan role-nya 'manager'

        // Ambil semua user ID yang berada dalam divisi yang sama
        $userIdsInSameDivision = User::where('division_id', $manager->division_id)->pluck('id');

        // Hitung jumlah pengajuan dari divisi yang menunggu persetujuan
        $menunggu = ItemDemand::whereIn('user_id', $userIdsInSameDivision)
            ->where('manager_approval', 0)
            ->count();

        // Hitung jumlah pengajuan dari divisi yang sudah disetujui
        $disetujui = ItemDemand::whereIn('user_id', $userIdsInSameDivision)
            ->where('manager_approval', 1)
            ->count();

        // Ambil 5 aktivitas terbaru dari divisinya
        $aktivitas = ItemDemand::with('stationery', 'user')
            ->whereIn('user_id', $userIdsInSameDivision)
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
                    'disetujui' => $item->manager_approval,
                    'pengaju' => $item->user->name, // tambahan: nama pengaju
                ];
            });

        return view('manager.dashboard', compact('menunggu', 'disetujui', 'aktivitas'));
        // return view('manager.dashboard');
    }
}
