<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function viewDetailPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function viewDaftarPelatihan() {
        $pelatihan = Pelatihan::get();
        return view('admin.daftar_pelatihan',['pelatihan'=>$pelatihan]);
    }
}
