<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            

            // Cek status
            if ($user->status != 1) {
                auth()->logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
            }

            // Cek dan logout session sebelumnya jika ada
            if ($user->last_session_id && $user->last_session_id !== session()->getId()) {
                \DB::table('sessions')->where('id', $user->last_session_id)->delete();
            }

            // Simpan session sekarang ke kolom last_session_id
            $user->last_session_id = session()->getId();
            $user->save();

            return redirect()->intended('/')->with('success', 'Login berhasil');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            $user->last_session_id = null;
            $user->save();
        }

        auth()->logout();
        return redirect('/login')->with('success', 'Logout berhasil');
    }
}
