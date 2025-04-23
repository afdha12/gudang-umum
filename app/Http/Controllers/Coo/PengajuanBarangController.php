<?php

namespace App\Http\Controllers\Coo;

use App\Models\ItemDemand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengajuanBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ItemDemand::with('user')
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN coo_approval = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('MAX(dos) as last_pengajuan')
            )
            ->groupBy('user_id')
            ->paginate(10);

        return view('coo.demands.index', compact('data'));
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
            ->where('manager_approval', 1)
            ->paginate(10);

        return view('coo.demands.detail', compact('userDemands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ItemDemand::with('stationery')->findOrFail($id);
        // $manager = Auth::user(); // user yang sedang login, diasumsikan role-nya 'manager'

        // Optional: pastikan hanya user yang berhak bisa edit
        // if ((int) $data->user->division_id !== (int) $manager->division_id) {
        //     abort(403);
        // }
        if ($data->coo_approval == 1) {
            return redirect()->back()->with('error', 'Permintaan yang disetujui Manager tidak bisa diedit.');
        }

        return view('manager.demand.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $itemDemand = ItemDemand::findOrFail($id);

        $request->validate([
            'notes' => 'nullable|string',
        ]);

        // Tambahkan catatan dengan label 'coo'
        $newNote = trim($request->notes);
        if ($newNote) {
            $formattedNote = "wadirum: {$newNote}";
            $itemDemand->notes = $itemDemand->notes
                ? $itemDemand->notes . "\n" . $formattedNote
                : $formattedNote;
        }

        // Proses persetujuan atau penolakan
        if ($request->action === 'approve') {
            $itemDemand->coo_approval = 1; // contoh status approved by COO
        } elseif ($request->action === 'reject') {
            $itemDemand->rejection = 1; // contoh status ditolak oleh COO
        }

        $itemDemand->save();

        return redirect()->route('user_demands.index')
            ->with('success', 'Permintaan berhasil diproses oleh COO.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
