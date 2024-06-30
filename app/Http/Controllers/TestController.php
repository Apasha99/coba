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
use Illuminate\Support\Facades\Validator;
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
        $pelatihan2 = Pelatihan::where('kode', $kode)->first();
        $pelatihan_start_date = $pelatihan->start_date;
        $pelatihan_end_date = $pelatihan->end_date;
        $validator = Validator::make($request->all(), [
            'nama' => ['required'],
            'start_date' => ['required', 'date','after_or_equal:today',
                                function ($attribute, $value, $fail) use ($pelatihan_start_date) {
                                    if ($value < $pelatihan_start_date) {
                                        $fail('Start date must be after or equal to the start date of the training.');
                                    }
                                }
                            ],
            'end_date' => ['required', 'date','after_or_equal:start_date',
                                function ($attribute, $value, $fail) use ($pelatihan_end_date) {
                                    if ($value > $pelatihan_end_date) {
                                        $fail('End date must be before or equal to the end date of the training.');
                                    }
                                }
                            ],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'tampil_hasil' => ['required'],
            'kkm' => ['required', 'max:100','min:0']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Validasi berhasil
        $validated = $validator->validated();
        $peserta_ids = $pelatihan2->peserta_pelatihan()->pluck('peserta_id');
    
        try {
            $test = new Test([
                'nama' => $validated['nama'],
                'plt_kode' => $kode,
                'kkm' => $validated['kkm'],
                'start_date' => $validated['start_date'], // Tambahkan nilai untuk start_date
                'end_date' => $validated['end_date'],
                'deskripsi' => $validated['deskripsi'],
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
        $cekPeserta = Attempt::where('test_id',$test_id)->exists();
        $hitung_nilai = Soal_Test::where('test_id', $test_id)->sum('nilai');

        if($cekPeserta == true){
            return redirect()->back()->with('error','Tidak dapat menambahkan soal dan jawaban untuk tes yang telah dikerjakan peserta');
        }else if($test->start_date <= now()){
            return redirect()->back()->with('error','Tidak dapat menambahkan soal dan jawaban untuk tes yang telah dimulai');
        }
        
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
        // dd($request);
        try {
            //dd($request);
            $rules = [
                'soal' => ['required', 'max:2000'],
                'nilai-custom' => ['nullable'],
                'tipe_nilai' => ['required'],
                'file_soal' => ['nullable', 'image'],
                'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
            ];
            
            // Initial validation
            $validated = $request->validate($rules);
            
            // Additional validation for Pilihan Ganda
            if ($request->tipe_option == 'Pilihan Ganda') {
                $additionalRules = [
                    'ganda-benar' => ['required'],
                    'title_ganda' => ['required', 'array', 'min:1'],
                    'title_ganda.*' => ['required', 'distinct'],
                ];
            
                $validated = array_merge($validated, $request->validate($additionalRules));
            }
    
            // Hitung jumlah soal dan nilai total
            $hitung_soal = Soal_Test::where('test_id', $test_id)->count();
            $hitung_nilai_default = Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->sum('nilai');
            $hitung_nilai_custom = Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Custom')->sum('nilai');
            if((Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()) >= 0 && ($hitung_nilai_custom + $validated['nilai-custom']) >99){
                return redirect()->back()->with('error', 'Nilai soal tidak boleh lebih dari 100');
            }elseif(($hitung_nilai_custom + $validated['nilai-custom']) >= 100){
                return redirect()->back()->with('error', 'Nilai soal tidak boleh lebih dari 100');
            }
            if ($hitung_soal == 0) {
                if ($validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; // Gunakan nilai custom jika disediakan
                    $nilaiDefault = 0;
                }else {
                    $nilai = 100;
                }
            }elseif ($hitung_nilai_custom > 0 && $hitung_nilai_default > 0) {
                if ($validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; // Gunakan nilai custom jika disediakan
                    dd($nilai);
                    $nilaiDefault = round(((100 - ($hitung_nilai_custom + $nilai))/ (Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count())),2);
                } else {
                    $nilaiDefault = round((100 - $hitung_nilai_custom) / ((Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()) + 1),2);
                    $nilai = $nilaiDefault;
                }
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilaiDefault]);
            } elseif ($hitung_nilai_custom > 0 && !$hitung_nilai_default) {
                if ($validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; 
                } else {
                    $nilaiDefault = 100 - $hitung_nilai_custom;
                    $nilai = $nilaiDefault;
                }
            } elseif ($hitung_nilai_default > 0 && !$hitung_nilai_custom) {
                if ($validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; // Gunakan nilai custom jika disediakan
                    $nilaiDefault = round((100 - $nilai)/ ($hitung_soal),2);
                } else {
                    $nilaiDefault = round(100 / ($hitung_soal + 1),2);
                    $nilai = $nilaiDefault;
                }
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilai]);
            } else {
                $nilai = 100;
            }
            if ($hitung_soal != 0){
                if ($validated['tipe_nilai'] === 'Custom') {
                    Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilaiDefault]);
                }
            }
             //dd($nilaiDefault, $nilai);

            // Pembuatan soal dengan nilai yang ditentukan
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
                'tipe_nilai' => $validated['tipe_nilai'],
                'nilai' => round($nilai, 2),
                'file_soal' => $validated2['file_soal'] ?? null,
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
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data soal dan jawaban.');
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
        $pelatihan_start_date = $pelatihan->start_date;
        $pelatihan_end_date = $pelatihan->end_date;
        $validator = Validator::make($request->all(), [
            'nama' => ['required'],
            'start_date' => ['required', 'date',
                                function ($attribute, $value, $fail) use ($pelatihan_start_date) {
                                    if ($value < $pelatihan_start_date) {
                                        $fail('Start date must be after or equal to the start date of the training.');
                                    }
                                }
                            ],
            'kkm' => ['required', 'max:100','min:0'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date',
                                function ($attribute, $value, $fail) use ($pelatihan_end_date) {
                                    if ($value > $pelatihan_end_date) {
                                        $fail('End date must be before or equal to the end date of the training.');
                                    }
                                }
                            ],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'tampil_hasil' => ['required'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Validasi berhasil
        $validated = $validator->validated();
        
        try {
            DB::beginTransaction();
        
            $updateData = [
                'nama' => $validated['nama'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'kkm' => $validated['kkm'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
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

            $total_soal_default = Soal_Test::where('test_id',$test_id)->where('tipe_nilai','Default')->count();
            $hitung_nilai_custom = Soal_Test::where('test_id',$test_id)->where('tipe_nilai','Custom')->sum('nilai');
            if($total_soal_default>0){
                $nilaiDefault = (100-$hitung_nilai_custom)/$total_soal_default;
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilaiDefault]);
            }
            // Commit the transaction
            DB::commit();
            
            return redirect()->back()->with('success', 'Soal dan semua jawaban terkait berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e);
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
        try {
            //dd($request);
            $rules = [
                'soal' => ['required', 'max:2000'],
                'nilai-custom' => ['nullable'],
                'tipe_nilai' => ['required'],
                'file_soal' => ['nullable', 'image'],
                'tipe_option' => ['required', Rule::in(['Pilihan Ganda', 'Jawaban Singkat'])],
            ];
            
            // Initial validation
            $validated = $request->validate($rules);
            
            // Additional validation for Pilihan Ganda
            if ($request->tipe_option == 'Pilihan Ganda') {
                $additionalRules = [
                    'ganda_benar' => ['required'],
                    'title_ganda' => ['required', 'array', 'min:1'],
                    'title_ganda.*' => ['required', 'distinct'],
                ];
            
                $validated = array_merge($validated, $request->validate($additionalRules));
            }
            // Validasi nilai 'old'
            $tipe_nilai_old = $soal_test->tipe_nilai ?? null;
    
            // Hitung jumlah soal dan nilai total
            $hitung_soal = Soal_Test::where('test_id', $test_id)->count();
            $hitung_nilai_default = Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->sum('nilai');
            $hitung_nilai_custom = Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Custom')->sum('nilai');

            if((Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()) >= 0 && ($hitung_nilai_custom + $validated['nilai-custom']) > 99){
                return redirect()->back()->with('error', 'Nilai soal tidak boleh lebih dari 100');
            }elseif(($hitung_nilai_custom + $validated['nilai-custom']) >= 100){
                return redirect()->back()->with('error', 'Nilai soal tidak boleh lebih dari 100');
            }
            if ($request->has('file_soal')) {
                $fileSoalPath = $request->file('file_soal')->store('file_soal', 'public');
                $validated2['file_soal'] = $fileSoalPath;
            }

            if ($hitung_soal == 0) {
                if ($validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; // Gunakan nilai custom jika disediakan
                } else {
                    $nilai = 100;
                }
            } elseif ($hitung_nilai_custom > 0 && $hitung_nilai_default > 0) {
                $hitung_new_custom = Soal_Test::where('test_id', $test_id)
                                        ->where('tipe_nilai', 'Custom')
                                        ->where('id', '!=', $soal_id) // Tidak termasuk soal yang sedang diubah
                                        ->sum('nilai');
                if ($tipe_nilai_old === 'Custom' && $validated['tipe_nilai'] === 'Default') {
                    // Jika 'old' adalah Custom dan berubah ke Default
                    $nilaiDefault = round(((100 - $hitung_new_custom) / (Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()+1)), 2);
                    $nilai = $nilaiDefault;
                    //dd($nilai,$nilaiDefault,$hitung_new_custom,$hitung_nilai_default);
                } elseif ($tipe_nilai_old === 'Default' && $validated['tipe_nilai'] === 'Custom') {
                    // Jika 'old' adalah Default dan berubah ke Custom
                    $nilaiDefault = round(((100 - ($hitung_nilai_custom + $validated['nilai-custom'])) / (Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()-1)), 2);
                    $nilai = $validated['nilai-custom'];
                } else {
                    // Jika tidak ada perubahan pada tipe nilai
                    if ($validated['tipe_nilai'] === 'Custom') {
                        $nilai = $validated['nilai-custom']; // Gunakan nilai custom jika disediakan
                        $nilaiDefault = round(((100 - ($hitung_new_custom + $nilai))/ (Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count())),2);
                    } else {
                        $nilaiDefault = round((100 - $hitung_nilai_custom) / ((Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count())),2);
                        $nilai = $nilaiDefault;
                    }
                }
                // Update nilai default jika perlu
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilaiDefault]);
            } elseif ($hitung_nilai_custom > 0 && !$hitung_nilai_default) {
                $hitung_new_custom = Soal_Test::where('test_id', $test_id)
                                        ->where('tipe_nilai', 'Custom')
                                        ->where('id', '!=', $soal_id) // Tidak termasuk soal yang sedang diubah
                                        ->sum('nilai');
                if ($tipe_nilai_old === 'Custom' && $validated['tipe_nilai'] === 'Custom') {
                    $nilai = $request['nilai-custom']; 
                    $nilaiDefault = 100 - ($hitung_new_custom + $validated['nilai-custom']);
                } elseif($tipe_nilai_old === 'Custom' && $validated['tipe_nilai'] === 'Default') {
                    $nilaiDefault = 100 - $hitung_new_custom;
                    $nilai = $nilaiDefault;
                }else {
                    $nilaiDefault = 100 - $hitung_nilai_custom;
                    $nilai = $nilaiDefault;
                }
            } elseif ($hitung_nilai_default == 100 && !$hitung_nilai_custom) {
                if ($tipe_nilai_old === 'Default' && $validated['tipe_nilai'] === 'Custom') {
                    // Jika 'old' adalah Default dan berubah ke Custom
                    $nilaiDefault = round(((100 - ($validated['nilai-custom']))), 2);
                    $nilai = $validated['nilai-custom'];
                }else {
                    $nilaiDefault = round(100 / ($hitung_soal),2);
                    $nilai = $nilaiDefault;
                }
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilai]);
            } elseif ($hitung_nilai_default > 0 && !$hitung_nilai_custom) {
                if ($tipe_nilai_old === 'Default' && $validated['tipe_nilai'] === 'Custom') {
                    // Jika 'old' adalah Default dan berubah ke Custom
                    $nilaiDefault = round(((100 - ($validated['nilai-custom'])) / (Soal_Test::where('test_id', $test_id)->where('tipe_nilai','=','Default')->count()-1)), 2);
                    $nilai = $validated['nilai-custom'];
                }else {
                    $nilaiDefault = round(100 / ($hitung_soal),2);
                    $nilai = $nilaiDefault;
                }
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilai]);
            } else {
                $nilai = 100;
            }
    
            $updateData = [
                'title' => $validated['soal'] ?? null,
                'urutan' => $soal_test->urutan,
                'tipe_nilai' => $validated['tipe_nilai'],
                'nilai' => $nilai ?? null,  
                'file_soal' => $validated2['file_soal'] ?? null, // Use existing file if not updated
                'tipe_option' => $validated['tipe_option'] ?? null,
            ];
            //dd($updateData);
            // Pembuatan soal dengan nilai yang ditentukan
            $existingSoal = Soal_Test::where('test_id', $test_id)->orderBy('urutan', 'desc')->first();
            if ($request->has('file_soal')) {
                $fileSoalPath = $request->file('file_soal')->store('file_soal', 'public');
                $validated2['file_soal'] = $fileSoalPath;
            }
            $soal_test->update(array_filter($updateData));

            // Update nilai untuk semua soal dengan tipe nilai default jika nilai dihitung otomatis
            if ($validated['tipe_nilai'] === 'Custom') {
                Soal_Test::where('test_id', $test_id)->where('tipe_nilai', 'Default')->update(['nilai' => $nilaiDefault]);
            }
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
            //dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data soal dan jawaban.');
        }
    }
    
}
