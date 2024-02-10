<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Test;
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
    public function viewDetailTest(String $plt_kode, String $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
                ->first();
        //dd($test);
        $hitungsoal = Soal_Test::where('test_id', $test_id)->count();
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->where('test_id', $test_id)
            ->where('peserta.user_id', Auth::user()->id)
            ->sum('nilai'); // Assuming 'nilai' is the column you want to sum
        $jawabBenar = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                                    ->where('test_id', $test_id)
                                                    ->where('peserta.user_id', Auth::user()->id)
                                                    ->where('nilai','!=', 0)->count();
        $existingNilai = Nilai_Test::where('test_id', $test_id)
        ->whereHas('peserta', function($query) {
            $query->where('user_id', Auth::user()->id);
        })
        ->exists();
        
        $soal_id = null;
        $soal_urutan = null;
        
        foreach ($soal_test as $soal) {
            $soal_id = $soal->id;
            $soal_urutan = $soal->id;
        }
    
        $currentQuestion = $soal_test->where('id', $soal_id)->sortBy('urutan')->first();
        
    
        return view('peserta.detail_test', ['hitungsoal'=>$hitungsoal,
        'hitungnilai'=>$hitungnilai,
        'jawabBenar'=>$jawabBenar,'existingNilai'=>$existingNilai,'pelatihan' => $pelatihan, 'test' => $test, 'peserta' => $peserta, 'soal_test' => $soal_test, 'currentQuestion' => $currentQuestion]);
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

        $existingNilai = Nilai_Test::where('test_id', $test_id)
                                    ->whereHas('peserta', function($query) {
                                        $query->where('user_id', Auth::user()->id);
                                    })
                                    ->exists();

        // Jika sudah ada nilai untuk tes ini, kembalikan pesan error
        if ($existingNilai) {
            return redirect()->back()->with('error', 'Anda sudah mengerjakan tes ini.');
        }

        return view('peserta.test_2', [
            'pelatihan' => $pelatihan,
            'test' => $test,
            'existingNilai' => $existingNilai,
            'nilai' => $nilai,
            'soal_test' => $soal_test,
            'jawaban_test' => $jawaban_test,
            'peserta' => $peserta,
        ]);
    }



    public function submitAnswer(Request $request, $plt_kode, $test_id)
    {
        foreach ($request->input('soal_id') as $urutan => $soal_id) {
            // Ambil satu per satu soal berdasarkan ID
            $currentQuestion = Soal_Test::find($soal_id);
            //dd($request);
            if ($currentQuestion->tipe == "Jawaban Singkat") {
                $jawabanSingkat = $request->input('singkat');
                $jawabanSingkatSoal = $jawabanSingkat[$urutan] ?? null;
            }else{
                $selectedOptionId = $request->input('selected_option.' . $urutan);
                //dd($selectedOptionId);
            }

            // Setelah menyimpan ke sesi, lakukan penyimpanan ke dalam database
            $test = Test::find($test_id);
            $question_number = $urutan;

            // Pastikan nomor pertanyaan berada dalam rentang yang valid
            if ($question_number < 1 || $question_number > $test->soal_test->count()) {
                abort(404); // or handle it as appropriate for your application
            }

            // Inisialisasi nilai awal
            $nilai = 0;
            $currentAnswer = null;

            // Lakukan logika berdasarkan tipe soal untuk setiap soal
            if ($currentQuestion->tipe == "Pilihan Ganda") {
                $jawabanBenar = $currentQuestion->jawaban_test->where('status', 1)->first();
            
                $nilai = ($jawabanBenar && $jawabanBenar->id == $selectedOptionId) ? $currentQuestion->nilai : 0;
                //dd($jawabanBenar, $selectedOptionId, $nilai);
                $currentAnswer = Jawaban_Test::find($selectedOptionId);

                Jawaban_User_Pilgan::updateOrCreate(
                    [
                        'peserta_id' => Auth::user()->peserta->id,
                        'test_id' => $test_id,
                        'soal_id' => $currentQuestion->id,
                    ],
                    [
                        'jawaban_id' => $currentAnswer ? $currentAnswer->id : null,
                        'jawaban' => $currentAnswer ? $currentAnswer->title : null,
                    ]

                );
                // Simpan nilai ke dalam tabel Nilai_Test
                Nilai_Test::updateOrCreate(
                    [
                        'peserta_id' => Auth::user()->peserta->id,
                        'test_id' => $test_id,
                        'soal_id' => $currentQuestion->id,
                    ],
                    [
                        'nilai' => $nilai,
                        'user_answers' => json_encode($currentAnswer ? $currentAnswer->title : null), // Ensure proper encoding
                    ]
                );
            } elseif ($currentQuestion->tipe == "Jawaban Singkat") {
                // Ambil semua jawaban benar terkait dengan soal
                $jawabanBenar = $currentQuestion->jawaban_test->where('status', 1)->pluck('title');
            
                // Periksa apakah jawaban singkat pengguna cocok dengan salah satu dari jawaban benar
                if (in_array($jawabanSingkatSoal, $jawabanBenar->toArray())) {
                    // Tandai jawaban sebagai benar dan atur nilai
                    $nilai = $currentQuestion->nilai;
                    $currentAnswer = $jawabanSingkatSoal; // Jawaban singkat pengguna yang benar
                } else {
                    // Jika jawaban tidak cocok, nilai tetap 0
                    $nilai = 0;
                    $currentAnswer = $jawabanSingkatSoal;
                }
            
                Jawaban_User_Singkat::updateOrCreate(
                    [
                        'peserta_id' => Auth::user()->peserta->id,
                        'test_id' => $test_id,
                        'soal_id' => $currentQuestion->id,
                    ],
                    [
                        'jawaban' => $jawabanSingkatSoal, // Simpan jawaban singkat langsung
                    ]
                );
                // Simpan nilai ke dalam tabel Nilai_Test
                Nilai_Test::updateOrCreate(
                    [
                        'peserta_id' => Auth::user()->peserta->id,
                        'test_id' => $test_id,
                        'soal_id' => $currentQuestion->id,
                    ],
                    [
                        'nilai' => $nilai,
                        'user_answers' => json_encode($currentAnswer), // Ensure proper encoding
                    ]
                );
            }    
        }

        return redirect()->route('peserta.hasil', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id])->with('success', 'Jawaban berhasil disubmit');
    }

    public function hasil($plt_kode, $test_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $nilai = Nilai_Test::leftjoin('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')->where('test_id', $test_id)->where('peserta.user_id', Auth::user()->id)->get();
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->where('peserta.user_id', Auth::user()->id)
            ->select('peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
            ->first();
        $hitungsoal = Soal_Test::where('test_id', $test_id)->count();
        $hitungnilai = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
            ->where('test_id', $test_id)
            ->where('peserta.user_id', Auth::user()->id)
            ->sum('nilai'); // Assuming 'nilai' is the column you want to sum
        $jawabBenar = Nilai_Test::join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                ->where('test_id', $test_id)
                                ->where('peserta.user_id', Auth::user()->id)
                                ->where('nilai','!=', 0)->count();
        $jawabSingkat = Jawaban_User_Singkat::where('test_id',$test_id)->get();
        $jawabPilgan = Jawaban_User_Pilgan::where('test_id',$test_id)->get();
        
        return view('peserta.hasil_test', [
            'pelatihan'=>$pelatihan,
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
