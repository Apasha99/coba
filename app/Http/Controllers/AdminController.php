<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Roles;
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
            $role = Roles::leftJoin('users', 'roles.id', '=', 'users.role_id')
                        ->where('roles.id', Auth::user()->role_id)
                        ->first();
            return view('admin.dashboard',['admin'=>$admin,'role'=>$role]);
        }
    }

}
