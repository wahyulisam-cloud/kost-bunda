<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // =========================
    // HALAMAN LOGIN
    // =========================
    public function loginForm()
    {
        return view('auth.login');
    }

    // =========================
    // LOGIN WEB
    // =========================
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Password salah');
        }

        // 🔥 REGENERATE DULU (PENTING)
        $request->session()->regenerate();

        // ✅ SIMPAN SESSION
        $request->session()->put('login', true);
        $request->session()->put('user_id', $user->id_user);
        $request->session()->put('username', $user->username);
        $request->session()->put('nama', $user->nama ?? '');

        // 🔥 PAKSA SIMPAN (KHUSUS RAILWAY)
        $request->session()->save();

        // 🔥 DEBUG (sementara saja)
        // dd($request->session()->all());

        return redirect('/dashboard');
    }

    // =========================
    // LOGIN API (BERSIH)
    // =========================
    public function apiLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = DB::table('users')
            ->where('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username atau Password salah'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'user' => $user
        ]);
    }

    // =========================
    // LOGOUT
    // =========================
    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}