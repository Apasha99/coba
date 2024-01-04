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
    public function viewPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.pelatihan', ['pelatihan' => $pelatihan]);
    }

    public function create(){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        if($admin){
            $pelatihan = Pelatihan::all();
            return view('admin.pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

    public function store(Request $request): RedirectResponse {
        $validated = $request->validate([
            'kode' => ['required', 'regex:/^[A-Z0-9]{6}$/',Rule::unique('pelatihan')],
            'nama' => ['required', 'alpha_num', 'between:1,24'],
            'start_date' => ['nullable', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:Not started yet,On going,Completed'],
            'penyelenggara' => ['required'],
            'tempat' => ['required', 'in:Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8,
            Gedung Balaikota,Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8,
            Gedung Juang 45,Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8,
            Ruang Rapat Lantai 4,Hall Balaikota Semarang,Halaman Balaikota Semarang,
            Ruang Rapat Lantai 6 Siber Pungli'],
            'deskripsi' => ['required', 'max:255'],
        ]);
    
        // Proses penyimpanan data jika validasi berhasil
        Pelatihan::create($validated);
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.create_pelatihan')->with('success', 'Data pelatihan berhasil disimpan');
    }
    
}
