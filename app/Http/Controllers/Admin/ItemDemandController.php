<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use App\Models\BarangHistory;
use Illuminate\Pagination\LengthAwarePaginator;
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
        // Ambil semua permintaan yang SUDAH disetujui COO
        $collection = ItemDemand::where('coo_approval', 1)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as total_pengajuan'),
                // Hanya hitung item yang BELUM disetujui gudang (null)
                DB::raw("SUM(CASE WHEN status IS NULL THEN 1 ELSE 0 END) as item_status"),
                DB::raw('MAX(dos) as last_pengajuan')
            )
            ->groupBy('user_id')
            ->orderByRaw('MAX(status IS NULL) DESC')
            ->orderBy('last_pengajuan')
            ->get();

        // Load relasi user biar tidak N+1 query di Blade
        $collection->load('user');

        // Manual paginate karena data < 100
        $perPage = 10;
        $currentPage = request('page', 1);
        $pagedData = $collection->forPage($currentPage, $perPage);

        $data = new LengthAwarePaginator(
            $pagedData,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

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
                DB::raw('SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as item_status'),
                DB::raw('SUM(CASE WHEN status IS NULL THEN 1 ELSE 0 END) as pending_items')
            )
            ->groupBy('dos')
            ->orderByRaw('MAX(status IS NULL) DESC') // urutkan yang status null dulu
            ->orderByDesc('dos') // lalu urutkan dos terbaru
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
            ->where('coo_approval', 1)
            ->whereDate('dos', $date)
            ->get();

        $user = User::findOrFail($userId);

        return view('edit.demands', compact('items', 'user', 'date'));
    }

    public function updateByDate(Request $request, $userId, $date)
    {
        $amounts = $request->input('amount', []);
        $notes = $request->input('notes', []);
        $statuses = $request->input('status', []);
        $isRejected = $request->input('is_rejected', []); // Tambahan untuk track rejected items
        $isCancelled = $request->input('is_cancelled', []); // Tambahan untuk track cancelled items
        $action = $request->input('action'); // 'approve' jika tombol disetujui ditekan

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::with('stationery')
                ->where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('dos', $date)
                ->first();

            if (!$item)
                continue;

            // Skip jika item sudah di-cancel
            if (isset($isCancelled[$id]) && $isCancelled[$id] == '1') {
                continue;
            }

            // Check apakah item di-reject dari form
            $isRejectFromForm = (isset($isRejected[$id]) && $isRejected[$id] === '1');
            $requestStatus = $statuses[$id] ?? null;
            $isRejectFromStatus = ($requestStatus === '0');
            $isReject = $isRejectFromForm || $isRejectFromStatus;

            // Jika sudah di-reject sebelumnya, tidak bisa diubah lagi
            if ($item->status === 0 || $item->manager_approval === 0 || $item->coo_approval === 0) {
                continue;
            }

            // PROSES REJECT
            if ($isReject) {
                $item->status = 0;
                $item->rejected_by = 'Admin';
                $note = trim($notes[$id] ?? '');
                $formattedNote = "admin: Ditolak" . ($note ? " - $note" : "");
                $item->notes = trim(($item->notes ? $item->notes . "\n" : "") . $formattedNote);
                $item->save();
                continue;
            }

            // PROSES EDIT JUMLAH (hanya jika belum diapprove/reject oleh admin)
            if ($item->canEditAmountByLevel(3)) {
                $item->amount = $value;
            } elseif ($item->amount != $value) {
                return redirect()->back()->with('error', 'Jumlah tidak dapat diubah karena permintaan sudah disetujui.');
            }

            // CATATAN TAMBAHAN
            $newNote = trim($notes[$id] ?? '');
            if ($newNote) {
                $formattedNote = 'admin: ' . $newNote;
                $item->notes = $item->notes
                    ? $item->notes . "\n" . $formattedNote
                    : $formattedNote;
            }

            // PROSES APPROVE - HANYA JIKA SUDAH DISETUJUI MANAGER DAN COO
            if ($action === 'approve') {
                // Validasi: item harus sudah disetujui manager dan COO
                if ($item->manager_approval !== 1 || $item->coo_approval !== 1) {
                    return redirect()->back()->with('error', 'Item "' . $item->stationery->nama_barang . '" belum disetujui oleh Manager dan COO.');
                }

                if ($item->status === null) {
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
                            'reference_id' => $item->id,
                            'reference_type' => 'demand',
                        ]);

                        $item->status = 1;
                        $item->admin_approved_at = now(); // simpan waktu persetujuan
                    } else {
                        return redirect()->back()->with('error', 'Stok tidak mencukupi untuk barang: ' . $stationery->nama_barang);
                    }
                }
            }

            $item->save();
        }

        return redirect()->route('demand.show', $userId)
            ->with('success', 'Permintaan berhasil diperbarui' . ($action === 'approve' ? ' dan disetujui.' : '.'));
    }

    public function reject(Request $request, ItemDemand $item)
    {
        $userRole = auth()->user()->role;
        $note = $request->input('note');

        if ($userRole == 'admin') {
            if ($item->coo_approval === 0) {
                return back()->with('error', 'Tidak disetujui oleh COO.');
            }
            $item->status = 0;
            $item->rejected_by = 'Admin';
            // } elseif ($userRole == 'coo') {
            //     if ($item->manager_approval !== 1) {
            //         return back()->with('error', 'Belum disetujui oleh manager.');
            //     }
            //     $item->coo_approval = 0;
            // } elseif ($userRole == 'admin') {
            //     if ($item->coo_approval !== 1) {
            //         return back()->with('error', 'Belum disetujui oleh COO.');
            //     }
            //     // $item->admin_approve = 0;
            //     $item->status = 0;
        }

        // Tambahkan note
        $item->notes = trim($item->notes . "\n" . $userRole . ": Ditolak - " . $note);
        $item->save();

        return back()->with('success', 'Permintaan ditolak.');
    }

    public function cancelDemand($id)
    {
        try {
            $item = ItemDemand::with('stationery')->findOrFail($id);

            // Hanya admin
            if (auth()->user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk membatalkan permintaan.'
                ], 403);
            }

            // Tidak boleh cancel dua kali
            if ($item->is_cancelled == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan ini sudah dibatalkan sebelumnya.'
                ], 400);
            }

            // Hanya boleh cancel jika sudah approve
            if ($item->status !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya permintaan yang sudah disetujui yang dapat dibatalkan.'
                ], 400);
            }

            DB::beginTransaction();

            try {
                $stationery = $item->stationery;

                // Ambil semua history keluar berdasarkan permintaan ini
                $histories = BarangHistory::where('reference_type', 'demand')
                    ->where('reference_id', $item->id)
                    ->where('jenis', 'keluar')
                    ->get();

                foreach ($histories as $h) {
                    // Buat reversal history (masuk)
                    BarangHistory::create([
                        'stationery_id' => $h->stationery_id,
                        'jenis' => 'masuk',
                        'jumlah' => $h->jumlah,
                        'tanggal' => now(),
                        'reference_type' => 'reversal',
                        'reference_id' => $h->id,
                        'note' => 'Reversal pembatalan permintaan #' . $item->id . ' oleh admin'
                    ]);

                    // Kembalikan stok
                    $stationery->increment('stok', $h->jumlah);
                }

                // Tandai sebagai dibatalkan
                $item->is_cancelled = 1;
                // $item->cancelled_at = now();
                // $item->cancelled_by = auth()->user()->id;
                $item->notes = trim($item->notes . "\nadmin: permintaan dibatalkan pada " . now()->format('d-m-Y H:i'));
                $item->save();

                DB::commit();

                \Log::info('Item demand cancelled successfully', [
                    'item_id' => $item->id,
                    'stationery_id' => $stationery->id,
                    'histories_count' => $histories->count(),
                    'cancelled_by' => auth()->user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan berhasil dibatalkan dan stok dikembalikan.'
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();

                \Log::error('Error in cancelDemand transaction', [
                    'item_id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan permintaan: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Unexpected error in cancelDemand', [
                'item_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan yang tidak terduga.'
            ], 500);
        }
    }
}
