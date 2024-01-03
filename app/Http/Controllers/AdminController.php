<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function admin() {
        if (Auth::user()->role_id === 1) {
            $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
            return view('admin.dashboard',['admin'=>$admin]);
        }
    }

}
