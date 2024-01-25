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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

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
            'isActive' => ['required','boolean'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'acak_soal' => ['required'],
            'acak_jawaban' => ['required'],
            'tampil_hasil' => ['required'],
            'durasi' => ['required','date_format:H:i:s'],
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
                'durasi' => $validated['durasi'],
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
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $hitung_soal = Soal_Test::where('test_id', $test_id)->count();
        return view('admin.detail_test', ['pelatihan' => $pelatihan, 'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test,'hitung_soal'=>$hitung_soal]);
    }

    public function storeSoal(Request $request, $plt_kode, $test_id)
    {
        try {
            //dd($request);
            $validated = $request->validate([
                'soal' => ['required', 'max:2000'],
                'nilai' => ['required', 'numeric', 'max:100', 'min:0'],
                'file_soal' => ['nullable','image'],
                'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
            ]);
            //dd($validated);
            // Cek apakah urutan untuk test_id dan soal_id sudah ada di database
            $existingSoal = Soal_Test::where('test_id', $test_id)->orderBy('urutan', 'desc')->first();
            $incrementedUrutan = $existingSoal ? $existingSoal->urutan + 1 : 1;

            if ($request->has('file_soal')) {
                $fileSoalPath = $request->file('file_soal')->store('file_soal', 'public');
                $validated2['file_soal'] = $fileSoalPath;
            }
            $soal = Soal_Test::create([
                'plt_kode' => $plt_kode,
                'test_id' => $test_id,
                'urutan' => $incrementedUrutan,
                'title' => $validated['soal'],
                'nilai' => $validated['nilai'],
                'file_soal' => $validated2['file_soal']?? null,
                'tipe' => $validated['tipe_option'],
            ]);

            $existingJawaban = Jawaban_Test::where('test_id', $test_id)
                                            ->where('soal_id', $soal->id)
                                            ->orderBy('urutan', 'desc')
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

    public function edit($plt_kode, $test_id){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        if($admin){
            $test = Test::where('plt_kode',$plt_kode)->find($test_id);
            return view('admin.edit_test', ['admin' => $admin, 'test' => $test]);
        }
    }

    public function update(Request $request, $plt_kode, $test_id)
    {
        //dd($request);
        $test = Test::where('plt_kode',$plt_kode)->find($test_id);
        if (!$test) {
            return redirect()->route('admin.viewDetailPelatihan', $test->plt_kode)->with('error', 'Tidak dapat menemukan test yang ingin diedit.');
        }
        $validated = $request->validate([
            'nama' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'isActive' => ['required', 'boolean'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'acak_soal' => ['required'],
            'durasi' => ['required','date_format:H:i:s'],
            'acak_jawaban' => ['required'],
            'tampil_hasil' => ['required'],
            'totalnilai' => ['required', 'numeric', 'max:100', 'min:0'],
        ]);
        
        try {
            DB::beginTransaction();
        
            $updateData = [
                'nama' => $validated['nama'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'isActive' => $validated['isActive'],  // Use the validated value directly
                'deskripsi' => $validated['deskripsi'] ?? null,
                'acak_soal' => $validated['acak_soal'],
                'durasi' => $validated['durasi'] ?? null,
                'acak_jawaban' => $validated['acak_jawaban'],
                'tampil_hasil' => $validated['tampil_hasil'],
                'totalnilai' => $validated['totalnilai'] ?? null,
            ];
        
            $test->update(array_filter($updateData));
        
            DB::commit();
        
            return redirect()
                ->route('admin.viewDetailPelatihan', $test->plt_kode)
                ->with('success', 'Data test berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data test.');
        }        
    }

    public function delete($plt_kode, $test_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        //dd($test);
        DB::beginTransaction();

        try {
            if ($test && $test->status == 1) {
                return redirect()->back()->with('error', 'Tidak dapat menghapus test yang masih aktif.');
            }

            foreach ($jawaban_test as $jawaban) {
                $jawaban->delete();
            }

            foreach ($soal_test as $soal) {
                $soal->delete();
            }

            $test->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Test, soal, dan semua jawaban terkait berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus test, soal, dan jawaban terkait.');
        }
    }

    public function deleteSoal(Request $request, $plt_kode, $test_id, $soal_id)
    {
        $soal_test = Soal_Test::where('test_id', $test_id)->where('id', $soal_id)->first();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id', $soal_id)->get();

        DB::beginTransaction();

        try {
            foreach ($jawaban_test as $jawaban) {
                $jawaban->delete();
            }

            $soal_test->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Soal dan semua jawaban terkait berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus soal dan jawaban terkait.');
        }
    }

    public function deleteJawaban(Request $request, $plt_kode, $test_id, $soal_id, $jawaban_id)
    {
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id', $soal_id)->where('id', $jawaban_id)->first();

        DB::beginTransaction();

        try {
            $jawaban_test->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Jawaban berhasil dihapus');
        } catch (\Exception $e) {
            //dd($e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus jawaban');
        }
    }

    public function editSoal(String $plt_kode, String $test_id, String $soal_id) {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
                $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->where('id',$soal_id)->first();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id',$soal_id)->get();
                                //dd($test);
        return view('admin.edit_soal', ['pelatihan'=>$pelatihan,'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test]);
    }

    public function updateSoal(Request $request, $plt_kode, $test_id, $soal_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id',$test_id)->find($soal_id);
        $jawaban_test = Jawaban_Test::where('test_id',$test_id)->where('soal_id',$soal_id)->get();
        //dd($request);
        if (!$soal_test) {
            return redirect()->route('admin.detailTest')->with('error', 'Tidak dapat menemukan soal yang ingin diedit.');
        }
        $validated = $request->validate([
            'soal' => ['required', 'max:2000'],
            'nilai' => ['required', 'numeric', 'max:100', 'min:0'],
            'file_soal' => ['nullable', 'image'],
            'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
        ]);
        
        //dd($validated);
        try {
            DB::beginTransaction();
    
            $updateData = [
                'title' => $validated['soal'] ?? null,
                'urutan' => $soal_test->urutan,
                'nilai' => $validated['nilai'] ?? null,
                'file_soal' => $soal_test->file_soal, // Use existing file if not updated
                'tipe_option' => $validated['tipe_option'] ?? null,
            ];
    
            if ($request->hasFile('file_soal')) {
                Storage::disk('public')->delete($soal_test->file_soal); // Delete old file
                $fileSoalPath = $request->file('file_soal')->store('file_soal', 'public');
                $updateData['file_soal'] = $fileSoalPath;
            }
    
            $soal_test->update(array_filter($updateData));
            $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id', $soal_id)->first();
            $jawaban_test->delete();
            $existingJawaban = Jawaban_Test::where('test_id', $test_id)
                                            ->where('soal_id', $soal_id)
                                            ->orderBy('urutan', 'desc')
                                            ->first();
            $incrementUrutan = $existingJawaban ? $existingJawaban->urutan + 1 : 1;
    
            if ($validated['tipe_option'] === 'Pilihan Ganda') {
                // Simpan jawaban pilihan ganda
                $jawabanBenar = $request->input('ganda_benar');
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal_test->id,
                    'title' => $jawabanBenar,
                    'status' => true,
                    'urutan' => $incrementUrutan,
                ]);

                // Increment $incrementUrutan only once before the loop
                $optionIncrementUrutan = $incrementUrutan;
            }

            $gandaOptions = $request->input('title_ganda');
            $singkatOptions = $request->input('title_singkat');
            if ($gandaOptions) {
                foreach ($gandaOptions as $option) {
                    Jawaban_Test::create([
                        'plt_kode' => $plt_kode,
                        'test_id' => $test_id,
                        'soal_id' => $soal_test->id,
                        'status' => false,
                        'title' => $option,
                        'urutan' => ++$optionIncrementUrutan, // Increment for each option
                    ]);
                }
            }elseif($singkatOptions){
                $optionIncrementUrutan = $incrementUrutan;
                foreach ($singkatOptions as $option) {
                    Jawaban_Test::create([
                        'plt_kode' => $plt_kode,
                        'test_id' => $test_id,
                        'soal_id' => $soal_test->id,
                        'status' => true,
                        'title' => $option,
                        'urutan' => ++$optionIncrementUrutan, // Increment for each option
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()
                ->route('admin.detailTest', ['plt_kode'=>$test->plt_kode, 'test_id'=>$test->id])
                ->with('success', 'Data soal dan jawaban berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data soal dan jawaban.');
        }
    }
    
}
