<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function viewDetailPelatihanPeserta(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function viewDaftarPelatihan() {
        $pelatihan = Pelatihan::all();
        //dd($pelatihan[0]);
        return view('admin.daftar_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function viewDetailPelatihanAdmin(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('admin.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }
}
