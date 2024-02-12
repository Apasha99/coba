<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Admin;
use App\Models\Peserta;
use App\Models\Soal_Test;
use App\Models\Nilai_Test;
use App\Models\Jawaban_Test;
use App\Models\Jawaban_User_Pilgan;
use App\Models\Jawaban_User_Singkat;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponseNa;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class RekapController extends Controller
{
    public function rekapTest(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $peserta = Peserta::get();
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->join('test','test.id','=','nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->groupby('test.id')
            ->sum('nilai_test.nilai');
        $hitungpeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->join('test', 'test.id', '=', 'nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->groupBy('test.id', 'peserta.id')
            ->select('test.id', 'peserta.id')->get()->count();
        //dd($hitungnilai,$hitungpeserta);

        return view('admin.rekap_test', ['hitungpeserta'=>$hitungpeserta,'hitungnilai'=>$hitungnilai,'pelatihan' => $pelatihan,  'test' => $test, 'peserta' => $peserta]);
    }

    public function detailRekapTest(String $plt_kode, $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $test2 = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
    
        // Hitung nilai untuk setiap peserta pada tes tertentu
        $nilaiPeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->selectRaw('peserta.id as peserta_id, peserta.user_id, peserta.nama, SUM(nilai) as total_nilai')
            ->where('test_id', $test_id)
            ->groupBy('peserta.id', 'peserta.user_id', 'peserta.nama')
            ->get();

        //dd($nilaiPeserta);
        // Hitung jumlah peserta pada tes tertentu
        $hitungpeserta = count($nilaiPeserta);
    
        // Hitung total nilai pada tes tertentu
        $hitungnilai = Nilai_Test::where('test_id', $test_id)->sum('nilai');

        $jumlahPesertaPerRentang = [
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '61-70' => 0,
            '71-80' => 0,
            '81-90' => 0,
            '91-100' => 0,
        ];
        
        // Loop melalui nilaiPeserta dan menghitung jumlah peserta dalam setiap rentang
        foreach ($nilaiPeserta as $score) {
            $total_nilai = $score->total_nilai;
            if ($total_nilai >= 0 && $total_nilai <= 10) {
                $jumlahPesertaPerRentang['0-10']++;
            } elseif ($total_nilai >= 11 && $total_nilai <= 20) {
                $jumlahPesertaPerRentang['11-20']++;
            } elseif ($total_nilai >= 21 && $total_nilai <= 30) {
                $jumlahPesertaPerRentang['21-30']++;
            } elseif ($total_nilai >= 31 && $total_nilai <= 40) {
                $jumlahPesertaPerRentang['31-40']++;
            } elseif ($total_nilai >= 41 && $total_nilai <= 50) {
                $jumlahPesertaPerRentang['41-50']++;
            } elseif ($total_nilai >= 51 && $total_nilai <= 60) {
                $jumlahPesertaPerRentang['51-60']++;
            } elseif ($total_nilai >= 61 && $total_nilai <= 70) {
                $jumlahPesertaPerRentang['61-70']++;
            } elseif ($total_nilai >= 71 && $total_nilai <= 80) {
                $jumlahPesertaPerRentang['71-80']++;
            } elseif ($total_nilai >= 81 && $total_nilai <= 90) {
                $jumlahPesertaPerRentang['81-90']++;
            } elseif ($total_nilai >= 91 && $total_nilai <= 100) {
                $jumlahPesertaPerRentang['91-100']++;
            }
        }
        //dd($jumlahPesertaPerRentang);
    
        return view('admin.detail_rekap_test', [
            'test2' => $test2,
            'nilaiPeserta' => $nilaiPeserta,
            'hitungpeserta' => $hitungpeserta,
            'hitungnilai' => $hitungnilai,
            'pelatihan' => $pelatihan,
            'test' => $test,
            'admin' => $admin,
            'jumlahPesertaPerRentang'=>$jumlahPesertaPerRentang
        ]);
    }    

    public function downloadRekap($plt_kode, $test_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $peserta = Peserta::get();
        $nilaiPeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->selectRaw('peserta.id as peserta_id, peserta.user_id, peserta.nama, SUM(nilai) as total_nilai')
            ->where('test_id', $test_id)
            ->groupBy('peserta.id', 'peserta.user_id', 'peserta.nama')
            ->get();

        // Hitung jumlah peserta pada tes tertentu
        $hitungpeserta = count($nilaiPeserta);

        // Hitung total nilai pada tes tertentu
        $hitungnilai = Nilai_Test::where('test_id', $test_id)->sum('nilai');

        $jumlahPesertaPerRentang = [
            '0-10' => 0,
            '11-20' => 0,
            '21-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '51-60' => 0,
            '61-70' => 0,
            '71-80' => 0,
            '81-90' => 0,
            '91-100' => 0,
        ];
        
        // Loop melalui nilaiPeserta dan menghitung jumlah peserta dalam setiap rentang
        foreach ($nilaiPeserta as $score) {
            $total_nilai = $score->total_nilai;
            if ($total_nilai >= 0 && $total_nilai <= 10) {
                $jumlahPesertaPerRentang['0-10']++;
            } elseif ($total_nilai >= 11 && $total_nilai <= 20) {
                $jumlahPesertaPerRentang['11-20']++;
            } elseif ($total_nilai >= 21 && $total_nilai <= 30) {
                $jumlahPesertaPerRentang['21-30']++;
            } elseif ($total_nilai >= 31 && $total_nilai <= 40) {
                $jumlahPesertaPerRentang['31-40']++;
            } elseif ($total_nilai >= 41 && $total_nilai <= 50) {
                $jumlahPesertaPerRentang['41-50']++;
            } elseif ($total_nilai >= 51 && $total_nilai <= 60) {
                $jumlahPesertaPerRentang['51-60']++;
            } elseif ($total_nilai >= 61 && $total_nilai <= 70) {
                $jumlahPesertaPerRentang['61-70']++;
            } elseif ($total_nilai >= 71 && $total_nilai <= 80) {
                $jumlahPesertaPerRentang['71-80']++;
            } elseif ($total_nilai >= 81 && $total_nilai <= 90) {
                $jumlahPesertaPerRentang['81-90']++;
            } elseif ($total_nilai >= 91 && $total_nilai <= 100) {
                $jumlahPesertaPerRentang['91-100']++;
            }
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_detail_rekap_test', [
            'hitungpeserta' => $hitungpeserta,
            'hitungnilai' => $hitungnilai,
            'pelatihan' => $pelatihan,
            'test' => $test,
            'peserta' => $peserta,
            'nilaiPeserta' => $nilaiPeserta,
            'jumlahPesertaPerRentang'=>$jumlahPesertaPerRentang
        ]);

        return $pdf->stream($plt_kode . '_rekap_test_' . $test_id . '.pdf');
    }


    public function download($plt_kode)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $peserta = Peserta::get();
        $hitungnilai = Nilai_Test::join('test', 'test.id', '=', 'nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->sum('nilai_test.nilai');

        $hitungpeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->join('test', 'test.id', '=', 'nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->groupBy('test.id', 'peserta.id')
            ->select('test.id', 'peserta.id')
            ->get()
            ->count();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_rekap_test', [
            'hitungpeserta' => $hitungpeserta,
            'hitungnilai' => $hitungnilai,
            'pelatihan' => $pelatihan,
            'test' => $test,
            'peserta' => $peserta,
        ]);

        return $pdf->stream('rekap_test_'.$plt_kode.'.pdf');
    }

    public function searchTest(Request $request, $plt_kode)
    {
        //dd($request);
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $plt = Pelatihan::join('test','test.plt_kode','=','kode')
            ->where('kode', $plt_kode)
            ->where(function ($query) use ($search) {
                $query
                    ->where('test.nama', 'like', '%' . $search . '%')
                    ->orWhere('test.id', 'like', '%' . $search . '%')
                    ->orWhere('test.kkm', 'like', '%' . $search . '%');
            })
            ->select('pelatihan.kode','pelatihan.nama','test.nama','test.id','test.kkm')
            ->get();
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
        ->join('test','test.id','=','nilai_test.test_id')
        ->where('test.plt_kode', $plt_kode)
        ->groupby('test.id')
        ->sum('nilai_test.nilai');
        $hitungpeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->join('test', 'test.id', '=', 'nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->groupBy('test.id', 'peserta.id')
            ->select('test.id', 'peserta.id')->get()->count();

        return view('admin.rekap_test', ['hitungpeserta'=>$hitungpeserta,'hitungnilai'=>$hitungnilai,'test'=>$test,'plt'=>$plt,'pelatihan' => $pelatihan, 'admin' => $admin, 'search' => $search]);
    }

    public function searchDetailTest(Request $request, $plt_kode,$test_id)
    {
        //dd($request);
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test2 = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        // Hitung nilai untuk setiap peserta pada tes tertentu
        $nilaiPeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->selectRaw('peserta.id as peserta_id, peserta.user_id, peserta.nama, SUM(nilai) as total_nilai')
            ->where('test_id', $test_id)
            ->groupBy('peserta.id', 'peserta.user_id', 'peserta.nama')
            ->get();

        //dd($nilaiPeserta);
        // Hitung jumlah peserta pada tes tertentu
        $hitungpeserta = count($nilaiPeserta);
    
        // Hitung total nilai pada tes tertentu
        $hitungnilai = Nilai_Test::where('test_id', $test_id)->sum('nilai');
        $plt = Pelatihan::join('test', 'test.plt_kode', '=', 'pelatihan.kode')
            ->join('nilai_test', 'nilai_test.test_id', '=', 'test.id')
            ->join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->where('pelatihan.kode', $plt_kode)
            ->where('test.id', $test_id)
            ->where(function ($query) use ($search) {
                $query->where('peserta.nama', 'like', '%' . $search . '%')
                    ->orWhere('peserta.user_id', 'like', '%' . $search . '%')
                    ->orWhere('test.kkm', 'like', '%' . $search . '%');
            })
            ->groupBy('peserta.id', 'test.id', 'peserta.nama', 'peserta.user_id', 'test.kkm')
            ->select('test.id', 'peserta.nama', 'peserta.user_id', 'test.kkm')
            ->get();
            
        $hitungpeserta = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->join('test', 'test.id', '=', 'nilai_test.test_id')
            ->where('test.plt_kode', $plt_kode)
            ->groupBy('test.id', 'peserta.id')
            ->select('test.id', 'peserta.id')->get()->count();
        
            $jumlahPesertaPerRentang = [
                '0-10' => 0,
                '11-20' => 0,
                '21-30' => 0,
                '31-40' => 0,
                '41-50' => 0,
                '51-60' => 0,
                '61-70' => 0,
                '71-80' => 0,
                '81-90' => 0,
                '91-100' => 0,
            ];
            
            // Loop melalui nilaiPeserta dan menghitung jumlah peserta dalam setiap rentang
            foreach ($nilaiPeserta as $score) {
                $total_nilai = $score->total_nilai;
                if ($total_nilai >= 0 && $total_nilai <= 10) {
                    $jumlahPesertaPerRentang['0-10']++;
                } elseif ($total_nilai >= 11 && $total_nilai <= 20) {
                    $jumlahPesertaPerRentang['11-20']++;
                } elseif ($total_nilai >= 21 && $total_nilai <= 30) {
                    $jumlahPesertaPerRentang['21-30']++;
                } elseif ($total_nilai >= 31 && $total_nilai <= 40) {
                    $jumlahPesertaPerRentang['31-40']++;
                } elseif ($total_nilai >= 41 && $total_nilai <= 50) {
                    $jumlahPesertaPerRentang['41-50']++;
                } elseif ($total_nilai >= 51 && $total_nilai <= 60) {
                    $jumlahPesertaPerRentang['51-60']++;
                } elseif ($total_nilai >= 61 && $total_nilai <= 70) {
                    $jumlahPesertaPerRentang['61-70']++;
                } elseif ($total_nilai >= 71 && $total_nilai <= 80) {
                    $jumlahPesertaPerRentang['71-80']++;
                } elseif ($total_nilai >= 81 && $total_nilai <= 90) {
                    $jumlahPesertaPerRentang['81-90']++;
                } elseif ($total_nilai >= 91 && $total_nilai <= 100) {
                    $jumlahPesertaPerRentang['91-100']++;
                }
            }
        

        return view('admin.detail_rekap_test', ['jumlahPesertaPerRentang'=>$jumlahPesertaPerRentang,'nilaiPeserta'=>$nilaiPeserta,'test2'=>$test2,'hitungpeserta'=>$hitungpeserta,'hitungnilai'=>$hitungnilai,'plt'=>$plt,'pelatihan' => $pelatihan, 'admin' => $admin, 'search' => $search]);
    }

}
