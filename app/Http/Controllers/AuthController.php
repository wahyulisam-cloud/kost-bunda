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

    // 🔥 DEBUG 1: cek user
    if (!$user) {
        dd('USER TIDAK DITEMUKAN');
    }

    // 🔥 DEBUG 2: cek password
    if (!Hash::check($request->password, $user->password)) {
        dd('PASSWORD SALAH');
    }

    // 🔥 DEBUG 3: sebelum simpan session
    dd([
        'status' => 'LOGIN BERHASIL',
        'user' => $user
    ]);

    // =============================
    // KODE ASLI (sementara tidak jalan karena dd)
    // =============================

    $request->session()->regenerate();

    $request->session()->put('login', true);
    $request->session()->put('user_id', $user->id_user);
    $request->session()->put('username', $user->username);
    $request->session()->put('nama', $user->nama ?? '');

    $request->session()->save();

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