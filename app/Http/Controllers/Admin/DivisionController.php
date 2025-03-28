<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'division_name' => 'required|unique:divisions,division_name'
        ]);

        $division = Division::create([
            'division_name' => $request->division_name // Sesuaikan dengan kolom di tabel
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Divisi berhasil ditambahkan.',
            'division' => $division
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        //
    }
}
