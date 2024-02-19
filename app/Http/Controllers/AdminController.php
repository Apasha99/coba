<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Roles;
use App\Models\User;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

    public function daftar_admin()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();
        $admin2 = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->select('admin.nama', 'admin.user_id as admin_id', 'users.username','users.foto','users.password_awal','users.email')
                ->get();
        //dd($admin2);
        $pst = User::where('role_id', '=', 1)->select('id')->get();
        return view('admin.daftar_admin', ['admin2'=>$admin2,'admin' => $admin, 'pst'=>$pst]);
    }

    public function detail_admin($admin_id)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $admin2 = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', $admin_id)
            ->select('admin.nama', 'admin.user_id as admin_id', 'users.username','users.foto','users.password_awal','users.email', 'admin.noHP','admin.alamat')
            ->first();
        //dd($admin2);
        $pst = User::where('role_id', '=', 1)->select('id')->get();
        return view('admin.detail_admin', ['admin' => $admin,'admin2'=>$admin2,'pst'=>$pst]);
    }

    public function create()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $admin2 = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->select('admin.nama', 'admin.user_id as admin_id', 'users.username','users.foto','users.password_awal','users.email')
            ->get();
        $pst = User::where('role_id', '=', 1)->select('id')->get();
       
        return view('admin.tambah_admin', ['admin' => $admin, 'admin2' => $admin2,'pst'=>$pst]);
    }

    public function store(Request $request): RedirectResponse {
        //dd($request);
        $validated = $request->validate([
            'nama' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'unique:users,email','email'],
            'noHP' => ['required', 'numeric'],
            'alamat' => ['required'],
            'foto' => ['required', 'max:10240','mimes:jpeg,png,jpg']
        ]);        
        //dd($validated);

        $password = Str::random(8);

        DB::transaction(function () use ($request,$password, $validated) {
            // Membuat user baru
            $user = new User();
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->password = $password;
            $user->password_awal = $password;
            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $user->foto = $fotoPath; // Simpan path foto pada model User
            }
            $user->role_id = 1;
            $user->save();

            $admin = new Admin();
            $admin->nama = $validated['nama'];
            $admin->noHP = $validated['noHP'];
            $admin->alamat = $validated['alamat'];
            $admin->user_id = $user->id;
            $admin->save();
        });
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.viewDaftarAdmin')->with('success', 'Data admin berhasil disimpan');
    }

    public function searchAdmin(Request $request)
    {
        //dd($request);
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::select('kode','nama')->get();
        $pst = User::where('role_id', '=', 1)->select('id')->get();
        $admin2 = Admin::leftjoin('users','users.id','=','admin.user_id')
            ->select('nama', 'alamat', 'users.username','users.email','admin.user_id as admin_id','noHP','password_awal')
            ->where(function ($query) use ($search) {
                $query
                    ->where('admin.nama', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('admin.user_id', 'like', '%' . $search . '%');
            })
            ->get();

        return view('admin.daftar_admin', ['pelatihan'=>$pelatihan,'pst'=>$pst,'admin2' => $admin2, 'admin' => $admin, 'search' => $search]);
    }

    public function edit($admin_id)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        if ($admin) {
            $admin2 = Admin::leftJoin('users', 'users.id', '=', 'admin.user_id')
                            ->where('admin.user_id', $admin_id)
                            ->first();
            return view('admin.edit_admin', ['admin' => $admin, 'admin2' => $admin2]);
        }
            
    }

    public function update(Request $request, $admin_id)
    {
        $admin2 = Admin::leftJoin('users', 'users.id', '=', 'admin.user_id')
                            ->where('admin.user_id', $admin_id)
                            ->select('admin.id as id', 'user_id','nama','username', 'email',
                            'noHP','alamat','password','password_awal','foto')
                            ->first();
        //dd($peserta);
        if (!$admin2) {
            return redirect()->route('admin.viewDaftarAdmin')->with('error', 'Tidak dapat menemukan admin yang ingin diedit.');
        }

        $validated = $request->validate([
            'nama' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'noHP' => ['required', 'numeric'],
            'alamat' => ['required'],
            'new_password' => ['nullable', 'min:8', 'string'],
            'conf_password' => ['nullable', 'same:new_password'],
            'foto' => [ 'max:10240'],
        ]);
        //dd($validated);
        try {
            DB::beginTransaction();

            $updateData = [
                'id' =>$admin2->id,
                'nama' => $validated['nama'],
                'noHP' => $validated['noHP'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ];
            //dd($updateData);
            $admin2->update(array_filter($updateData));
            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'foto' => $validated['foto'] ?? null,
            ];

            if ($request->has('new_password')) {
                $updateData2['password'] = ($validated['new_password']);
                $updateData2['password_awal'] = $validated['new_password'];
            }

            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $updateData2['foto'] = $fotoPath;
            }

            $admin2->user->update(array_filter($updateData2));

            DB::commit();

            return redirect()
                ->route('admin.viewDaftarAdmin')
                ->with('success', 'Data admin berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data admin');
        }
    }

    public function delete(Request $request, String $admin_id)
    {
        try {
            $admin = Admin::where('user_id', $admin_id)->first();
            //dd($admin);
            if (!$admin) {
                return redirect()->route('admin.viewDaftarAdmin')->with('error', 'Tidak dapat menemukan admin yang ingin dihapus.');
            }

            DB::beginTransaction();

            try {
                // Hapus entri admin terlebih dahulu
                $admin->delete();

                // Hapus pengguna terkait
                User::where('id', $admin->user_id)->delete();

                DB::commit();

                return redirect()->route('admin.viewDaftarAdmin')->with('success', 'Admin dan semua data terkait berhasil dihapus.');
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);

                return redirect()->route('admin.viewDaftarAdmin')->with('error', 'Terjadi kesalahan saat menghapus admin dan data terkait.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.viewDaftarAdmin')->with('error', 'Terjadi kesalahan saat menghapus admin dan data terkait.');
        }
    }

    public function download()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();
        $admin2 = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->select('admin.nama', 'admin.user_id as admin_id', 'users.username','users.foto','users.password_awal','users.email')
                ->get();
        //dd($admin2);
        $pst = User::where('role_id', '=', 1)->select('id')->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_admin', [
            'admin' => $admin,
            'admin2' => $admin2,
            'pst' => $pst,
        ]);

        return $pdf->stream('daftar_admin'.'.pdf');
    }


}
