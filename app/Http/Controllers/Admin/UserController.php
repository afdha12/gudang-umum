<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
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
        return view('admin.users.create');
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
            'division' => 'nullable|string|max:255',
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
        return view('admin.users.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = User::findOrFail($id); // Pastikan ID valid

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id, // Pastikan username unik dengan pengecualian ID saat ini
            'password' => 'nullable|string|min:6|confirmed',
            'email' => 'required|string|email|max:255',
            'role' => 'required|in:admin,user',
            'division' => 'nullable|string|max:255',
        ]);

        // Hanya update password jika diisi oleh user
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']); // Hapus password dari array agar tidak di-set ke NULL
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
