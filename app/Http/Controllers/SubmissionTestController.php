<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Peserta;
use App\Models\Soal_Test;
use App\Models\Nilai_Test;
use App\Models\Jawaban_Test;
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
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode','peserta.user_id')
                ->first();
        return view('peserta.detail_test', ['pelatihan' => $pelatihan, 'test' => $test,'peserta'=>$peserta]);
    }

    public function test($plt_kode, $test_id, $question_number = 1)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $nilai = Nilai_Test::leftjoin('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')->where('test_id', $test_id)->where('peserta.user_id', Auth::user()->id)->first();
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
            ->leftJoin('peserta_pelatihan', 'peserta.id', '=', 'peserta_pelatihan.peserta_id')
            ->where('peserta.user_id', Auth::user()->id)
            ->select('peserta.nama', 'peserta.id', 'users.username', 'peserta_pelatihan.plt_kode', 'peserta.user_id')
            ->first();

        // Validate that the question_number is within a valid range
        if ($question_number < 1 || $question_number > count($soal_test)) {
            abort(404); // or handle it as appropriate for your application
        }

        $currentQuestion = $soal_test->get($question_number - 1); // -1 to adjust for zero-based index

        return view('peserta.test', [
            'currentQuestion' => $soal_test->get($question_number - 1),
            'question_number' => $question_number,
            'pelatihan' => $pelatihan,
            'test' => $test,
            'nilai' => $nilai,
            'soal_test' => $soal_test,
            'jawaban_test' => $jawaban_test,
            'peserta' => $peserta,
        ]);
    }

    public function submitAnswer(Request $request, $plt_kode, $test_id)
    {
        $soal_id = $request->input('soal_id');
        $selected_option = $request->input('selected_option');

        // Process the selected option here, update database, calculate score, etc.

        // Redirect back or to the next question
        return redirect()->back();
    }

    public function showQuestion($plt_kode, $test_id, $question_number)
    {
        $soal_test = Soal_Test::where('test_id', $test_id)->get();

        // Validate that the question_number is within a valid range
        if ($question_number < 1 || $question_number > count($soal_test)) {
            abort(404); // or handle it as appropriate for your application
        }

        $currentQuestion = $soal_test->get($question_number - 1); // -1 to adjust for zero-based index

        return view('peserta.test', [
            'plt_kode' => $plt_kode,
            'test_id' => $test_id,
            'currentQuestion' => $currentQuestion,
            'question_number' => $question_number,
        ]);
    }

}
