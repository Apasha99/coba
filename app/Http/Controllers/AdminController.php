<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\Instruktur;
use App\Models\Instruktur_Pelatihan;
use App\Models\Materi;
use App\Models\Roles;
use App\Models\User;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\Peserta_Pelatihan;
use App\Models\Test;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $pelatihan = Pelatihan::where('status', 'On going')->get();
            $jmlPltAktif = Pelatihan::where('status', 'On going')->count();
            $jmlPltSelesai = Pelatihan::where('status', 'Completed')->count();
            $jmlPeserta = Peserta::count();
            $jmlInstruktur = Instruktur::count();

            // Menggunakan loop untuk menghitung peserta pelatihan untuk setiap pelatihan
            foreach ($pelatihan as $p) {
                $kodePelatihan = $p->kode;
    
                // Menghitung peserta_pelatihan berdasarkan kode_pelatihan
                $pesertaPelatihan = Peserta_Pelatihan::where('plt_kode', $kodePelatihan)->count();
    
                // Menambahkan informasi pesertaPelatihan ke dalam objek pelatihan
                $p->pesertaPelatihan = $pesertaPelatihan;
            }
            //dd($pesertaPelatihan);
            return view('admin.dashboard',['jmlPltAktif'=>$jmlPltAktif,'jmlPltSelesai'=>$jmlPltSelesai,'jmlPeserta'=>$jmlPeserta,'jmlInstruktur'=>$jmlInstruktur,'admin' => $admin,'role' => $role, 'pelatihan' => $pelatihan]);
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

    public function viewDetailPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('admin.detail_pelatihan', ['bidang'=>$bidang,'pelatihan' => $pelatihan, 'materi' => $materi, 'tugas' => $tugas, 'test' => $test]);
    }

    public function viewDaftarPartisipan(String $plt_kode){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->pluck('peserta_id');
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->pluck('instruktur_id');
        $allPeserta = Peserta::whereNotIn('id', $pesertaTerdaftar)->get();
        $allInstruktur = Instruktur::whereNotIn('id', $instrukturTerdaftar)->get();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->get();
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->get();
        //dd($allPeserta);
    
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('admin.daftar_partisipan', ['bidang' => $bidang, 'pelatihan' => $pelatihan, 
                                                'pesertaTerdaftar' => $pesertaTerdaftar, 'instrukturTerdaftar' => $instrukturTerdaftar,
                                                'allPeserta' => $allPeserta, 'allInstruktur' => $allInstruktur]);
    }

    public function viewDaftarPelatihan() {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
    
        if($admin){
            $pelatihan = Pelatihan::all();
    
            // Menggunakan loop untuk menghitung peserta pelatihan untuk setiap pelatihan
            foreach ($pelatihan as $p) {
                $kodePelatihan = $p->kode;
    
                // Menghitung peserta_pelatihan berdasarkan kode_pelatihan
                $pesertaPelatihan = Peserta_Pelatihan::where('plt_kode', $kodePelatihan)->count();
    
                // Menambahkan informasi pesertaPelatihan ke dalam objek pelatihan
                $p->pesertaPelatihan = $pesertaPelatihan;
            }
    
            return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
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
            'foto' => ['nullable', 'max:10240','mimes:jpeg,png,jpg']
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

    public function download(Request $request)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $request->validate([
            'export_option' => 'required',
            'start_user_id' => 'required_if:export_option,range|exists:users,id',
            'end_user_id' => 'required_if:export_option,range|exists:users,id|after_or_equal:start_user_id',
        ]);

        $exportOption = $request->input('export_option');
        $startUserId = $request->input('start_user_id');
        $endUserId = $request->input('end_user_id');

        $query = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->select('admin.nama', 'admin.user_id as admin_id', 'users.username', 'users.foto', 'users.password_awal', 'users.email');

        // Filter berdasarkan opsi yang dipilih
        if ($exportOption === 'range' && $startUserId && $endUserId) {
            // Filter berdasarkan rentang user_id
            $query->whereBetween('admin.user_id', [$startUserId, $endUserId]);
        }

        $admin2 = $query->get(); // Mengambil data setelah filter

        $pst = User::where('role_id', '=', 1)->select('id')->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_admin', [
            'admin' => $admin,
            'admin2' => $admin2,
            'pst' => $pst,
        ]);

        $filename = $exportOption === 'range' ? 'daftar-list-admin-range.pdf' : 'daftar-list-admin.pdf';

        return $pdf->stream($filename);
    }


    public function ubahPassword(){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                    ->where('admin.user_id', Auth::user()->id)
                    ->first();
    
        return view('admin.ubah_password', compact('admin'));
    }
    

    public function updatePassword(Request $request, $admin_id){
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8|string',
            'conf_password' => 'required|same:new_password',
        ]);
    
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->where('user_id',$admin_id)
                ->first();
    
        if (!$admin) {
            return redirect()->back()->with('error' ,'Admin not found');
        }
    
        // Verifikasi password yang dimasukkan dengan password_awal
        if (!Hash::check($request->input('password'), $admin->password)) {
            return redirect()->back()->with('error' ,'Current password does not match');
        }
    
        try{
            DB::beginTransaction();
    
            $updateData = [];
    
            if ($request->has('new_password')) {
                $updateData['password'] = Hash::make($request->input('new_password'));
                $updateData['password_awal'] = $request->input('new_password');
            }
    
            $admin->user()->update($updateData);
    
            DB::commit();
    
            return redirect()->back()->with('success','Password berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error','Gagal update password');
        }
    }

    public function profil(){
        $admin = Admin::where('admin.user_id', Auth::user()->id)
                    ->first();
        return view('admin.profil', compact('admin'));
    }

    public function editprofil(){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                    ->where('admin.user_id', Auth::user()->id)
                    ->first();
        return view('admin.edit_profil', compact('admin'));
    }

    public function updateProfil(Request $request, $admin_id){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                    ->where('admin.user_id', Auth::user()->id)
                    ->where('user_id',$admin_id)
                    ->first();

        $validated = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'noHP' => ['required', 'numeric'],
            'alamat' => ['required'],
            'foto' => [ 'max:10240'],
        ]);
        //dd($validated);
        try {
            DB::beginTransaction();

            $updateData = [
                'id' =>$admin->id,
                'noHP' => $validated['noHP'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ];
            //dd($updateData);
            $admin->update(array_filter($updateData));
            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'foto' => $validated['foto'] ?? null,
            ];

            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $updateData2['foto'] = $fotoPath;
            }

            $admin->user->update(array_filter($updateData2));

            DB::commit();

            return redirect()
                ->route('admin.profil')
                ->with('success', 'Data user berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data user');
        }
    }

}
