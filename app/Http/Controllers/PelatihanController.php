<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class PelatihanController extends Controller
{
    public function viewDetailPelatihanPeserta(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function viewDaftarPelatihan() {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        if($admin){
            $pelatihan = Pelatihan::all();
            return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

    // public function create(){
    //     $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
    //             ->where('admin.user_id', Auth::user()->id)
    //             ->select('admin.nama', 'admin.id', 'users.username')
    //             ->first();
    //     if($admin){
    //         $pelatihan = Pelatihan::all();
    //         return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
    //     }
    // }

    public function store(Request $request): RedirectResponse {
        //dd($request);
        $validated = $request->validate([
            'kode' => ['required', 'regex:/^[A-Z0-9]{6}$/',Rule::unique('pelatihan')],
            'nama' => ['required', 'alpha_num', 'between:1,24'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:Not started yet,On going,Completed'],
            'penyelenggara' => ['required'],
            'tempat' => ['required', 'in:Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8,
            Gedung Balaikota,Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8,
            Gedung Juang 45,Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8,
            Ruang Rapat Lantai 4,Hall Balaikota Semarang,Halaman Balaikota Semarang,
            Ruang Rapat Lantai 6 Siber Pungli'],
            'deskripsi' => ['required', 'max:255'],
        ],[
            'kode.regex' => 'Format kode tidak valid. Kode harus terdiri dari 6 karakter huruf kapital dan angka.',
            'end_date.after_or_equal' => 'Tanggal Selesai Pelatihan harus setelah atau sama dengan Tanggal Mulai Pelatihan.',
            'deskripsi.max'=>'Maksimal panjang huruf untuk deskripsi adalah 255 huruf']);

        //dd($request);
       
        // Proses penyimpanan data jika validasi berhasil
        Pelatihan::create($validated);
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.addPelatihan')->with('success', 'Data pelatihan berhasil disimpan');
    }
    

    public function viewDetailPelatihanAdmin(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('admin.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }
}
