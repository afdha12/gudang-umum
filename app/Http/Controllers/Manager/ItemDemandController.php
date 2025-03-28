<?php

namespace App\Http\Controllers\Manager;

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
        $user = auth()->user(); // Mendapatkan user yang sedang login

        $data = ItemDemand::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('division_id', $user->division_id);
            })
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('MAX(dos) as last_pengajuan')
            )
            ->groupBy('user_id')
            ->paginate(10);

        return view('manager.demand.index', compact('data'));
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
            ->paginate(10);

        return view('manager.demand.detail', compact('userDemands'));
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
        if ($pengajuan->manager_approval == 0) { // Hanya jika belum disetujui

            $pengajuan->manager_approval = 1;
            $pengajuan->save();
            return redirect()->back()->with('success', 'Pengajuan telah disetujui.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        //
    }
}
