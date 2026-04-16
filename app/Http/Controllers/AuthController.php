<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
    {
        public function loginForm()
    {
        return view('auth.login');
    }
    
        public function apiLogin(Request $request)
    {
    $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = \App\Models\User::where('username', $request->username)->first();

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

    $user = DB::table('users')
        ->where('username', $request->username)
        ->first();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Username tidak ditemukan'
        ], 401);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Password salah'
        ], 401);
    }

    return response()->json([
        'status' => 'success',
        'user' => $user
    ]);
}


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

    $request->session()->put('login', true);
$request->session()->regenerate();

    Session::put('user_id', $user->id_user); // ⬅️ FIX
    Session::put('username', $user->username);
    Session::put('nama', $user->nama ?? '');

    return redirect('/dashboard');
}


    public function logout()
    {
        Session::flush();
        return redirect('/');
    }
}
