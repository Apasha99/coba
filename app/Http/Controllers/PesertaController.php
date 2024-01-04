<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function peserta() {
        if (Auth::user()->role_id === 2) {
            $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username')
                ->first();

            $pelatihan = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->where('peserta_pelatihan.peserta_id', $peserta->id)
                ->get();
            //dd($pelatihan);
            return view('peserta.dashboard',['peserta'=>$peserta, 'pelatihan'=>$pelatihan]);
        }
    }
}
