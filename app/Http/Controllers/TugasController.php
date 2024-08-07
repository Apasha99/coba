<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\Notifikasi;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\Tugas;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function viewTambahTugas($plt_kode){
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('admin.tambah_tugas', ['pelatihan' => $pelatihan]);
    }

    public function detailTugas(String $plt_kode, String $tugas_id, $notif_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        $peserta_id = Peserta::where('user_id', Auth::user()->id)->value('id');
        $submission = Submission::where('tugas_id', $tugas_id)->where('peserta_id', $peserta_id)->first();
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
        $peserta = Peserta::leftJoin('users', 'peserta.user_id', '=', 'users.id')
                ->leftJoin('peserta_pelatihan','peserta.id','=','peserta_pelatihan.peserta_id')
                ->where('peserta.user_id', Auth::user()->id)
                ->select('peserta.nama', 'peserta.id', 'users.username','peserta_pelatihan.plt_kode')
                ->first();
        return view('peserta.detail_tugas_copy', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'pelatihan' => $pelatihan, 'tugas' => $tugas, 'submission' => $submission]);
    }

    public function viewDetailTugas(String $plt_kode, String $tugas_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        $peserta_id = Peserta::where('user_id', Auth::user()->id)->value('id');
        $submission = Submission::where('tugas_id', $tugas_id)->where('peserta_id', $peserta_id)->first();
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
        return view('peserta.detail_tugas', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'pelatihan' => $pelatihan, 'tugas' => $tugas, 'submission' => $submission]);
    }

    public function store(Request $request, String $kode): RedirectResponse {
        // Dapatkan data pelatihan
        $pelatihan = Pelatihan::where('kode', $kode)->first();
        $pelatihan_start_date = $pelatihan->start_date;
        $pelatihan_end_date = $pelatihan->end_date;
        
        // Validasi input dengan aturan kustom
        $validator = Validator::make($request->all(), [
            'judul' => ['required'],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($pelatihan_start_date) {
                    if ($value < $pelatihan_start_date) {
                        $fail('Start date must be after or equal to the start date of the training.');
                    }
                }
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($pelatihan_end_date) {
                    if ($value > $pelatihan_end_date) {
                        $fail('End date must be before or equal to the end date of the training.');
                    }
                }
            ],
            'deskripsi' => ['required', 'max:2000'],
            'file_tugas' => ['max:10240'],
            'nama_file' => ['optional']
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Validasi berhasil
        $validated = $validator->validated();
        
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');
        if ($request->has('file_tugas')) {
            $fileTugasPath = $request->file('file_tugas')->store('file_tugas', 'public');
            $filename = $request->file('file_tugas')->getClientOriginalName();
            $validated['file_tugas'] = $fileTugasPath;
            $validated['nama_file'] = $filename;
        }

        $validated['plt_kode'] = $kode;

        try {
            Tugas::create($validated);
            foreach ($peserta_ids as $peserta_id) {
                Notifikasi::create([
                    'judul' => 'Tugas',
                    'subjudul' => 'Ada tugas baru: ' . $validated['judul'],
                    'plt_kode' => $kode,
                    'peserta_id' => $peserta_id,
                    'isChecked' => 0,
                ]);
            }
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $kode)->with('success', 'Data tugas berhasil disimpan');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $kode)->with('success', 'Data tugas berhasil disimpan');
            }
        } catch (\Exception $e) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $kode)->with('success', 'Gagal menyimpan data tugas');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $kode)->with('success', 'Gagal menyimpan data tugas');
            }
        }
    }

    public function viewEdit($plt_kode, $id){
        $tugas = Tugas::find($id);
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('admin.edit_tugas', ['tugas' => $tugas, 'pelatihan' => $pelatihan]);
    }

    public function update(Request $request, $plt_kode, $id)
    {
        //dd($request);
        $tugas = Tugas::find($id);
        $pelatihan = Pelatihan::find($plt_kode);
        $pelatihan2 = Pelatihan::where('kode', $plt_kode)->first();
        $pelatihan_start_date = $pelatihan2->start_date;
        $pelatihan_end_date = $pelatihan2->end_date;
        $peserta_ids = $pelatihan2->peserta_pelatihan()->pluck('peserta_id');
        $validator = Validator::make($request->all(), [
            'judul' => ['required'],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($pelatihan_start_date) {
                    if ($value < $pelatihan_start_date) {
                        $fail('Start date must be after or equal to the start date of the training.');
                    }
                }
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) use ($pelatihan_end_date) {
                    if ($value > $pelatihan_end_date) {
                        $fail('End date must be before or equal to the end date of the training.');
                    }
                }
            ],
            'deskripsi' => ['required', 'max:2000'],
            'file_tugas' => ['max:10240'],
            'nama_file' => ['optional']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        try {
            DB::beginTransaction();
    
            $tugas = Tugas::find($id);

            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Tugas')
                    ->where(function($query) use ($tugas) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = ?", [$tugas->judul])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = ?", [$tugas->judul]);
                    })->first();
            
                if ($notifikasi) {
                    // Perbarui subjudul notifikasi
                    $notifikasi->subjudul = 'Ada pembaharuan tugas: ' . $validated['judul'];
                    $notifikasi->isChecked = 0;
                    $notifikasi->save();
                } else {
                    // Buat notifikasi baru karena tidak ada notifikasi sebelumnya
                    $notifikasiBaru = new Notifikasi();
                    $notifikasiBaru->plt_kode = $plt_kode;
                    $notifikasiBaru->peserta_id = $peserta_id;
                    $notifikasiBaru->judul = 'Tugas';
                    $notifikasiBaru->subjudul = 'Ada pembaharuan tugas: ' . $validated['judul'];
                    $notifikasiBaru->isChecked = 0;
                    $notifikasiBaru->save();
                }
            }
    
            $fileTugasPathLama = $tugas->file_tugas;
    
            $updateData = [
                'judul' => $validated['judul'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null
            ];   
    
            if ($request->hasFile('file_tugas')) {
                $fileTugasPathBaru = $request->file('file_tugas')->store('file_tugas', 'public');
                $filename = $request->file('file_tugas')->getClientOriginalName();
                $updateData['file_tugas'] = $fileTugasPathBaru;
                $updateData['nama_file'] = $filename;
                // dd($filename);
                if ($fileTugasPathLama) {
                    Storage::delete('public/' . $fileTugasPathLama);
                }
            }
    
            $tugas->update(array_filter($updateData));

            DB::commit();
    
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $plt_kode)->with('success', 'Data tugas berhasil diperbarui');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $plt_kode)->with('success', 'Data tugas berhasil diperbarui');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $plt_kode)->with('success', 'Gagal memperbarui data tugas');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $plt_kode)->with('success', 'Gagal memperbarui data tugas');
            }
        }
    }

    public function delete($plt_kode, $id)
    {
        $tugas = Tugas::where('id', $id)->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->firstOrFail();
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');
        $cekPeserta = Submission::where('tugas_id', $id)->exists();

        if($cekPeserta == true){
            return redirect()->back()->with('error','Tidak dapat menghapus penugasan yang telah dikerjakan peserta');
        }

        DB::beginTransaction();

        try {
            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Tugas')
                    ->where(function($query) use ($tugas) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = ?", [$tugas->judul])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = ?", [$tugas->judul]);
                    })->delete();
            }
            $tugas->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Tugas berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus tugas');
        }
    }
}
