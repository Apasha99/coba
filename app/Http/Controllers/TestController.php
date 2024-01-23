<?php

namespace App\Http\Controllers;
use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Admin;
use App\Models\Soal_Test;
use App\Models\Jawaban_Test;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponseNa;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class TestController extends Controller
{
    public function viewDetailTest(String $plt_kode, String $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        return view('peserta.detail_test', ['pelatihan' => $pelatihan, 'test' => $test]);
    }

    public function store(Request $request, String $kode): RedirectResponse {
        $validated = $request->validate([
            'nama' => ['required'],
            'start_date' => ['required', 'date','after_or_equal:today'],
            'end_date' => ['required', 'date','after_or_equal:start_date'],
            'isActive' => ['required', 'boolean'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'acak_soal' => ['required'],
            'acak_jawaban' => ['required'],
            'tampil_hasil' => ['required'],
            'totalnilai' => ['required', 'numeric', 'max:100', 'min:0'],
        ]);
        //dd($validated);
    
        $isActive = $request->input('isActive');
    
        try {
            $test = new Test([
                'nama' => $validated['nama'],
                'isActive' => $isActive,
                'totalnilai' => $validated['totalnilai'],
                'plt_kode' => $kode,
                'start_date' => $validated['start_date'], // Tambahkan nilai untuk start_date
                'end_date' => $validated['end_date'],
                'acak_soal' => $validated['acak_soal'],
                'deskripsi' => $validated['deskripsi'],
                'acak_jawaban' => $validated['acak_jawaban'],
                'tampil_hasil' => $validated['tampil_hasil'],
            ]);
            //dd($test);
    
            $test->save();
            return redirect()->back()->with('success', 'Data test berhasil disimpan');
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan atau log jika diperlukan
            //dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }    

    public function DetailTest(String $plt_kode, String $test_id) {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('soal_test.test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('jawaban_test.test_id', $test_id)->get();
                                //dd($soal_test);
        return view('admin.detail_test', ['pelatihan' => $pelatihan, 'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test]);
    }

    public function storeSoal(Request $request, $plt_kode, $test_id)
    {
        try {
            //dd($request);
            $validated = $request->validate([
                'soal' => ['required', 'max:2000'],
                'nilai' => ['required', 'numeric', 'max:100', 'min:0'],
                'file_soal' => ['nullable'],
                'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
            ]);
            //dd($validated);
            // Cek apakah urutan untuk test_id dan soal_id sudah ada di database
            $existingSoal = Soal_Test::where('test_id', $test_id)->first();
            $incrementedUrutan = $existingSoal ? $existingSoal->urutan + 1 : 1;

            if ($request->has('file_soal')) {
                $fileSoalPath = $request->file('file_soal')->store('file_soal', 'public');
                $validated['file_soal'] = $fileSoalPath;
            }
            $soal = Soal_Test::create([
                'plt_kode' => $plt_kode,
                'test_id' => $test_id,
                'urutan' => $incrementedUrutan,
                'title' => $validated['soal'],
                'nilai' => $validated['nilai'],
                'file_soal' => $validated['file_soal'],
                'tipe' => $validated['tipe_option'],
            ]);

            $existingJawaban = Jawaban_Test::where('test_id', $test_id)
                                            ->where('soal_id', $soal->id)
                                            ->first();
            $incrementUrutan = $existingJawaban ? $existingJawaban->urutan + 1 : 1;
            if ($validated['tipe_option'] === 'Pilihan Ganda') {
                // Simpan jawaban pilihan ganda
                $jawabanBenar = $request->input('ganda-benar');
                $gandaOption = $request->input('ganda');
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal->id,
                    'title' => $jawabanBenar,
                    'status' => true,
                    'urutan' => $incrementUrutan,
                ]);
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal->id,
                    'status' => false, // Set jawaban selain benar menjadi false
                    'title' => $gandaOption,
                    'urutan' => ++$incrementUrutan,
                ]);

                // Increment $incrementUrutan only once before the loop
                $optionIncrementUrutan = $incrementUrutan;
            } elseif ($validated['tipe_option'] === 'Jawaban Singkat') {
                // Simpan jawaban singkat
                $jawabanBenar = $request->input('jawaban-singkat');
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal->id,
                    'title' => $jawabanBenar,
                    'status' => true,
                    'urutan' => $incrementUrutan,
                ]);

                // Initialize $optionIncrementUrutan for the loop
                $optionIncrementUrutan = $incrementUrutan;
            }

            $gandaOptions = $request->input('title_ganda');
            $singkatOptions = $request->input('title_singkat');
            if ($gandaOptions) {
                foreach ($gandaOptions as $option) {
                    Jawaban_Test::create([
                        'plt_kode' => $plt_kode,
                        'test_id' => $test_id,
                        'soal_id' => $soal->id,
                        'status' => false,
                        'title' => $option,
                        'urutan' => ++$optionIncrementUrutan, // Increment for each option
                    ]);
                }
            }elseif($singkatOptions){
                foreach ($singkatOptions as $option) {
                    Jawaban_Test::create([
                        'plt_kode' => $plt_kode,
                        'test_id' => $test_id,
                        'soal_id' => $soal->id,
                        'status' => true,
                        'title' => $option,
                        'urutan' => ++$optionIncrementUrutan, // Increment for each option
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Soal dan Jawaban berhasil disimpan');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            // Log or handle the query exception as needed
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam menyimpan data')->withInput();
        }
    }


    
}
