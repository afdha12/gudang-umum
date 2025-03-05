<?php

namespace App\Http\Controllers\Admin;

use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ItemDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Hapus Data!';
        $text = "Apakah Anda Yakin Ingin Menghapusnya?";

        // $data = ItemDemand::paginate(20);
        $data = ItemDemand::with('user')
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('MAX(dos) as last_pengajuan')
            )
            ->groupBy('user_id')
            ->paginate(20);

        return view('admin.demand.index', compact('data'));
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
    public function show($user_id)
    {
        $userDemands = ItemDemand::with('user')
            ->where('user_id', $user_id)
            // ->where('manager_approval', 1)
            ->paginate(20);

        return view('admin.demand.detail', compact('userDemands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemDemand $itemDemand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $pengajuan = ItemDemand::findOrFail($id);

        // Cek apakah sudah mendapat persetujuan dari manager
        if ($pengajuan->manager_approval == 0) {
            return redirect()->back()->with('error', 'Pengajuan belum disetujui oleh manager.');
        }

        if ($pengajuan->status == 0) { // Hanya jika belum disetujui
            $stationery = Stationery::findOrFail($pengajuan->stationery_id);

            // Cek apakah stok mencukupi
            if ($stationery->stok >= $pengajuan->amount) {
                // Kurangi stok
                $stationery->stok -= $pengajuan->amount;
                $stationery->keluar += $pengajuan->amount;
                $stationery->save();

                // Update status pengajuan
                $pengajuan->status = 1;
                $pengajuan->save();

                return redirect()->back()->with('success', 'Pengajuan disetujui dan stok telah dikurangi.');
            } else {
                return redirect()->back()->with('error', 'Stok tidak mencukupi.');
            }
        }

        return redirect()->back()->with('warning', 'Pengajuan sudah disetujui sebelumnya.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        //
    }
}
