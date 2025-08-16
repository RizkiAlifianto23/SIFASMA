<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;

class Users extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::where('status', 1)->get();
        return view('users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|exists:role,id',
            'status' => 'required|in:1,2',
            'password' => 'required|string|min:6',
        ]);

        // Simpan user baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'id_role' => $validated['role_id'],
            'status' => $validated['status'],
            'password' => Hash::make($validated['password']),
            'created_by' => auth()->id(), // ID user yang membuat
            'updated_by' => auth()->id(), // ID user yang mengubah
        ]);

        // Tambahkan log setelah berhasil membuat user
        Log::create([
            'action' => 'create user',
            'created_by' => auth()->id(), // ID user yang sedang login
        ]);

        return redirect()->route('users')->with('success', 'User berhasil ditambahkan.');
    }


    public function update(Request $request, $id)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:role,id',
            'status' => 'required|in:1,2',
            'password' => 'nullable|string|min:6',
        ]);

        $user = User::findOrFail($id);

        $user->name = $validated['name'];
        $user->id_role = $validated['role_id'];
        $user->status = $validated['status'];
        $user->updated_by = auth()->id(); // ID user yang mengubah

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Tambahkan log setelah berhasil update user
        Log::create([
            'action' => 'update user',
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

}
