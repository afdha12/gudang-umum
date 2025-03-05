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

        $data = ItemDemand::where('user_id', Auth::id())->paginate(20);
        return view('user.demand.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.demand.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|max:255',
            'stationery_id' => 'required|string|max:255',
            'amount' => 'required|integer|max:255',
            'dos' => 'required|date|max:255',
            'manager_approval' => 'nullable|integer|max:255',
            'status' => 'nullable|integer|max:255',
            // 'satuan' => 'required|string|max:255',
            // 'masuk' => 'required|integer',
            // 'keluar' => 'required|integer',
            // 'stok' => 'required|integer',
        ]);

        // Ambil stok dari database berdasarkan stationery_id
        $stationery = Stationery::find($request->stationery_id);

        if ($request->amount > $stationery->stok) {
            return back()->with('error', 'Jumlah barang yang diminta melebihi stok yang ada.');
        } else {
            ItemDemand::create($validated);
            return redirect()->route('item-demand.index')->with('success', 'Permintaan barang berhasil ditambahkan.');
        }
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
    public function edit(ItemDemand $itemDemand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemDemand $itemDemand)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemDemand $itemDemand)
    {
        $itemDemand->delete();

        return redirect()->route('item-demand.index')->with('success', 'Data Permintaan Berhasil Dihapus.');
    }

}
