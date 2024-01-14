<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta_Pelatihan;
use App\Models\Peserta;
use App\Models\Admin;
use App\Models\User;
use App\Models\Nilai_Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Different;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    public function peserta() {
        if (Auth::user()->role_id === 2) {
            $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();

            $pelatihan = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->where('peserta_pelatihan.peserta_id', $peserta->id)
                ->get();
            //dd($pelatihan);
            return view('peserta.dashboard',['peserta'=>$peserta, 'pelatihan'=>$pelatihan]);
        }
    }

    public function daftar_peserta()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->select('peserta.user_id as peserta_id','peserta.nama as peserta_nama', 'peserta.noHP', 'peserta.alamat', 'users.foto', 'users.password_awal', 'peserta.id', 'users.username', 'users.email')
            ->get();

        return view('admin.daftar_peserta', ['admin' => $admin, 'peserta' => $peserta]);
    }

    public function store(Request $request): RedirectResponse {
        //dd($request);
        $validated = $request->validate([
            'nama' => ['required'],
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'unique:users,email','email',
            Rule::unique('users')->where(function ($query) {
                // Contoh: Memastikan domain email adalah contoh.com
                $query->where('email_domain', 'contoh.com');
            })],
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
            $user->role_id = 2;
            $user->save();

            // Membuat peserta baru
            $peserta = new Peserta();
            $peserta->nama = $validated['nama'];
            $peserta->noHP = $validated['noHP'];
            $peserta->alamat = $validated['alamat'];
            $peserta->user_id = $user->id;
            $peserta->save();
        });
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.viewDaftarPeserta')->with('success', 'Data peserta berhasil disimpan');
    }

    public function searchPeserta(Request $request)
    {
        //dd($request);
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();

        $peserta = Peserta::leftjoin('users','users.id','=','peserta.user_id')
            ->select('peserta.nama as peserta_nama', 'alamat', 'users.username','users.email','peserta.user_id as peserta_id','noHP','password_awal')
            ->where(function ($query) use ($search) {
                $query
                    ->where('peserta.nama', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('peserta.user_id', 'like', '%' . $search . '%');
            })
            ->get();

        return view('admin.daftar_peserta', ['peserta' => $peserta, 'admin' => $admin, 'search' => $search]);
    }

    public function edit($id)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        if ($admin) {
            $peserta = Peserta::leftJoin('users', 'users.id', '=', 'peserta.user_id')
                            ->where('peserta.user_id', $id)
                            ->first();
            return view('admin.edit_peserta', ['admin' => $admin, 'peserta' => $peserta]);
        }
            
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::leftJoin('users', 'users.id', '=', 'peserta.user_id')
                            ->where('peserta.user_id', $id)
                            ->first();
        if (!$peserta) {
            return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Tidak dapat menemukan peserta yang ingin diedit.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'alpha'],
            'username' => ['required', ],
            'email' => ['required', 'email'],
            'noHP' => ['required', 'numeric'],
            'alamat' => ['required'],
            'new_password' => ['nullable', 'min:8', 'string'],
            'conf_password' => ['nullable', 'same:new_password'],
            'foto' => [ 'max:10240'],
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'nama' => $validated['nama'] ?? null,
                'noHP' => $validated['noHP'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ];

            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'foto' => $validated['foto'] ?? null,
            ];

            if ($request->has('new_password')) {
                $updateData2['password'] = bcrypt($validated['new_password']);
                $updateData2['password_awal'] = $validated['new_password'];
            }

            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $updateData2['foto'] = $fotoPath;
            }

            $peserta->update(array_filter($updateData));
            $peserta->user->update(array_filter($updateData2));

            DB::commit();

            return redirect()
                ->route('admin.viewDaftarPeserta')
                ->with('success', 'Data peserta berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data peserta');
        }
    }

    public function delete(Request $request, String $peserta_id)
{
    try {
        $peserta = Peserta::leftJoin('users', 'users.id', '=', 'peserta.user_id')
            ->where('peserta.user_id', $peserta_id)
            ->select('peserta.user_id','users.id')
            ->first();

        $peserta_pelatihan = Peserta_Pelatihan::leftJoin('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->leftJoin('users', 'users.id', '=', 'peserta.user_id')
            ->leftJoin('pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
            ->where('peserta.user_id', $peserta_id)
            ->select('peserta.user_id', 'users.id','peserta.nama', 'pelatihan.status', 'peserta_pelatihan.plt_kode')
            ->first();
        //dd($peserta_pelatihan);

        DB::beginTransaction();

        try {
            if ($peserta_pelatihan && $peserta_pelatihan->status == 'On going') {
                return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Tidak dapat menghapus peserta dengan pelatihan yang masih berlangsung.');
            }

            if ($peserta_pelatihan != null) {
                Nilai_Test::leftJoin('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                           ->where('peserta.user_id', $peserta_id)->delete();
                $peserta_pelatihan->delete();
            }
            Peserta::where('user_id', $peserta_id)->delete();
            User::where('id', $peserta_id)->delete();

            DB::commit();

            return redirect()->route('admin.viewDaftarPeserta')->with('success', 'Peserta dan semua data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);

            return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Terjadi kesalahan saat menghapus peserta dan data terkait.');
        }
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Tidak dapat menemukan peserta yang ingin dihapus.');
    }
}



}
