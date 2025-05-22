<?php

namespace App\Http\Controllers\Manager;

use App\Models\User;
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
            ->orderBy('last_pengajuan', 'desc')
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
        // $userDemands = ItemDemand::with('user')
        //     ->where('user_id', $user_id)
        //     // ->where('manager_approval', 1)
        //     ->paginate(10);
        // return view('manager.demand.detail', compact('userDemands'));

        $user = User::findOrFail($user_id);

        $data = ItemDemand::with('user')
            ->where('user_id', $user_id)
            ->select(
                'dos',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN manager_approval = 0 THEN 1 ELSE 0 END) as item_status')
            )
            ->groupBy('dos')
            ->orderBy('dos', 'desc')
            ->paginate(10);

        return view('show.show_by_date', compact('user', 'data'));
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
    public function update(Request $request, $userId, $date)
    {
        // $itemDemand = ItemDemand::with('stationery')->findOrFail($id);

        // // Validasi jumlah permintaan
        // $request->validate([
        //     'amount' => 'required|integer|min:1',
        //     'notes' => 'nullable|string'
        // ]);

        // // Update jumlah permintaan
        // $itemDemand->amount = $request->amount;

        // // Tambahkan catatan
        // $newNote = trim($request->notes);
        // if ($newNote) {
        //     $formattedNote = "manager: {$newNote}";
        //     $itemDemand->notes = $itemDemand->notes
        //         ? $itemDemand->notes . "\n" . $formattedNote
        //         : $formattedNote;
        // }

        // // Jika tombol yang diklik adalah Setujui
        // if ($request->action === 'approve') {
        //     $itemDemand->manager_approval = 1; // contoh status approved by manager
        // }

        // $itemDemand->save();

        // return redirect()->route('item_demands.show', $itemDemand->user_id)
        //     ->with('success', 'Permintaan berhasil diperbarui oleh Manager.');
        $amounts = $request->input('amount', []);
        $notes = $request->input('notes', []);
        $action = $request->input('action'); // menangkap 'approve' jika diklik

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->first();

            if ($item) {
                // Update jumlah
                $item->amount = $value;

                // Tambahkan catatan jika ada
                $newNote = trim($notes[$id] ?? '');
                if ($newNote) {
                    $formattedNote = auth()->user()->role . ': ' . $newNote;
                    $item->notes = $item->notes
                        ? $item->notes . "\n" . $formattedNote
                        : $formattedNote;
                }

                // Jika disetujui oleh manager
                if ($action === 'approve' && auth()->user()->role === 'manager') {
                    $item->manager_approval = 1;
                }

                $item->save();
            }
        }

        return redirect()->route('item_demands.show', $userId)
            ->with('success', 'Permintaan berhasil diperbarui' . ($action === 'approve' ? ' dan disetujui.' : '.'));

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        //
    }

    public function showByUserAndDate($user_id, $date)
    {
        $user = User::findOrFail($user_id);

        $items = ItemDemand::with('stationery')
            ->where('user_id', $user_id)
            ->whereDate('dos', $date)
            ->get();

        return view('manager.demand.show_by_date', compact('user', 'items', 'date'));
    }

    public function editByDate($userId, $date)
    {
        $items = ItemDemand::with('stationery')
            ->where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->get();

        $user = User::findOrFail($userId);

        return view('edit.demands', compact('items', 'user', 'date'));
    }

    public function updateByDate(Request $request, $userId, $date)
    {
        $amounts = $request->input('amount', []);
        $notes = $request->input('notes', []);
        $action = $request->input('action'); // menangkap 'approve' jika diklik

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->first();

            if ($item) {
                // Update jumlah
                $item->amount = $value;

                // Tambahkan catatan jika ada
                $newNote = trim($notes[$id] ?? '');
                if ($newNote) {
                    $formattedNote = auth()->user()->role . ': ' . $newNote;
                    $item->notes = $item->notes
                        ? $item->notes . "\n" . $formattedNote
                        : $formattedNote;
                }

                // Jika disetujui oleh manager
                if ($action === 'approve' && auth()->user()->role === 'manager') {
                    $item->manager_approval = 1;
                }

                $item->save();
            }
        }

        return redirect()->route('item_demands.show', $userId)
            ->with('success', 'Permintaan berhasil diperbarui' . ($action === 'approve' ? ' dan disetujui.' : '.'));
    }

}
