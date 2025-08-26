<?php

namespace App\Http\Controllers\User;

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
        $title = 'Hapus Data!';
        $text = "Apakah Anda Yakin Ingin Menghapusnya?";

        // $data = ItemDemand::paginate(10);
        $user = Auth::user()->name;
        $data = ItemDemand::with('user')
            ->where('user_id', Auth::id())
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

        // $data = ItemDemand::where('user_id', Auth::id())
        //     ->orderBy(('dos'), 'desc')
        //     ->paginate(10);

        $totalHargaBulanIni = ItemDemand::where('user_id', Auth::id())
            ->whereYear('dos', now()->year)
            ->whereMonth('dos', now()->month)
            ->where('status', 1)
            ->with('stationery')
            ->get()
            ->sum(function ($item) {
                $harga = $item->stationery?->harga_barang ?? 0;
                return $harga * $item->amount;
            });

        $limitBulanIni = 5_000_000; // Rp 5 juta

        return view('user.demand.index', compact('data', 'totalHargaBulanIni', 'limitBulanIni', 'user'));
        // return view('user.demand.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stationeries = Stationery::orderBy('nama_barang'); // Atau sesuai jenis_barang jika ingin filter
        return view('user.demand.create', compact('stationeries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validated = $request->validate([
        //     'user_id' => 'required|string|max:255',
        //     'stationery_id' => 'required|string|max:255',
        //     'amount' => 'required|integer|max:255',
        //     'dos' => 'required|date|max:255',
        //     'manager_approval' => 'nullable|integer|max:255',
        //     'status' => 'nullable|integer|max:255',
        //     // 'satuan' => 'required|string|max:255',
        //     // 'masuk' => 'required|integer',
        //     // 'keluar' => 'required|integer',
        //     // 'stok' => 'required|integer',
        // ]);

        // // Ambil stok dari database berdasarkan stationery_id
        // $stationery = Stationery::find($request->stationery_id);

        // if ($request->amount > $stationery->stok) {
        //     return back()->with('error', 'Jumlah barang yang diminta melebihi stok yang ada.');
        // } else {
        //     ItemDemand::create($validated);
        //     return redirect()->route('item-demand.index')->with('success', 'Permintaan barang berhasil ditambahkan.');
        // }

        $request->validate([
            'items' => 'required|array',
            'items.*.stationery_id' => 'required|exists:stationeries,id',
            'items.*.amount' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            $stationery = Stationery::find($item['stationery_id']);
            if ($item['amount'] > $stationery->stok) {
                return back()->with('error', 'Jumlah barang "' . $stationery->nama_barang . '" yang diminta melebihi stok yang ada.');
            }
            // Simpan permintaan barang
            ItemDemand::create([
                'user_id' => auth()->id(),
                'stationery_id' => $item['stationery_id'],
                'amount' => $item['amount'],
                'dos' => now()->toDateString(), // atau bisa dikirim dari form juga
            ]);
        }

        return redirect()->route('item-demand.index')->with('success', 'Pengajuan berhasil disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemDemand $itemDemand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = ItemDemand::with('stationery')->findOrFail($id);

        // Optional: pastikan hanya user yang berhak bisa edit
        if ((int) $data->user_id !== (int) Auth::id()) {
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

    public function editByDate($userId, $date)
    {
        $items = ItemDemand::with('stationery')
            ->where('user_id', $userId)
            // ->where('coo_approval', 1)
            ->whereDate('dos', $date)
            ->get();

        $user = User::findOrFail($userId);

        return view('edit.demands', compact('items', 'user', 'date'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_barang' => 'required|in:1,2',
            'stationery_id' => 'required|exists:stationeries,id',
            'amount' => 'required|integer|min:1',
        ]);

        $itemDemand = ItemDemand::findOrFail($id);
        $stationery = Stationery::findOrFail($request->stationery_id);

        if ($request->amount > $stationery->stok) {
            return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia!');
        }

        $itemDemand->update([
            'user_id' => auth()->id(), // Bisa juga tetap pakai $request->user_id jika ingin fleksibel
            'stationery_id' => $request->stationery_id,
            'amount' => $request->amount,
            // 'dos' => $request->dos,
        ]);

        return redirect()->route('item-demand.index')->with('success', 'Pengajuan berhasil diperbarui!');
    }

    public function updateByDate(Request $request, $userId, $date)
    {
        // Validasi ownership (pastikan user hanya mengedit miliknya sendiri)
        if (Auth::id() != $userId) {
            abort(403, 'Unauthorized action.');
        }

        $amounts = $request->input('amount', []);
        $action = $request->input('action'); // menangkap 'approve' jika diklik
        // $notes = $request->input('notes', []);

        foreach ($amounts as $id => $value) {
            $item = ItemDemand::where('id', $id)
                ->where('user_id', $userId)
                ->whereDate('dos', $date)
                ->firstOrFail();

            // Hanya bisa edit jika belum ada persetujuan/penolakan
            if ($item->manager_approval !== null || $item->coo_approval !== null || $item->status !== null) {
                continue;
            }

            // Validasi jumlah
            if ($value < 1) {
                return redirect()->back()->with('error', 'Jumlah tidak boleh kurang dari 1');
            }

            // PROSES EDIT JUMLAH (hanya jika belum diapprove/reject oleh COO/Admin)
            if ($item->canEditAmountByLevel(1)) {
                $item->amount = $value;
            } elseif ($item->amount != $value) {
                return redirect()->back()->with('error', 'Jumlah tidak dapat diubah karena permintaan sudah disetujui oleh COO/Admin.');
            }

            // Jika disetujui oleh manager
            // if ($action === 'approve' && auth()->user()->role === 'user') {
            //     // $item->manager_approval = 1;
            //     // $item->manager_approved_at = now(); // simpan waktu persetujuan
            //     // $item->amount = $value;
            // }

            // Update data

            // Tambahkan catatan jika ada
            // if (!empty(trim($notes[$id] ?? ''))) {
            //     $item->notes = ($item->notes ? $item->notes . "\n" : "") . "user: " . trim($notes[$id]);
            // }

            $item->save();
        }

        return redirect()->route('item-demand.index')
            ->with('success', 'Permintaan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        // if ($itemDemand->manager_approval == 1) {
        //     return redirect()->back()->with('error', 'Permintaan yang sudah disetujui tidak bisa dihapus.');
        // }
        // $itemDemand->delete();

        // return redirect()->route('item-demand.index')->with('success', 'Data Permintaan Berhasil Dihapus.');

        try {
            $item = ItemDemand::findOrFail($id,);
            
            // Pastikan user hanya bisa hapus item miliknya sendiri
            if ((int)$item->user_id !== (int)Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus item ini.'
                ], 403);
            }
            
            // Pastikan item belum disetujui/diproses
            if ($item->status !== null || 
                $item->manager_approval !== null || 
                $item->coo_approval !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item sudah diproses, tidak dapat dihapus.'
                ], 400);
            }
            
            $item->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus item.'
            ], 500);
        }
    }

}
