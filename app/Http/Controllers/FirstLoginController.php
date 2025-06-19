<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class FirstLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('first-login');
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
        // Cek apakah user yang login hanya bisa mengubah passwordnya sendiri
        if (Auth::id() != $id) {
            abort(403, 'Unauthorized action.');
        }

        $data = User::findOrFail($id);
        return view('first-login', compact('data'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, string $id)
    {
        if (Auth::id() != $id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ]);

        $user = Auth::user();

        // Simpan tanda tangan dengan Spatie
        if ($request->hasFile('signature')) {
            $user->clearMediaCollection('signature'); // Hapus tanda tangan lama
            $user->addMedia($request->file('signature'))->toMediaCollection('signature'); // Simpan yang baru
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->password_changed = true;
        $user->save();

        // Auth::logout();
        event(new Registered($user));

        Auth::login($user);

        // return redirect()->route('login')->with('success', 'Password berhasil diubah, silakan login kembali.');
        // Redirect ke halaman yang sesuai berdasarkan peran
        return redirect()->route($user->role . '.dashboard')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
