<?php

namespace App\Http\Controllers\User;

use App\Models\ItemDemand;
use App\Models\Stationery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $data = ItemDemand::where('user_id', Auth::id())->paginate(10);
        return view('user.demand.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stationeries = Stationery::all(); // Atau sesuai jenis_barang jika ingin filter
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        if ($itemDemand->manager_approval == 1) {
            return redirect()->back()->with('error', 'Permintaan yang sudah disetujui tidak bisa dihapus.');
        }
        $itemDemand->delete();

        return redirect()->route('item-demand.index')->with('success', 'Data Permintaan Berhasil Dihapus.');
    }

}
