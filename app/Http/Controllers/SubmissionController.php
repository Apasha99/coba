<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\SubmissionFiles;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    public function viewSubmissionForm(String $plt_kode, String $tugas_id)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        return view('peserta.submit_tugas', ['pelatihan' => $pelatihan, 'tugas' => $tugas]);
    }

    public function store(Request $request, String $plt_kode, String $tugas_id)
    {
       // dd($request);
        // $request->validate([
        //     'submission_files[]' => 'required|mimetypes:*/*|max:5120',
        // ]);
        
        $peserta_id = Peserta::where('id', Auth::user()->id)->value('id');
        //dd($request);
        $submission = Submission::create([
            'peserta_id' => $peserta_id,
            'tugas_id' => $tugas_id,
            'status' => 'submitted',
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

        return redirect()->route('peserta.viewDetailTugas', [$plt_kode, $tugas_id])->with('success', 'Tugas berhasil disubmit');
    }
}
