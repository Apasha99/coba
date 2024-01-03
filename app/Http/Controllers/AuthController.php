<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return view('login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = $request->user(); 
        
            if ($user->role_id === 1){
                return redirect()->intended('/dashboardAdmin')->with('success', 'Login successful');
            } else if ($user->role_id === 2) {
                return redirect()->intended('/dashboardPeserta')->with('success', 'Login successful');
            }
            
        };

        return back()->with('error', 'Login Gagal');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('login');
    }
}
