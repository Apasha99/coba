<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\Notifikasi;
use App\Models\Peserta_Pelatihan;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\SubmissionFiles;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PHPZip\Zip\File\Zip;
use Illuminate\Support\Facades\File;
use PhpZip\ZipFile;

class SubmissionController extends Controller
{
    public function viewSubmissionForm(String $plt_kode, String $tugas_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
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
                                ->where('peserta_id', '=', Auth::user()->peserta->id)
                                ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                ->where(function($query) {
                                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                })
                                ->get();
            $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')
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
        return view('peserta.submit_tugas', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'pelatihan' => $pelatihan, 'tugas' => $tugas]);
    }

    public function viewEditSubmission(String $plt_kode, String $tugas_id, String $submission_id){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        $submission = Submission::where('id', $submission_id)->first();
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
                                ->where('peserta_id', '=', Auth::user()->peserta->id)
                                ->select('isChecked','notifikasi.plt_kode', 'tugas.judul','notifikasi.id as notif_id', 'subjudul', 'tugas.id as tugas_id')
                                ->where(function($query) {
                                    $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada tugas baru: ', -1) = tugas.judul")
                                        ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan tugas: ', -1) = tugas.judul");
                                })
                                ->get();
            $notif_test = Notifikasi::join('test','test.plt_kode','=','test.plt_kode')
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
        return view('peserta.edit_tugas', ['total_notif'=>$total_notif,'peserta'=>$peserta,'notif_materi'=>$notif_materi,'notif_tugas'=>$notif_tugas,'notif_test'=>$notif_test,'pelatihan' => $pelatihan, 'tugas' => $tugas, 'submission' => $submission]);
    }

    public function viewDaftarSubmissionTugas(String $plt_kode, String $tugas_id){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        $submissions = Submission::where('tugas_id', $tugas_id)->get();
    
        $peserta_pelatihan = Peserta_Pelatihan::where('plt_kode', $plt_kode)->pluck('peserta_id');
        $peserta = Peserta::whereIn('id', $peserta_pelatihan)->get();
        
        $submission_peserta = Submission::join('peserta', 'submissions.peserta_id', '=', 'peserta.id')
            ->join('submission_files', 'submissions.id', '=', 'submission_files.submission_id')
            ->whereIn('submissions.peserta_id', $peserta_pelatihan)
            ->select('peserta.nama', 'submissions.updated_at', 'submission_files.*')
            ->get();
        
        return view('admin.daftar_submission_tugas', [
            'pelatihan' => $pelatihan,
            'tugas' => $tugas,
            'submissions' => $submissions,
            'peserta' => $peserta,
            'submission_peserta' => $submission_peserta
        ]);
    }
    

    public function store(Request $request, String $plt_kode, String $tugas_id)
    {
    try {
        $request->validate([
            'submission_files' => 'required|array',
            'submission_files.*' => 'required', 
        ]);
        
        $peserta_id = Peserta::where('user_id', Auth::user()->id)->value('id');
       
        DB::beginTransaction();

        $submission = Submission::create([
            'peserta_id' => $peserta_id,
            'tugas_id' => $tugas_id,
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        if ($request->hasFile('submission_files')) {
            foreach ($request->file('submission_files') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->store('submission_files', 'public');

                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'nama_file' => $filename,
                    'path_file' => $path,
                ]);
            }
        }

        DB::commit();

        return redirect()->route('peserta.viewDetailTugas', [$plt_kode, $tugas_id])->with('success', 'Tugas berhasil disubmit');
    } catch (\Exception $e) {
        DB::rollback();

        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function update(Request $request, $plt_kode, $tugas_id, $submission_id)
    {
        try {
            DB::beginTransaction();

            $submission = Submission::findOrFail($submission_id);

            foreach ($submission->submission_file as $file) {
                Storage::delete('public/' . $file->path_file);
                $file->delete();
            }

            if ($request->hasFile('submission_files')) {
                foreach ($request->file('submission_files') as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->store('submission_files', 'public');

                    SubmissionFile::create([
                        'submission_id' => $submission->id,
                        'nama_file' => $filename,
                        'path_file' => $path,
                    ]);
                }
            }

            $submission->update(['submitted_at' => now()]);
            
            DB::commit();

            return redirect()
                ->route('peserta.viewDetailTugas', [$plt_kode, $tugas_id])
                ->with('success', 'Data submission berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui data submission.');
        }
    }


    public function delete($plt_kode, $tugas_id, $submission_id)
    {
        $submission = Submission::where('id', $submission_id)->first();
        
        DB::beginTransaction();

        try {
            $submission->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Submission tugas berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus submission tugas');
        }
    }

    public function inputNilai(Request $request, String $plt_kode, String $tugas_id, String $submission_id) {
        try {
            $submission = Submission::where('id', $submission_id)->first();
            
            $submission->update(['nilai' => $request->nilai,
                                 'grading_status' => 'graded']);
            return redirect()->back()->with('success', 'Berhasil menginput nilai');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menginput nilai');
        }
    }

    public function download(Request $request, String $plt_kode, String $tugas_id, $submission_id)
    {
    $submission = Submission::findOrFail($submission_id);
    $zipFileName = $submission->peserta->nama . '.zip';
    $zipFilePath = public_path($zipFileName);

    $zip = new \ZipArchive();
    if ($zip->open($zipFilePath, \ZipArchive::CREATE) === true) {
        foreach ($submission->submission_file as $file) {
            $filePath = public_path(str_replace(url('/'), '', Storage::url($file->path_file)));
            $relativeName = basename($file->nama_file);
            
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $relativeName);
            } else {
                dd('File not found: ' . $filePath);
            }
        }
        $zip->close();
    } else {
        dd('Failed to open or create zip archive');
    }
    
    return response()->download($zipFilePath)->deleteFileAfterSend();
}
 

}