<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Notifikasi;
use App\Models\Attempt;
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

class SubmissionTestController extends Controller
{
    public function detailTest(String $plt_kode, String $test_id, $notif_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $maxAttempt = DB::table('attempt')
                        ->leftJoin('peserta', 'attempt.peserta_id', '=', 'peserta.id')
                        ->leftJoin('test','test.id','=','attempt.test_id')
                        ->where('peserta.user_id', Auth::user()->id)
                        ->where('test.id', $test_id)
                        ->max('attempt.attempt'); 
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->leftJoin('attempt', function($join) use ($maxAttempt) {
                    $join->on('attempt.peserta_id', '=', 'peserta.id')
                         ->where('attempt.attempt', '=', $maxAttempt);
                })
                ->where('peserta.user_id', Auth::user()->id)
                ->select('attempt.*','peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
                ->first();
        //dd($test);
        $hitungsoal = Soal_Test::where('test_id', $test_id)->count();

        $existingNilai = Attempt::where('peserta_id', Auth::user()->peserta->id)
                                ->where('test_id',$test_id)->get();

        $existing = Nilai_Test::where('test_id', $test_id)
        ->whereHas('peserta', function($query) {
            $query->where('user_id', Auth::user()->id)
                    ->where('attempt','<', 3);
        })
        ->exists();
        
        $soal_id = null;
        $soal_urutan = null;
        
        foreach ($soal_test as $soal) {
            $soal_id = $soal->id;
            $soal_urutan = $soal->id;
        }
    
        $currentQuestion = $soal_test->where('id', $soal_id)->sortBy('urutan')->first();
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
        $notification = Notifikasi::where('peserta_id', '=', Auth::user()->peserta->id)
                ->where('plt_kode', $plt_kode)->where('id',$notif_id)
                ->where('isChecked','=',0)->first();
        if($notification) {
            // Ubah isChecked menjadi 1
            $notification->isChecked = 1;
            $notification->save();
        }
        return view('peserta.detail_test_copy', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'existing'=>$existing,'hitungsoal'=>$hitungsoal,
        'existingNilai'=>$existingNilai,'pelatihan' => $pelatihan, 'test' => $test, 'peserta' => $peserta, 'soal_test' => $soal_test, 'currentQuestion' => $currentQuestion]);
    }   

    public function viewDetailTest(String $plt_kode, String $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $maxAttempt = DB::table('attempt')
                        ->leftJoin('peserta', 'attempt.peserta_id', '=', 'peserta.id')
                        ->leftJoin('test','test.id','=','attempt.test_id')
                        ->where('peserta.user_id', Auth::user()->id)
                        ->where('test.id', $test_id)
                        ->max('attempt.attempt'); 
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->leftJoin('attempt', function($join) use ($maxAttempt) {
                    $join->on('attempt.peserta_id', '=', 'peserta.id')
                         ->where('attempt.attempt', '=', $maxAttempt); // Ganti 'attempt_value' dengan nama kolom yang sesuai
                })
                ->where('peserta.user_id', Auth::user()->id)
                ->select('attempt.*','peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
                ->first();
        //dd($test);
        $hitungsoal = Soal_Test::where('test_id', $test_id)->count();

        $existingNilai = Attempt::where('peserta_id', Auth::user()->peserta->id)
                                ->where('test_id',$test_id)->get();
        //dd($existingNilai);

        $existing = Nilai_Test::where('test_id', $test_id)
        ->whereHas('peserta', function($query) {
            $query->where('user_id', Auth::user()->id)
                 ->where('attempt','<', 3);
        })
        ->exists();

        //dd($existingNilai);
        
        $soal_id = null;
        $soal_urutan = null;
        
        foreach ($soal_test as $soal) {
            $soal_id = $soal->id;
            $soal_urutan = $soal->id;
        }
    
        $currentQuestion = $soal_test->where('id', $soal_id)->sortBy('urutan')->first();
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
        return view('peserta.detail_test', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'existing'=>$existing,'hitungsoal'=>$hitungsoal,
        'existingNilai'=>$existingNilai,'pelatihan' => $pelatihan, 'test' => $test, 'peserta' => $peserta, 'soal_test' => $soal_test, 'currentQuestion' => $currentQuestion]);
    }    

    public function test($plt_kode, $test_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $nilai = Nilai_Test::leftJoin('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                            ->where('test_id', $test_id)
                            ->where('peserta.user_id', Auth::user()->id)
                            ->first();
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                            ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                            ->where('peserta.user_id', Auth::user()->id)
                            ->select('peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
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
                        
        // Periksa apakah tes tersedia
        if (!$test) {
            return redirect()->back()->with('error', 'Test tidak ditemukan.');
        }

        // Periksa apakah tes sudah dimulai
        if ($test->start_date > now()) {
            return redirect()->back()->with('error', 'Test belum dimulai.');
        }

        // Periksa apakah tes sudah berakhir (opsional)
        if ($test->end_date < now()) {
            return redirect()->back()->with('error', 'Test telah berakhir.');
        }
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();

        return view('peserta.test_2', [
            'pelatihan' => $pelatihan,
            'test' => $test,
            'total_notif'=>$total_notif,
            'nilai' => $nilai,
            'soal_test' => $soal_test,
            'jawaban_test' => $jawaban_test,
            'peserta' => $peserta,
            'notif_materi'=>$notif_materi,
            'notif_tugas'=>$notif_tugas,
            'notif_test'=>$notif_test,
            'peserta'=>$peserta
        ]);
    }



    public function submitAnswer(Request $request, $plt_kode, $test_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $peserta = Peserta::join('nilai_test', 'nilai_test.peserta_id', '=', 'peserta.id')
                    ->where('peserta.id', Auth::user()->peserta->id)
                    ->where('nilai_test.test_id', $test_id)
                    ->latest('attempt')
                    ->first();

        $attempt = $peserta ? $peserta->attempt + 1 : 1;

        foreach ($request->input('soal_id') as $urutan => $soal_id) {
            $currentQuestion = Soal_Test::find($soal_id);

            $request->validate([
                'singkat.*' => 'string|nullable', // Validasi input 'singkat' untuk setiap soal
                'selected_option.*' => 'nullable' // Validasi input 'selected_option' untuk setiap soal
            ]);

            $nilai = 0;
            $currentAnswer = null;
            $jawaban_id = null;

            if ($currentQuestion->tipe == "Jawaban Singkat") {
                $jawabanSingkatSoal = strtolower($request->input('singkat')[$urutan] ?? null);
                if ($jawabanSingkatSoal) {
                    $jawabanBenar = $currentQuestion->jawaban_test->where('status', 1)->pluck('title');
                    $nilai = in_array($jawabanSingkatSoal, $jawabanBenar->toArray()) ? $currentQuestion->nilai : 0;
                    $currentAnswer = $jawabanSingkatSoal;
                }
            } else if ($currentQuestion->tipe == "Pilihan Ganda") {
                $selectedOptionId = $request->input('selected_option.' . $urutan) ?? null;
                if ($selectedOptionId) {
                    $jawabanBenar = $currentQuestion->jawaban_test->where('status', 1)->first();
                    $nilai = ($jawabanBenar && $jawabanBenar->id == $selectedOptionId) ? $currentQuestion->nilai : 0;
                    $currentAnswer = Jawaban_Test::find($selectedOptionId);
                    $jawaban_id = $selectedOptionId;
                }
            }

            // Simpan jawaban Pilihan Ganda hanya jika ada jawaban
            if ($currentQuestion->tipe == "Pilihan Ganda" && $jawaban_id) {
                $jawabanUserPilganData = [
                    'peserta_id' => Auth::user()->peserta->id,
                    'test_id' => $test_id,
                    'soal_id' => $currentQuestion->id,
                    'jawaban_id' => $jawaban_id,
                    'jawaban' => $currentAnswer ? $currentAnswer->title : null,
                    'attempt' => $attempt
                ];

                Jawaban_User_Pilgan::create($jawabanUserPilganData);
            }

            // Simpan jawaban Jawaban Singkat
            if ($currentQuestion->tipe == "Jawaban Singkat" && $currentAnswer) {
                $jawabanUserSingkatData = [
                    'peserta_id' => Auth::user()->peserta->id,
                    'test_id' => $test_id,
                    'soal_id' => $currentQuestion->id,
                    'jawaban' => $currentAnswer,
                    'attempt' => $attempt
                ];

                Jawaban_User_Singkat::create($jawabanUserSingkatData);
            }

            // Simpan nilai untuk semua jenis soal
            Nilai_Test::create([
                'peserta_id' => Auth::user()->peserta->id,
                'test_id' => $test_id,
                'soal_id' => $currentQuestion->id,
                'nilai' => $nilai,
                'user_answers' => $currentAnswer ? json_encode($currentAnswer) : null,
                'attempt' => $attempt
            ]);
        }

        // Hitung total nilai untuk percobaan saat ini
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                ->where('test_id', $test_id)
                                ->where('peserta.user_id', Auth::user()->id)
                                ->where('attempt', $attempt)
                                ->sum('nilai');

        Attempt::Create([
            'peserta_id' => Auth::user()->peserta->id,
            'test_id' => $test_id,
            'totalnilai' => $hitungnilai,
            'attempt' => $attempt
        ]);

        return redirect()->route('peserta.hasiltest', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'hitungnilai' => $hitungnilai])->with('success', 'Jawaban berhasil disubmit');
    }




    public function hasil(Request $request, $plt_kode, $test_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $latestAttempt = Peserta::join('nilai_test', 'nilai_test.peserta_id', '=', 'peserta.id')
                ->where('peserta.user_id', Auth::user()->id)
                ->where('nilai_test.test_id', $test_id)
                ->max('nilai_test.attempt');
        //dd($request);
        $nilai = Nilai_Test::leftJoin('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                    ->where('test_id', $test_id)
                    ->where('peserta.user_id', Auth::user()->id)
                    ->where('attempt', $latestAttempt)
                    ->get();

        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->where('peserta.user_id', Auth::user()->id)
            ->select('peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
            ->first();
        $hitungsoal = Soal_Test::where('test_id', $test_id)->count();
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->where('test_id', $test_id)
            ->where('peserta.user_id', Auth::user()->id)
            ->where('nilai_test.attempt', $latestAttempt)
            ->sum('nilai'); // Assuming 'nilai' is the column you want to sum
        $jawabBenar = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                ->where('test_id', $test_id)
                                ->where('attempt', $latestAttempt)
                                ->where('peserta.user_id', Auth::user()->id)
                                ->where('nilai','!=', 0)->count();
        $jawabSingkat = Jawaban_User_Singkat::where('test_id',$test_id)->where('attempt', $latestAttempt)->get();
        $jawabPilgan = Jawaban_User_Pilgan::where('test_id',$test_id)->where('attempt', $latestAttempt)->get();
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
        return view('peserta.hasil_test', [
            'pelatihan'=>$pelatihan,
            'notif_materi'=>$notif_materi,
            'notif_tugas'=>$notif_tugas,
            'notif_test'=>$notif_test,
            'peserta'=>$peserta,
            'total_notif'=>$total_notif,
            'jawabSingkat'=>$jawabSingkat,
            'jawabPilgan'=>$jawabPilgan,
            'hitungsoal'=>$hitungsoal,
            'test'=>$test,
            'soal_test'=>$soal_test,
            'jawaban_test'=>$jawaban_test,
            'nilai'=>$nilai,
            'peserta'=>$peserta,
            'hitungnilai'=>$hitungnilai,
            'jawabBenar'=>$jawabBenar,
        ]);
    }

}
