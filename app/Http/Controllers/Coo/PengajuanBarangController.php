<?php

namespace App\Http\Controllers\Coo;

use App\Models\User;
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
            ->where(function ($query) {
                $query->where('manager_approval', 1)
                    ->orWhere(function ($q) {
                        $q->where('manager_approval', 0)
                            ->whereHas('user.division', function ($d) {
                                $d->where('managed_by_coo', true);
                            });
                    });
            })
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                // DB::raw('SUM(CASE WHEN coo_approval = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw("SUM(CASE WHEN coo_approval IS NULL THEN 1 ELSE 0 END) as item_status"),
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
        $user = User::findOrFail($user_id);

        $data = ItemDemand::with('user')
            ->where('user_id', $user_id)
            ->where(function ($query) {
                $query->where('manager_approval', 1)
                    ->orWhere(function ($q) {
                        $q->where('manager_approval', 0)
                            ->whereHas('user.division', function ($d) {
                                $d->where('managed_by_coo', true);
                            });
                    });
            })
            ->select(
                'dos',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN coo_approval = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('SUM(CASE WHEN coo_approval IS NULL THEN 1 ELSE 0 END) as pending_items')
            )
            ->groupBy('dos')
            ->orderBy('dos', 'desc')
            ->paginate(10);

        return view('show.show_by_date', compact('data', 'user'));
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
            'amount' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Update jumlah permintaan
        $itemDemand->amount = $request->amount;

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
        }

        $itemDemand->save();

        return redirect()->route('user_demands.show', $itemDemand->user_id)
            ->with('success', 'Permintaan berhasil diproses oleh COO.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function editByDate($userId, $date)
    {
        $items = ItemDemand::with('stationery')
            ->where('user_id', $userId)
            ->whereDate('dos', $date)
            ->get();

        $user = User::findOrFail($userId);

        return view('edit.demands', compact('items', 'user', 'date'));
    }

    public function updateByDate(Request $request, $userId, $date)
    {
        $amounts = $request->input('amount', []);
        $notes = $request->input('notes', []);
        $action = $request->input('action'); // menangkap 'approve' jika diklik
        $statuses = $request->input('status', []);
        $userRole = auth()->user()->role;

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('dos', $date)
                ->first();

            if (!$item)
                continue;

            $requestStatus = $statuses[$id] ?? null;
            $isReject = ($requestStatus === '0');

            // Jika sudah di-reject sebelumnya, tidak bisa diubah lagi
            if ($item->status === 0 || $item->manager_approval === 0 || $item->coo_approval === 0) {
                continue;
            }

            // PROSES REJECT
            if ($isReject) {
                $item->coo_approval = 0;
                $item->rejected_by = 'Wadirum';
                $note = trim($notes[$id] ?? '');
                $formattedNote = "wadirum: Ditolak" . ($note ? " - $note" : "");
                $item->notes = trim(string: ($item->notes ? $item->notes . "\n" : "") . $formattedNote);
                $item->save();
                continue;
            }

            if ($item->canEditAmountByLevel(2)) { // COO = level 2
                $item->amount = $value;
            } elseif ($item->amount != $value) {
                return redirect()->back()->with('error', 'Jumlah tidak dapat diubah karena permintaan sudah disetujui.');
            }

            // Tambahkan catatan jika ada
            $newNote = trim($notes[$id] ?? '');
            if ($newNote) {
                $formattedNote = auth()->user()->role . ': ' . $newNote;
                $item->notes = $item->notes
                    ? $item->notes . "\n" . $formattedNote
                    : $formattedNote;
            }

            // Jika disetujui oleh COO
            if ($action === 'approve' && auth()->user()->role === 'coo') {
                $item->coo_approval = 1;
            }

            $item->save();
        }

        return redirect()->route('user_demands.show', $userId)
            ->with('success', 'Permintaan berhasil diperbarui' . ($action === 'approve' ? ' dan disetujui.' : '.'));
    }
}
