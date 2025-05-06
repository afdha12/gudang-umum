<?php

namespace App\Http\Controllers\Manager;

use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
                DB::raw('SUM(CASE WHEN manager_approval = 0 THEN 1 ELSE 0 END) as item_status'),
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
    public function edit(string $id)
    {
        $data = ItemDemand::with('stationery')->findOrFail($id);
        $manager = Auth::user(); // user yang sedang login, diasumsikan role-nya 'manager'

        // Optional: pastikan hanya user yang berhak bisa edit
        if ((int) $data->user->division_id !== (int) $manager->division_id) {
            abort(403);
        }
        if ($data->manager_approval == 1) {
            return redirect()->back()->with('error', 'Permintaan yang disetujui Manager tidak bisa diedit.');
        }

        return view('manager.demand.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $itemDemand = ItemDemand::with('stationery')->findOrFail($id);

        // Validasi jumlah permintaan
        $request->validate([
            'amount' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Update jumlah permintaan
        $itemDemand->amount = $request->amount;

        // Tambahkan catatan
        $newNote = trim($request->notes);
        if ($newNote) {
            $formattedNote = "manager: {$newNote}";
            $itemDemand->notes = $itemDemand->notes
                ? $itemDemand->notes . "\n" . $formattedNote
                : $formattedNote;
        }

        // Jika tombol yang diklik adalah Setujui
        if ($request->action === 'approve') {
            $itemDemand->manager_approval = 1; // contoh status approved by manager
        }

        $itemDemand->save();

        return redirect()->route('item_demands.show', $itemDemand->user_id)
            ->with('success', 'Permintaan berhasil diperbarui oleh Manager.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        //
    }
}
