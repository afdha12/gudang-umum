<?php

namespace App\Http\Controllers\User;

use App\Models\ItemDemand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return view('user.dashboard');

        $userId = Auth::id();
        $total = ItemDemand::where('user_id', $userId)->count();
        $menunggu = ItemDemand::where('user_id', $userId)->where('status', 0)->count();
        $disetujui = ItemDemand::where('user_id', $userId)->where('status', 1)->count();
        $latest = ItemDemand::where('user_id', $userId)->latest()->take(5)->get();

        return view('user.dashboard', compact('total', 'menunggu', 'disetujui', 'latest'));


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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
