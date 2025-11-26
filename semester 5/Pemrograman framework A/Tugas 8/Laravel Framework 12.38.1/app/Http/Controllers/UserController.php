<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Halaman Dashboard (Tugas Baru)
    public function dashboard()
    {
        // 1. Jumlah user aktif
        $activeUsers = User::where('status', 'active')->count();

        // 2. Jumlah admin
        $admins = User::where('role', 'admin')->count();

        // 3. 5 user terbaru
        $latestUsers = User::latest()->take(5)->get();

        return view('dashboard', compact('activeUsers', 'admins', 'latestUsers'));
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required',
            'nomor_hp'  => 'nullable|numeric',
            'alamat'    => 'nullable|string',
            'status'    => 'required'
        ]);

        User::create($request->all());

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|email|unique:users,email,'.$user->id,
            'role'      => 'required',
            'nomor_hp'  => 'nullable|numeric',
            'alamat'    => 'nullable|string',
            'status'    => 'required'
        ]);

        // Password opsional saat update
        if($request->password) {
            $user->update($request->all());
        } else {
            $user->update($request->except('password'));
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User dihapus');
    }
}