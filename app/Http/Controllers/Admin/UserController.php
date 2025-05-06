<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $data = User::paginate(10);
        return view('admin.users.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();
        return view('admin.users.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255',
            'role' => 'required|in:admin,user,manager',
            'division_id' => 'nullable|string|max:255',
        ]);

        // Tetapkan password default
        $validated['password'] = Hash::make('Hermina32');

        User::create($validated);

        return redirect()->route('users-management.index')->with('success', 'User berhasil dibuat.');
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
        $data = User::find($id);
        $divisions = Division::all();
        $roles = ['admin', 'user', 'manager'];
        return view('admin.users.edit', compact('data', 'divisions', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id); // Pastikan ID valid

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255',
            'role' => 'nullable|in:admin,user,manager,coo',
            'division_id' => 'nullable|integer',
        ]);

        // Cek apakah reset password dicentang
        if ($request->has('reset_password')) {
            $validated['password'] = Hash::make('Hermina32');
            $validated['password_changed'] = false; // Menandai bahwa password belum diganti oleh user
        }

        $data->update($validated);

        return redirect()->route('users-management.index')->with('success', 'User berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users-management.index')->with('success', 'User berhasil dihapus.');
    }
}
