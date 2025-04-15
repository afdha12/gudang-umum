<?php

namespace App\Http\Controllers\User;

use App\Models\ItemDemand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('user.dashboard');

        $userId = Auth::id();
        $menunggu = ItemDemand::where('user_id', $userId)->where('status', 0)->count();
        $disetujui = ItemDemand::where('user_id', $userId)->where('status', 1)->count();

        $aktivitas = ItemDemand::with('stationery')
        ->where('user_id', $userId)
        ->latest('created_at')
        ->take(5) // ambil 5 aktivitas terakhir (bisa disesuaikan)
        ->get()
        ->map(function ($item) {
            return [
                'nama_barang' => ucwords(strtolower($item->stationery->nama_barang)),
                'jumlah' => $item->amount,
                'satuan' => $item->stationery->satuan,
                'waktu' => $item->created_at->diffForHumans(), // contoh: "30 menit yang lalu"
                'status' => $item->status,
                'disetujui' => $item->manager_approval,
            ];
        });

        return view('user.dashboard', compact( 'menunggu', 'disetujui', 'aktivitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
