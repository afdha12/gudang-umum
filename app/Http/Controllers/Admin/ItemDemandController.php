<?php

namespace App\Http\Controllers\Admin;

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
        $userDemands = ItemDemand::with('user')
            ->where('user_id', $user_id)
            ->where('coo_approval', 1)
            ->paginate(10);

        return view('admin.demand.detail', compact('userDemands'));
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
}
