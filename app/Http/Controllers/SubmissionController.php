<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Submission;
use App\Models\Tugas;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function viewSubmissionForm(String $plt_kode, String $tugas_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        return view('peserta.submit_tugas', ['pelatihan' => $pelatihan, 'tugas' => $tugas]);
    }

    
}
