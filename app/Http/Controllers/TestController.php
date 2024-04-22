<?php

namespace App\Http\Controllers;
use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Peserta;
use App\Models\Admin;
use App\Models\Notifikasi;
use App\Models\Attempt;
use App\Models\Nilai_Test;
use App\Models\Instruktur;
use App\Models\Soal_Test;
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

class TestController extends Controller
{
    public function viewDetailTest(String $plt_kode, String $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
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
        return view('peserta.detail_test', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'pelatihan' => $pelatihan, 'test' => $test]);
    }

    public function create($plt_kode){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::all();
        if(Auth::user()->role_id == 1){
            return view('admin.tambah_test', ['test'=>$test,'admin' => $admin, 'pelatihan' => $pelatihan]);
        }else{
            return view('instruktur.tambah_test', ['test'=>$test,'instruktur' => $instruktur, 'pelatihan' => $pelatihan]);
        }
    }

    public function store(Request $request, String $kode): RedirectResponse {
        $pelatihan = Pelatihan::where('kode', $kode)->first();
        $validated = $request->validate([
            'nama' => ['required'],
            'start_date' => ['required', 'date','after_or_equal:today'],
            'end_date' => ['required', 'date','after_or_equal:start_date'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'tampil_hasil' => ['required'],
            'durasi' => ['required','date_format:H:i:s'],
            'kkm' => ['required', 'max:100','min:0']
        ]);
        $pelatihan2 = Pelatihan::where('kode', $kode)->first();
        $peserta_ids = $pelatihan2->peserta_pelatihan()->pluck('peserta_id');
    
        try {
            $test = new Test([
                'nama' => $validated['nama'],
                'plt_kode' => $kode,
                'kkm' => $validated['kkm'],
                'start_date' => $validated['start_date'], // Tambahkan nilai untuk start_date
                'end_date' => $validated['end_date'],
                'deskripsi' => $validated['deskripsi'],
                'durasi' => $validated['durasi'],
                'tampil_hasil' => $validated['tampil_hasil'],
            ]);
            //dd($test);
    
            $test->save();
            foreach ($peserta_ids as $peserta_id) {
                Notifikasi::create([
                    'judul' => 'Test',
                    'subjudul' => 'Ada test baru: ' . $validated['nama'],
                    'plt_kode' => $kode,
                    'peserta_id' => $peserta_id,
                    'isChecked' => 0,
                ]);
            }
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $pelatihan->kode)->with('success', 'Data test berhasil disimpan');
            }else{
                return redirect()->route('instruktur.viewDetailPelatihan', $pelatihan->kode)->with('success', 'Data test berhasil disimpan');
            }
        } catch (\Exception $e) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $pelatihan->kode)->with('error', 'Terjadi kesalahan saat menyimpan data');
            }else{
                return redirect()->route('instruktur.viewDetailPelatihan', $pelatihan->kode)->with('error', 'Terjadi kesalahan saat menyimpan data');
            }
        }
    }    

    public function DetailTest(String $plt_kode, String $test_id) {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $hitung_soal = Soal_Test::where('test_id', $test_id)->count();
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');
        $existingNilai = Nilai_Test::where('test_id', $test_id)->exists();
        if(Auth::user()->role_id == 1){
            return view('admin.detail_test', ['existingNilai'=>$existingNilai,'hitung_nilai'=>$hitung_nilai,'pelatihan' => $pelatihan, 'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test,'hitung_soal'=>$hitung_soal]);
        } else{
            return view('instruktur.detail_test', ['existingNilai'=>$existingNilai,'instruktur'=>$instruktur,'hitung_nilai'=>$hitung_nilai,'pelatihan' => $pelatihan, 'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test,'hitung_soal'=>$hitung_soal]);
        }
    }

    public function createSoal(String $plt_kode, String $test_id) {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');
        if(Auth::user()->role_id == 1){
            return view('admin.tambah_soal', ['hitung_nilai'=>$hitung_nilai,'pelatihan'=>$pelatihan,'test' => $test]);
        }else{
            return view('instruktur.tambah_soal', ['hitung_nilai'=>$hitung_nilai,'pelatihan'=>$pelatihan,'test' => $test]);
        }
    }

    public function storeSoal(Request $request, $plt_kode, $test_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');
        if($hitung_nilai > 100 ){
            return redirect()->back()->with('error','Total nilai tidak boleh lebih dari 100');
        }
        try {
            //dd($request);
            $validated = $request->validate([
                'soal' => ['required', 'max:2000'],
                'nilai' => ['required', 'numeric', 'max:100', 'min:0'],
                'file_soal' => ['nullable','image'],
                'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
            ]);
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
                $jawabanBenar = strtolower($request->input('ganda-benar'));
                $gandaOption = strtolower($request->input('ganda'));
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
                $optionIncrementUrutan = $incrementUrutan;

                // Increment $incrementUrutan only once before the loop
            } elseif ($validated['tipe_option'] === 'Jawaban Singkat') {
                // Simpan jawaban singkat
                $jawabanBenar = strtolower($request->input('jawaban-singkat'));
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal->id,
                    'title' => $jawabanBenar,
                    'status' => true,
                    'urutan' => $incrementUrutan,
                ]);
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
                        'title' => strtolower($option),
                        'urutan' => ++$optionIncrementUrutan, 
                    ]);
                }
            }elseif($singkatOptions){
                foreach ($singkatOptions as $option) {
                    Jawaban_Test::create([
                        'plt_kode' => $plt_kode,
                        'test_id' => $test_id,
                        'soal_id' => $soal->id,
                        'status' => true,
                        'title' => strtolower($option),
                        'urutan' => ++$optionIncrementUrutan,
                    ]);
                }
            }

            return redirect()->route('test.detail', [$pelatihan->kode, $test->id])->with('success', 'Soal dan Jawaban berhasil disimpan');
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
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        $test = Test::where('plt_kode',$plt_kode)->find($test_id);
        if(Auth::user()->role_id == 1){
            return view('admin.edit_test', ['admin' => $admin, 'test' => $test, 'pelatihan' => $pelatihan]);
        } else{
            return view('instruktur.edit_test', ['instruktur' => $instruktur, 'test' => $test, 'pelatihan' => $pelatihan]);
        }
    }

    public function update(Request $request, $plt_kode, $test_id)
    {
        //dd($request);
        $test = Test::where('plt_kode',$plt_kode)->find($test_id);
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');
        $validated = $request->validate([
            'nama' => ['required'],
            'start_date' => ['required', 'date'],
            'kkm' => ['required', 'max:100','min:0'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'durasi' => ['required','date_format:H:i:s'],
            'tampil_hasil' => ['required'],
        ]);
        
        try {
            DB::beginTransaction();
        
            $updateData = [
                'nama' => $validated['nama'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'kkm' => $validated['kkm'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'durasi' => $validated['durasi'] ?? null,
                'tampil_hasil' => $validated['tampil_hasil'],
            ];

            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Test')
                    ->where(function($query) use ($test) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = ?", [$test->nama])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = ?", [$test->nama]);
                    })->first();
            
                if ($notifikasi) {
                    // Perbarui subjudul notifikasi
                    $notifikasi->subjudul = 'Ada pembaharuan test: ' . $validated['nama'];
                    $notifikasi->isChecked = 0;
                    $notifikasi->save();
                } else {
                    // Buat notifikasi baru karena tidak ada notifikasi sebelumnya
                    $notifikasiBaru = new Notifikasi();
                    $notifikasiBaru->plt_kode = $plt_kode;
                    $notifikasiBaru->peserta_id = $peserta_id;
                    $notifikasiBaru->judul = 'Test';
                    $notifikasiBaru->subjudul = 'Ada pembaharuan test: ' . $validated['nama'];
                    $notifikasiBaru->isChecked = 0;
                    $notifikasiBaru->save();
                }
            }   
        
            $test->update(array_filter($updateData));
        
            DB::commit();
            if(Auth::user()->role_id == 1){
                return redirect()
                    ->route('admin.viewDetailPelatihan', $test->plt_kode)
                    ->with('success', 'Data test berhasil diperbarui');
            }else{
                return redirect()
                    ->route('instruktur.viewDetailPelatihan', $test->plt_kode)
                    ->with('success', 'Data test berhasil diperbarui');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();

            if(Auth::user()->role_id == 1){
                return redirect()
                    ->route('admin.viewDetailPelatihan', $test->plt_kode)
                    ->with('error', 'Gagal memperbarui data test.');
            }else{
                return redirect()
                    ->route('instruktur.viewDetailPelatihan', $test->plt_kode)
                    ->with('error', 'Gagal memperbarui data test.');
            }
        }        
    }

    public function delete($plt_kode, $test_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->get();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->get();
        $jawaban_pilgan = Jawaban_User_Pilgan::where('test_id',$test_id)->get();
        $jawaban_singkat = Jawaban_User_Singkat::where('test_id',$test_id)->get();
        $attempt = Attempt::where('test_id',$test_id)->get();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->firstOrFail();
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');
        $cekPeserta = Attempt::where('test_id',$test_id)->exists();

        if($cekPeserta == true){
            return redirect()->back()->with('error','Tidak dapat menghapus test yang telah dikerjakan peserta');
        }else if($test->start_date <= now()){
            return redirect()->back()->with('error','Tidak dapat menghapus test yang telah dimulai');
        }
        DB::beginTransaction();

        try {
            foreach ($jawaban_pilgan as $jawaban) {
                $jawaban->delete();
            }
            foreach ($jawaban_singkat as $jawaban) {
                $jawaban->delete();
            }
            foreach ($attempt as $jawaban) {
                $jawaban->delete();
            }
            foreach ($jawaban_test as $jawaban) {
                $jawaban->delete();
            }
            foreach ($soal_test as $soal) {
                $soal->delete();
            }

            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Test')
                    ->where(function($query) use ($test) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada test baru: ', -1) = ?", [$test->nama])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan test: ', -1) = ?", [$test->nama]);
                    })->delete();
            }

            $test->delete();

            // Commit the transaction
            DB::commit();
            if(Auth::user()->role_id == 1){
                return redirect()
                    ->route('admin.viewDetailPelatihan', $test->plt_kode)
                    ->with('success', 'Test, soal, dan semua jawaban terkait berhasil dihapus.');
            }else{
                return redirect()
                    ->route('instruktur.viewDetailPelatihan', $test->plt_kode)
                    ->with('success', 'Test, soal, dan semua jawaban terkait berhasil dihapus.');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollback();
            if(Auth::user()->role_id == 1){
                return redirect()
                    ->route('admin.viewDetailPelatihan', $test->plt_kode)
                    ->with('error', 'Terjadi kesalahan saat menghapus test, soal, dan jawaban terkait.');
            }else{
                return redirect()
                    ->route('instruktur.viewDetailPelatihan', $test->plt_kode)
                    ->with('error', 'Terjadi kesalahan saat menghapus test, soal, dan jawaban terkait.');
            }
        }
    }

    public function deleteSoal(Request $request, $plt_kode, $test_id, $soal_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->where('id', $soal_id)->first();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id', $soal_id)->get();
        $cekPeserta = Attempt::where('test_id',$test_id)->exists();

        if($cekPeserta == true){
            return redirect()->back()->with('error','Tidak dapat menghapus soal dan jawaban yang telah dikerjakan peserta');
        }else if($test->start_date <= now()){
            return redirect()->back()->with('error','Tidak dapat menghapus soal dan jawaban yang telah dimulai');
        }

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
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id', $test_id)->where('id',$soal_id)->first();
        $jawaban_test = Jawaban_Test::where('test_id', $test_id)->where('soal_id',$soal_id)->get();
                                //dd($test);
        $cekPeserta = Attempt::where('test_id',$test_id)->exists();
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');
        if($cekPeserta == true){
            return redirect()->back()->with('error','Tidak dapat mengedit soal dan jawaban yang telah dikerjakan peserta');
        }else if($test->start_date <= now()){
            return redirect()->back()->with('error','Tidak dapat mengedit soal dan jawaban yang telah dimulai');
        }
        if(Auth::user()->role_id == 1){
            return view('admin.edit_soal', ['hitung_nilai'=>$hitung_nilai,'pelatihan'=>$pelatihan,'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test]);
        }else{
            return view('instruktur.edit_soal', ['hitung_nilai'=>$hitung_nilai,'pelatihan'=>$pelatihan,'test' => $test,'soal_test'=>$soal_test,'jawaban_test'=>$jawaban_test]);
        }
    }

    public function updateSoal(Request $request, $plt_kode, $test_id, $soal_id)
    {
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        $soal_test = Soal_Test::where('test_id',$test_id)->find($soal_id);
        $jawaban_test = Jawaban_Test::where('test_id',$test_id)->where('soal_id',$soal_id)->get();
        //dd($request);
        if (!$soal_test) {
            return redirect()->route('test.detail')->with('error', 'Tidak dapat menemukan soal yang ingin diedit.');
        }
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');
        if($hitung_nilai > 100 ){
            return redirect()->back()->with('error','Total nilai tidak boleh lebih dari 100');
        }
        
        $validated = $request->validate([
            'soal' => ['required', 'max:2000'],
            'nilai' => ['required', 'numeric', 'max:100', 'min:0'],
            'file_soal' => ['nullable'],
            'tipe_option' => ['required'],
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
            Jawaban_Test::where('test_id', $test_id)->where('soal_id', $soal_id)->delete();
            $existingJawaban = Jawaban_Test::where('test_id', $test_id)
                                            ->where('soal_id', $soal_id)
                                            ->orderBy('urutan', 'desc')
                                            ->first();
            $incrementUrutan = $existingJawaban ? $existingJawaban->urutan + 1 : 1;

            if ($validated['tipe_option'] === 'Pilihan Ganda') {
                // Simpan jawaban pilihan ganda
                $jawabanBenar = strtolower($request->input('ganda_benar'));
                Jawaban_Test::create([
                    'plt_kode' => $plt_kode,
                    'test_id' => $test_id,
                    'soal_id' => $soal_test->id,
                    'title' => $jawabanBenar,
                    'status' => true,
                    'urutan' => $incrementUrutan,
                ]);
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
                        'title' => strtolower($option),
                        'urutan' => ++$optionIncrementUrutan,
                        
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
                        'title' => strtolower($option),
                        'urutan' => ++$optionIncrementUrutan, 
                    ]);
                }
            }
    
            DB::commit();
    
            return redirect()
                ->route('test.detail', ['plt_kode'=>$test->plt_kode, 'test_id'=>$test->id])
                ->with('success', 'Data soal dan jawaban berhasil diperbarui');
        } catch (\Exception $e) {
            
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data soal dan jawaban.');
        }
    }
    
}
