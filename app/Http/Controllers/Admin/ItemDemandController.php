<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use App\Models\BarangHistory;
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

        // $data = ItemDemand::paginate(10);
        $data = ItemDemand::with('user')
            ->where('coo_approval', 1)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('MAX(dos) as last_pengajuan')
            )
            ->groupBy('user_id')
            ->orderBy('last_pengajuan', 'desc')->orderBy('status')
            ->paginate(10);

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
        // $userDemands = ItemDemand::with('user')
        //     ->where('user_id', $user_id)
        //     ->where('coo_approval', 1)
        //     ->paginate(10);

        // return view('admin.demand.detail', compact('userDemands'));
        $user = User::findOrFail($user_id);

        $data = ItemDemand::with('user')
            ->where('user_id', $user_id)
            ->where('coo_approval', 1)
            ->select(
                'dos',
                DB::raw('COUNT(*) as total_pengajuan'),
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status')
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
        // $manager = Auth::user(); // user yang sedang login, diasumsikan role-nya 'manager'

        // Optional: pastikan hanya user yang berhak bisa edit
        // if ((int) $data->user->division_id !== (int) $manager->division_id) {
        //     abort(403);
        // }
        if ($data->status == 1) {
            return redirect()->back()->with('error', 'Permintaan yang sudah disetujui tidak bisa diedit.');
        }

        return view('manager.demand.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $pengajuan = ItemDemand::findOrFail($id);
        $pengajuan = ItemDemand::with('stationery')->findOrFail($id);

        // Validasi jumlah permintaan
        $request->validate([
            'amount' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Update jumlah permintaan
        $pengajuan->amount = $request->amount;

        // Cek apakah sudah mendapat persetujuan dari manager
        if ($pengajuan->coo_approval == 0) {
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

                // Simpan ke history
                BarangHistory::create([
                    'stationery_id' => $stationery->id,
                    'jenis' => 'keluar',
                    'jumlah' => $pengajuan->amount,
                    'tanggal' => now(),
                ]);

                // Tambahkan catatan
                $newNote = trim($request->notes);
                if ($newNote) {
                    $formattedNote = "gudang: {$newNote}";
                    $pengajuan->notes = $pengajuan->notes
                        ? $pengajuan->notes . "\n" . $formattedNote
                        : $formattedNote;
                }

                // Jika tombol yang diklik adalah Setujui
                if ($request->action === 'approve') {
                    $pengajuan->status = 1; // contoh status approved by manager
                }

                // Update status pengajuan
                $pengajuan->status = 1;
                $pengajuan->save();

                return redirect()->route('demand.show', $pengajuan->user_id)
                    ->with('success', 'Permintaan berhasil diperbarui oleh Manager.');
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
        $action = $request->input('action'); // 'approve' jika tombol disetujui ditekan

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::with('stationery')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->first();

            if (!$item)
                continue;

            // ✅ Lindungi agar jumlah tidak bisa diubah setelah disetujui
            if ($item->status == 0) {
                $item->amount = $value;
            } elseif ($item->amount != $value) {
                return redirect()->back()->with('error', 'Jumlah tidak dapat diubah karena permintaan sudah disetujui.');
            }

            // ✅ Lindungi catatan jika perlu
            $newNote = trim($notes[$id] ?? '');
            if ($item->status == 0 && $newNote) {
                $formattedNote = auth()->user()->role . ': ' . $newNote;
                $item->notes = $item->notes
                    ? $item->notes . "\n" . $formattedNote
                    : $formattedNote;
            }

            // ✅ Persetujuan admin dan pengurangan stok hanya jika belum disetujui
            if ($action === 'approve' && auth()->user()->role === 'admin' && $item->status == 0) {
                $stationery = $item->stationery;

                if ($stationery->stok >= $item->amount) {
                    $stationery->stok -= $item->amount;
                    $stationery->keluar += $item->amount;
                    $stationery->save();

                    BarangHistory::create([
                        'stationery_id' => $stationery->id,
                        'jenis' => 'keluar',
                        'jumlah' => $item->amount,
                        'tanggal' => now(),
                    ]);

                    $item->status = 1;
                } else {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $stationery->nama_barang);
                }
            }

            $item->save();
        }

        return redirect()->route('demand.show', $userId)
            ->with('success', 'Permintaan berhasil diperbarui' . ($action === 'approve' ? ' dan disetujui.' : '.'));
    }

}
