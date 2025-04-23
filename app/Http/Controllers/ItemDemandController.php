<?php

namespace App\Http\Controllers;

use App\Models\ItemDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ItemDemand::with('user')
            ->where('coo_approval', 1)  // Hanya menampilkan permintaan yang sudah disetujui oleh COO
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status'),  // Menampilkan jumlah permintaan yang belum disetujui
                DB::raw('MAX(dos) as last_pengajuan')  // Menampilkan tanggal permintaan terakhir
            )
            ->groupBy('user_id')  // Mengelompokkan berdasarkan user_id
            ->paginate(10);  // Menggunakan pagination

        return view('admin.demand.index', compact('data'));  // Mengirim data ke view
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
