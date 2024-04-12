<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta_Pelatihan;
use App\Models\Peserta;
use App\Models\Admin;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Nilai_Test;
use App\Models\Jawaban_User_Pilgan;
use App\Models\Jawaban_User_Singkat;
use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertaImport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PesertaRegistered;
use App\Models\Attempt;
use App\Models\Materi;
use App\Models\Test;
use App\Models\Tugas;
use Barryvdh\DomPDF\PDF;

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

            $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.id', '=', Auth::user()->peserta->id)
                ->where('status','=','On going')
                ->get();

            foreach ($kode as $kd){
                $pltkode = $kd->kode;
                $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
                $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                    ->where('notifikasi.judul', '=', 'Tugas')
                                    ->where('notifikasi.plt_kode', $pltkode)
                                    ->where('start_date','<=',now())
                                    ->where('peserta_id', '=', Auth::user()->peserta->id)
                                    ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                    ->where(function($query) {
                                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                    })
                                    ->get();
                $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                    ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                    ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                    ->where(function($query) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                    })
                    ->get();
            }
            $total_notif = $notif_materi->where('isChecked', 0)->count() + 
               $notif_tugas->where('isChecked', 0)->count() + 
               $notif_test->where('isChecked', 0)->count();
            //dd($total_notif, $notif_materi, $notif_test, $notif_tugas);
            // return view('peserta.dashboard',['total_notif'=>$total_notif,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'peserta'=>$peserta, 'pelatihan'=>$pelatihan]);

            $last_accessed = DB::table('peserta_pelatihan')
            ->where('peserta_id', $peserta->id)
            ->orderBy('last_accessed', 'desc')
            ->take(3)
            ->get();

            $last_accessed_pelatihan = [];

            foreach ($last_accessed as $item) {
                $pelatihan = Pelatihan::where('kode', $item->plt_kode)->first();
                if ($pelatihan) {
                    $last_accessed_pelatihan[] = $pelatihan;
                }
            }

            return view('peserta.dashboard', ['peserta' => $peserta, 'pelatihan' => $pelatihan, 'last_accessed_pelatihan' => $last_accessed_pelatihan,
            'total_notif'=>$total_notif,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test]);
        }
    }

    public function viewDaftarPelatihan() {
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();

        $pelatihan = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->where('peserta_pelatihan.peserta_id', $peserta->id)
                ->where('status', 'On going')
                ->get();

                $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.id', '=', Auth::user()->peserta->id)
                ->where('status','=','On going')
                ->get();

        foreach ($kode as $kd){
            $pltkode = $kd->kode;
            $notif_materi = Notifikasi::where('judul','=','Materi')->where('isChecked','=',0)->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
            $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                ->where('notifikasi.judul', '=', 'Tugas')
                                ->where('start_date','<=',now())
                                ->where('notifikasi.plt_kode', $pltkode)
                                ->where('peserta_id', '=', Auth::user()->peserta->id)
                                ->select('notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                ->where(function($query) {
                                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                })
                                ->get();
            $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                ->select('notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                ->where(function($query) {
                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                })
                ->get();
        }
        $total_notif = count($notif_materi) + count($notif_tugas) + count($notif_test);
    
        return view('peserta.daftar_pelatihan', ['peserta'=>$peserta, 'pelatihan' => $pelatihan, 'total_notif'=>$total_notif,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test]);
    }

    public function viewHistoryPelatihan() {
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();

        $pelatihan = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->where('peserta_pelatihan.peserta_id', $peserta->id)
                ->where('status', 'Completed')
                ->get();

                $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.id', '=', Auth::user()->peserta->id)
                ->where('status','=','On going')
                ->get();

        foreach ($kode as $kd){
            $pltkode = $kd->kode;
            $notif_materi = Notifikasi::where('judul','=','Materi')->where('isChecked','=',0)->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
            $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                ->where('notifikasi.judul', '=', 'Tugas')
                                ->where('start_date','<=',now())
                                ->where('notifikasi.plt_kode', $pltkode)
                                ->where('peserta_id', '=', Auth::user()->peserta->id)
                                ->select('notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                ->where(function($query) {
                                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                })
                                ->get();
            $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                ->select('notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                ->where(function($query) {
                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                })
                ->get();
        }
        $total_notif = count($notif_materi) + count($notif_tugas) + count($notif_test);
    
        return view('peserta.history_pelatihan', ['peserta'=>$peserta, 'pelatihan' => $pelatihan, 'total_notif'=>$total_notif,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test]);
    }

    public function hapusNotif($notif_id)
    {
        // Mendapatkan peserta_id dari user saat ini
        $peserta_id = Auth::user()->peserta->id;

        // Menghapus notifikasi berdasarkan id dan peserta_id
        Notifikasi::where('id', $notif_id)
            ->where('peserta_id', $peserta_id)
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
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
        $pst = User::leftjoin('peserta','peserta.user_id','=','users.id')
                    ->where('role_id', '=', 2)->select('users.id','nama')->get();
        $pelatihan = Pelatihan::select('kode','nama','status')->where('status','!=','Completed')->get();
        return view('admin.daftar_peserta', ['admin' => $admin, 'peserta' => $peserta,'pst'=>$pst,'pelatihan'=>$pelatihan]);
    }

    public function detailPelatihan($plt_kode, $notif_id){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $peserta = Peserta::where('user_id', Auth::user()->id)->first();
        
        $totalNilaiTes = [];
        $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.id', '=', Auth::user()->peserta->id)
                ->where('status','=','On going')
                ->get();
                foreach ($kode as $kd){
                    $pltkode = $kd->kode;
                    $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
                    $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                        ->where('notifikasi.judul', '=', 'Tugas')
                                        ->where('notifikasi.plt_kode', $pltkode)
                                        ->where('start_date','<=',now())
                                        ->where('peserta_id', '=', Auth::user()->peserta->id)
                                        ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                        ->where(function($query) {
                                            $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                                ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                        })
                                        ->get();
                    $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                        ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                        ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                        ->where(function($query) {
                            $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                                ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                        })
                        ->get();
                }
                $total_notif = $notif_materi->where('isChecked', 0)->count() + 
                   $notif_tugas->where('isChecked', 0)->count() + 
                   $notif_test->where('isChecked', 0)->count();
        $notification = Notifikasi::where('peserta_id', '=', Auth::user()->peserta->id)
                            ->where('plt_kode', $plt_kode)->where('id',$notif_id)
                            ->where('isChecked','=',0)->first();
        // Periksa apakah notifikasi ditemukan
        if($notification) {
            // Ubah isChecked menjadi 1
            $notification->isChecked = 1;
            $notification->save();
        }
        //dd($notification);
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();
        $doneTest = 0;
        foreach ($test as $tes) {
            $nilaiPeserta = Attempt::where('test_id', $tes->id)
                ->where('peserta_id', $peserta->id)
                ->select('totalnilai')
                ->get();

            foreach ($nilaiPeserta as $nilai) {
                // Memeriksa apakah nilai peserta lebih besar dari atau sama dengan KKM
                if ($nilai->totalnilai >= $tes->kkm) {
                    $doneTest++;
                    break; // Keluar dari loop karena tes sudah selesai
                }
            }
        
            $totalNilaiTes[$tes->id] = $nilaiPeserta->toArray();
        }
    
        $doneTugas = 0;
        foreach ($tugas as $tgs) {
            if ($tgs->submissions()->where('peserta_id', $peserta->id)->first()) {
                $doneTugas++;
            }
        }
    
        $completed = false;
        if ($doneTugas == count($tugas) && $doneTest == count($test)) {
            $completed = true;
        }
    
        return view('peserta.detail_pelatihan', [
            'pelatihan' => $pelatihan,
            'materi' => $materi, 
            'notif_materi'=>$notif_materi,
            'notif_tugas'=>$notif_tugas,
            'notif_test'=>$notif_test,
            'tugas' => $tugas, 
            'test' => $test, 
            'peserta' => $peserta,
            'totalNilaiTes' => $totalNilaiTes,
            'completed' => $completed,
            'peserta'=>$peserta,
            'total_notif'=>$total_notif
        ]);
    }

    public function viewDetailPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        // $peserta_pelatihan = Peserta_Pelatihan::where('plt_kode', $plt_kode)->where('peserta_id', Auth::user()->peserta->id)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $peserta = Peserta::where('user_id', Auth::user()->id)->first();
        // dd($peserta_pelatihan);
        DB::table('peserta_pelatihan')
        ->where('peserta_id', Auth::user()->peserta->id)
        ->where('plt_kode', $plt_kode)
        ->update(['last_accessed' => now()]);


        $totalNilaiTes = [];
        $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
                ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.id', '=', Auth::user()->peserta->id)
                ->where('status','=','On going')
                ->get();
                foreach ($kode as $kd){
                    $pltkode = $kd->kode;
                    $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
                    $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                        ->where('notifikasi.judul', '=', 'Tugas')
                                        ->where('notifikasi.plt_kode', $pltkode)
                                        ->where('start_date','<=',now())
                                        ->where('peserta_id', '=', Auth::user()->peserta->id)
                                        ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                        ->where(function($query) {
                                            $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                                ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                        })
                                        ->get();
                    $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                        ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                        ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                        ->where(function($query) {
                            $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                                ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                        })
                        ->get();
                }
                $total_notif = $notif_materi->where('isChecked', 0)->count() + 
                   $notif_tugas->where('isChecked', 0)->count() + 
                   $notif_test->where('isChecked', 0)->count();
        
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();
        $doneTest = 0;
        foreach ($test as $tes) {
            $nilaiPeserta = Attempt::where('test_id', $tes->id)
                ->where('peserta_id', $peserta->id)
                ->select('totalnilai')
                ->get();

            foreach ($nilaiPeserta as $nilai) {
                // Memeriksa apakah nilai peserta lebih besar dari atau sama dengan KKM
                if ($nilai->totalnilai >= $tes->kkm) {
                    $doneTest++;
                    break; // Keluar dari loop karena tes sudah selesai
                }
            }
        
            $totalNilaiTes[$tes->id] = $nilaiPeserta->toArray();
        }
    
        $doneTugas = 0;
        foreach ($tugas as $tgs) {
            if ($tgs->submissions()->where('peserta_id', $peserta->id)->first()) {
                $doneTugas++;
            }
        }
    
        $completed = false;
        if ($doneTugas == count($tugas) && $doneTest == count($test)) {
            $completed = true;
        }
    
        return view('peserta.detail_pelatihan', [
            'pelatihan' => $pelatihan,
            'materi' => $materi, 
            'notif_materi'=>$notif_materi,
            'notif_tugas'=>$notif_tugas,
            'notif_test'=>$notif_test,
            'tugas' => $tugas, 
            'test' => $test, 
            'peserta' => $peserta,
            'totalNilaiTes' => $totalNilaiTes,
            'completed' => $completed,
            'peserta'=>$peserta,
            'total_notif'=>$total_notif
        ]);
    }
    
    public function detail_peserta($peserta_id)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->where('peserta.user_id',$peserta_id)
            ->select('peserta.user_id as peserta_id','peserta.nama as peserta_nama', 'peserta.noHP', 'peserta.alamat', 'users.foto', 'users.password_awal', 'peserta.id', 'users.username', 'users.email')
            ->first();
        $pst = User::where('role_id', '=', 2)->select('id')->get();
        $pelatihan = Pelatihan::select('kode','nama')->get();
        $pstplt = Peserta_Pelatihan::join('peserta','peserta.id','=','peserta_pelatihan.peserta_id')
                                    ->join('users','users.id','=','peserta.user_id')
                                    ->join('pelatihan','pelatihan.kode','=','peserta_pelatihan.plt_kode')
                                    ->where('peserta.user_id', $peserta_id)
                                    ->select('plt_kode','pelatihan.nama')
                                    ->get();
        //dd($pstplt);
        return view('admin.detail_peserta', ['pstplt'=>$pstplt,'admin' => $admin, 'peserta' => $peserta,'pst'=>$pst,'pelatihan'=>$pelatihan]);
    }

    public function create()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->select('peserta.user_id as peserta_id','peserta.nama as peserta_nama', 'peserta.noHP', 'peserta.alamat', 'users.foto', 'users.password_awal', 'peserta.id', 'users.username', 'users.email')
            ->get();
        $pst = User::where('role_id', '=', 2)->select('id')->get();
        $pelatihan = Pelatihan::select('kode','nama')->get();
        return view('admin.tambah_peserta', ['admin' => $admin, 'peserta' => $peserta,'pst'=>$pst,'pelatihan'=>$pelatihan]);
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
        $pelatihan = Pelatihan::select('kode','nama')->get();
        $pst = User::leftjoin('peserta','peserta.user_id','=','users.id')
                    ->where('role_id', '=', 2)->select('users.id','nama')->get();
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

        return view('admin.daftar_peserta', ['pelatihan'=>$pelatihan,'pst'=>$pst,'peserta' => $peserta, 'admin' => $admin, 'search' => $search]);
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
                            ->select('peserta.id as id', 'user_id','nama','username', 'email',
                            'noHP','alamat','password','password_awal','foto')
                            ->first();
        //dd($peserta);
        if (!$peserta) {
            return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Tidak dapat menemukan peserta yang ingin diedit.');
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
                'id' =>$peserta->id,
                'nama' => $validated['nama'],
                'noHP' => $validated['noHP'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ];
            //dd($updateData);
            $peserta->update(array_filter($updateData));
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
                ->select('peserta.user_id', 'users.id', 'peserta.nama', 'pelatihan.status', 'peserta_pelatihan.plt_kode','peserta_id')
                ->first();

            $submission = Submission::join('submission_files','submission_files.submission_id','=','submissions.id')
                ->where('peserta_id', $peserta_pelatihan->peserta_id)
                ->get();
            $jawabpilgan = Jawaban_User_Pilgan::where('peserta_id', $peserta_pelatihan->peserta_id)->get(); 
            $jawabsingkat =  Jawaban_User_Singkat::where('peserta_id', $peserta_pelatihan->peserta_id)->get(); 
            $attempt =  Attempt::where('peserta_id', $peserta_pelatihan->peserta_id)->get(); 
            $nilai = Nilai_Test::where('peserta_id', $peserta_pelatihan->peserta_id)->get(); 
            $notif = Notifikasi::where('peserta_id', $peserta_pelatihan->peserta_id)->get(); 
            //dd($peserta_pelatihan);
            DB::beginTransaction();

            try {
                if ($peserta_pelatihan && $peserta_pelatihan->status == 'On going') { // Check if $peserta_pelatihan exists before accessing its properties
                    return redirect()->route('admin.viewDaftarPeserta')->with('error', 'Tidak dapat menghapus peserta dengan pelatihan yang masih berlangsung.');
                }
                
                if ($peserta_pelatihan != null) {
                    foreach ($submission as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    foreach ($jawabpilgan as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    foreach ($jawabsingkat as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    foreach ($attempt as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    foreach ($nilai as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    foreach ($notif as $sub) { // Loop through submission to delete each submission
                        $sub->delete();
                    }
                    Peserta_Pelatihan::where('peserta_id',$peserta_pelatihan->peserta_id)->delete();
                }

                // Peserta deletion should be inside the condition where $peserta_pelatihan is not null
                Peserta::where('user_id', $peserta_id)->delete(); // Menghapus peserta berdasarkan user_id
                User::where('id', $peserta_id)->delete(); // Menghapus user berdasarkan id

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

    public function tambah()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $peserta = Peserta::select('user_id', 'nama', 'noHP', 'alamat')
            ->whereNull('user_id')
            ->get();

        return view('admin.import_peserta', compact('admin', 'peserta'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        $data = Excel::toArray(new PesertaImport(), $request->file('file'));
        
        $errors = [];
        foreach ($data[0] as $row) {
            $validator = Validator::make($row, [
                'nama' => 'required|regex:/^[a-zA-Z\s]*$/',
                'nohp' => 'required',
                'alamat' => 'required',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        // Check if the email already exists in the users table
                        if (User::where('email', $value)->exists()) {
                            return redirect()
                                ->back()
                                ->with('error', 'Email peserta sudah terdaftar');
                        }
                    }
                ],
            ]);

            if ($validator->fails()) {
                $errors[] = $validator->errors()->all();
            }
        }

        if (!empty($errors)) {
            return redirect()
                ->back()
                ->with('errors', $errors)
                ->withInput();
        }

        // Flash the data for a single request
        session()->put('peserta_data', $data[0]);

        return redirect()->route('admin.previewPeserta');
    }



    public function preview()
    {
        $data = session()->get('peserta_data');
        
        // If there is no data, you might want to handle it accordingly (e.g., redirect back with an error message)
        if (!$data) {
            return redirect()->route('admin.tambahPeserta')->with('error', 'Tidak ada data yang ingin di generate');
        }

        return view('admin.import_peserta', ['data' => $data]);
    }

    public function clearSessionData()
    {
        session()->forget('peserta_data');
        return redirect()->route('admin.previewPeserta')->with('success', 'Session data telah dihapus');
    }

    public function generateAkun()
    {
        $data = session()->get('peserta_data');
        //dd($data);
        // If there is no data, you might want to handle it accordingly (e.g., redirect back with an error message)
        if (!$data) {
            return redirect()->route('admin.tambahPeserta')->with('error', 'Tidak ada data yang ingin di generate');
        }
        
        foreach ($data as $row) {
            if (array_key_exists('user_id', $row) && Peserta::where('user_id', $row['user_id'])->exists()) {
                return redirect()
                    ->route('admin.preview')
                    ->with('error', 'Peserta sudah terdaftar');
            } else {
                $email = $row['email'];
                $noHP = $row['nohp'];
                $alamat = $row['alamat'];
                $nama = $row['nama'];
                $originalUsername = strtolower(str_replace(' ', '', $row['nama']));
                $username = strlen($originalUsername) > 10 ? substr($originalUsername, 0, 10) : $originalUsername;
        
                // Check for uniqueness and append a unique identifier if needed
                while (User::where('username', $username)->exists()) {
                    $username .= '_' . uniqid();
                }
        
                $password = Str::random(8);
        
                $user = User::create([
                    'email' => $email,
                    'username' => $username,
                    'password' => $password,
                    'password_awal' => $password, 
                    'role_id' => 2,
                ]);
        
                // Check if 'user_id' key exists before attempting to use it
                if (array_key_exists('user_id', $row)) {
                    $row['user_id'] = $user->id;
                }
        
                $row['noHP'] = $noHP;
                $row['alamat'] = $alamat;
                $row['nama'] = $nama;
                Peserta::create([
                    'noHP' => $noHP,
                    'alamat' => $alamat,
                    'nama' => $nama,
                    'user_id' => $user->id
                ]);
            }
        }        
        return redirect()
            ->route('admin.viewDaftarPeserta')
            ->with('success', 'Data peserta berhasil ditambahkan');
    }

    public function export(Request $request)
    {

        $request->validate([
            'export_option' => 'required',
            'start_user_id' => 'required_if:export_option,range|exists:users,id',
            'end_user_id' => 'required_if:export_option,range|exists:users,id', 'after_or_equal:start_user_id',
        ]);
        $pst = User::where('role_id', '=', 2)->select('id')->get();
        $user_id = $request->input('user_id');
        $exportOption = $request->input('export_option');
        $startUserId = $request->input('start_user_id');
        $endUserId = $request->input('end_user_id');

        $query = Peserta::join('users', 'peserta.user_id', '=', 'users.id')
            ->select('nama', 'user_id', 'noHP', 'alamat', 'username', 'password_awal', 'email');

        // Filter berdasarkan opsi yang dipilih
        if ($exportOption === 'all') {
            // Tidak ada filter tambahan
        } elseif ($exportOption === 'range' && $startUserId && $endUserId) {
            // Filter berdasarkan rentang user_id
            $query->whereBetween('user_id', [$startUserId, $endUserId]);
        } else {
            // Opsi tidak valid, mungkin tambahkan penanganan kesalahan di sini
        }

        $peserta = $query->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_peserta', ['peserta' => $peserta, 'pst' => $pst]);

        // Nama file PDF disesuaikan dengan opsi yang dipilih
        $filename = $exportOption === 'range' ? 'daftar-list-peserta-range.pdf' : 'daftar-list-peserta.pdf';

        return $pdf->stream($filename);
    }

    public function sendEmail(Request $request)
    {
        // Validasi input
        $request->validate([
            'subjek' => 'required|string',
            'deliver_option' => 'required|in:all,range',
            'start_user_id' => 'required_if:deliver_option,range|exists:users,id',
            'end_user_id' => 'required_if:deliver_option,range|exists:users,id', 'after_or_equal:start_user_id',
            'kode' => 'required|exists:pelatihan,kode',
        ]);
        //dd($request);
        $fromName = 'Dinas Kominfo Kota Semarang';
        $subjek = $request->input('subjek');
        $kode = $request->input('kode');

        $exportOption = $request->input('deliver_option');
        $startUserId = $request->input('start_user_id');
        $endUserId = $request->input('end_user_id');

        $users = null;

        if ($exportOption === 'all') {
            // Ambil semua user dengan role_id = 2
            $users = User::where('role_id', '=', 2)->get();
        } elseif ($exportOption === 'range' && $startUserId && $endUserId) {
            // Ambil user dalam rentang tertentu
            $users = User::where('role_id', '=', 2)
                ->whereBetween('id', [$startUserId, $endUserId])
                ->get();
        } else {
            return redirect()->back()->with('error', 'Opsi pengiriman tidak valid.');
        }

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih.');
        }

        // Loop melalui pengguna dan kirim email
        foreach ($users as $user) {
            $username = $user->username;
            $password = $user->password_awal;
            $toAddress = $user->email;
            
            Mail::to($toAddress)
                ->send(new PesertaRegistered($username, $password, $kode,$fromName, $subjek));
        }

        return redirect()->back()->with('success', 'Berhasil mengirim email');
    }

    public function generateSertifikat(Request $request) {
        return view('peserta.sertifikat');
    }

    public function cetakSertifikat($plt_kode, $peserta_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $peserta = Peserta::where('id', $peserta_id)->first();

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'landscape'); // Set ukuran kertas ke A4 dengan orientasi landscape
        $pdf->loadView('peserta.sertifikat', ['pelatihan' => $pelatihan, 'peserta' => $peserta]);

        
        return $pdf->stream('sertifikat.pdf');
    }

    public function ubahPassword(){
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                    ->where('peserta.user_id', Auth::user()->id)
                    ->first();

        $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
            ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->where('peserta.id', '=', Auth::user()->peserta->id)
            ->where('status','=','On going')
            ->get();
            foreach ($kode as $kd){
                $pltkode = $kd->kode;
                $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
                $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                    ->where('notifikasi.judul', '=', 'Tugas')
                                    ->where('notifikasi.plt_kode', $pltkode)
                                    ->where('start_date','<=',now())
                                    ->where('peserta_id', '=', Auth::user()->peserta->id)
                                    ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                    ->where(function($query) {
                                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                    })
                                    ->get();
                $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                    ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                    ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                    ->where(function($query) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                    })
                    ->get();
            }
            $total_notif = $notif_materi->where('isChecked', 0)->count() + 
               $notif_tugas->where('isChecked', 0)->count() + 
               $notif_test->where('isChecked', 0)->count();
    
        return view('peserta.ubah_password', compact('total_notif','peserta','notif_materi','notif_tugas','notif_test'));
    }
    

    public function updatePassword(Request $request, $peserta_id){
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8|string',
            'conf_password' => 'required|same:new_password',
        ]);
    
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                    ->where('peserta.user_id', Auth::user()->id)
                    ->where('user_id',$peserta_id)
                    ->first();
    
        if (!$peserta) {
            return redirect()->back()->with('error', 'Peserta not found');
        }
    
        // Verifikasi password yang dimasukkan dengan password_awal
        if (!Hash::check($request->input('password'), $peserta->password)) {
            return redirect()->back()->with('error', 'Password tidak cocok');
        }
    
        try{
            DB::beginTransaction();
    
            $updateData = [];
    
            if ($request->has('new_password')) {
                $updateData['password'] = Hash::make($request->input('new_password'));
                $updateData['password_awal'] = $request->input('new_password');
            }
    
            $peserta->user()->update($updateData);
    
            DB::commit();
    
            return redirect()->back()->with('success','Password berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error','Gagal memperbarui password');
        }
    }

    public function profil(){
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                    ->where('peserta.user_id', Auth::user()->id)
                    ->first();
                    
        $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
            ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->where('peserta.id', '=', Auth::user()->peserta->id)
            ->where('status','=','On going')
            ->get();
            foreach ($kode as $kd){
                $pltkode = $kd->kode;
                $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
                $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                    ->where('notifikasi.judul', '=', 'Tugas')
                                    ->where('notifikasi.plt_kode', $pltkode)
                                    ->where('start_date','<=',now())
                                    ->where('peserta_id', '=', Auth::user()->peserta->id)
                                    ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                    ->where(function($query) {
                                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                    })
                                    ->get();
                $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                    ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                    ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                    ->where(function($query) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                    })
                    ->get();
            }
            $total_notif = $notif_materi->where('isChecked', 0)->count() + 
               $notif_tugas->where('isChecked', 0)->count() + 
               $notif_test->where('isChecked', 0)->count();
        return view('peserta.profil', compact('peserta','total_notif','notif_materi','notif_tugas','notif_test'));
    }

    public function editprofil(){
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                    ->where('peserta.user_id', Auth::user()->id)
                    ->first();
        $kode = Pelatihan::join('peserta_pelatihan', 'pelatihan.kode', '=', 'peserta_pelatihan.plt_kode')
        ->join('peserta', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
        ->where('peserta.id', '=', Auth::user()->peserta->id)
        ->where('status','=','On going')
        ->get();
        foreach ($kode as $kd){
            $pltkode = $kd->kode;
            $notif_materi = Notifikasi::where('judul','=','Materi')->where('plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)->get();
            $notif_tugas = Notifikasi::join('tugas', 'tugas.plt_kode', '=', 'notifikasi.plt_kode')
                                ->where('notifikasi.judul', '=', 'Tugas')
                                ->where('notifikasi.plt_kode', $pltkode)
                                ->where('start_date','<=',now())
                                ->where('peserta_id', '=', Auth::user()->peserta->id)
                                ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                ->where(function($query) {
                                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                })
                                ->get();
            $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')->where('start_date','<=',now())
                ->where('notifikasi.judul','=','Test')->where('notifikasi.plt_kode',$pltkode)->where('peserta_id', '=', Auth::user()->peserta->id)
                ->select('isChecked','notifikasi.plt_kode','notifikasi.id as notif_id','subjudul','notifikasi.judul','test.id as test_id')
                ->where(function($query) {
                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = test.nama")
                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = test.nama");
                })
                ->get();
        }
        $total_notif = $notif_materi->where('isChecked', 0)->count() + 
           $notif_tugas->where('isChecked', 0)->count() + 
           $notif_test->where('isChecked', 0)->count();
        return view('peserta.edit_profil', compact('total_notif','peserta','notif_materi','notif_tugas','notif_test'));
    }

    // public function updateProfil(Request $request, $peserta_id){
    //     // dd($peserta_id);
    //     $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
    //                 ->where('peserta.user_id', Auth::user()->id)
    //                 ->where('user_id',$peserta_id)
    //                 ->first();
    //     // dd($peserta->nama);
    //     $validated = $request->validate([
    //         'username' => ['required'],
    //         'email' => ['required', 'email'],
    //         'noHP' => ['required', 'numeric'],
    //         'alamat' => ['required'],
    //         'foto' => [ 'max:10240'],
    //     ]);
    //     //dd($validated);
    //     try {
    //         DB::beginTransaction();

    //         $updateData = [
    //             'noHP' => $validated['noHP'] ?? null,
    //             'alamat' => $validated['alamat'] ?? null,
    //         ];
    //         //dd($updateData);
    //         $peserta->update(array_filter($updateData));
    //         dd($peserta);
    //         $updateData2 = [
    //             'username' => $validated['username'] ?? null,
    //             'email' => $validated['email'] ?? null,
    //             'foto' => $validated['foto'] ?? null,
    //         ];

    //         if ($request->has('foto')) {
    //             $fotoPath = $request->file('foto')->store('foto', 'public');
    //             $updateData2['foto'] = $fotoPath;
    //         }

    //         $peserta->user->update(array_filter($updateData2));

    //         DB::commit();

    //         return redirect()
    //             ->route('peserta.profil')
    //             ->with('success', 'Data user berhasil diperbarui');
    //     } catch (\Exception $e) {
    //         dd($e->getMessage());
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'Gagal memperbarui data user');
    //     }
    // }

    public function updateProfil(Request $request, $peserta_id){
        $peserta = Peserta::where('peserta.user_id', Auth::user()->id)
                    ->first();

        $validated = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'noHP' => ['required', 'numeric'],
            'alamat' => ['required'],
            'foto' => [ 'max:10240'],
        ]);
    
        try {
            DB::beginTransaction();

            $updateData = [
                            'noHP' => $validated['noHP'] ?? null,
                            'alamat' => $validated['alamat'] ?? null,
                        ];
            $peserta->update(array_filter($updateData));
            //dd($peserta);
            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'foto' => $validated['foto'] ?? null,
            ];

            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $updateData2['foto'] = $fotoPath;
            }

            $peserta->user->update(array_filter($updateData2));
            // dd($peserta->user);
            DB::commit();

            return redirect()
                ->route('peserta.profil')
                ->with('success', 'Data peserta berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data peserta');
        }
    }
}
