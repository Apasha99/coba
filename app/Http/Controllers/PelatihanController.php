<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function viewPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.pelatihan',['pelatihan'=>$pelatihan]);
    }
}
