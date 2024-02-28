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
            'username_or_email' => 'required',
            'password' => 'required',
        ]);

        // Coba otentikasi dengan email
        if (filter_var($credentials['username_or_email'], FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } else {
            $fieldType = 'username';
        }

        $loginAttempt = [
            $fieldType => $credentials['username_or_email'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($loginAttempt)) {
            $request->session()->regenerate();

            $user = $request->user(); 
            
            if ($user->role_id === 1){
                return redirect()->intended('/admin/dashboard')->with('success', 'Login successful');
            } else if ($user->role_id === 2) {
                return redirect()->intended('/peserta/dashboard')->with('success', 'Login successful');
            } else if ($user->role_id === 3) {
                return redirect()->intended('/instruktur/dashboard')->with('success', 'Login successful');
            }
        }

        return back()->with('error', 'Username atau Email atau Password salah');
    }


    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout successful');
    }
    
}
