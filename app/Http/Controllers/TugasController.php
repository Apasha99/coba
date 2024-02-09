<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Pelatihan;
use App\Models\Peserta;
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
    public function viewDetailTugas(String $plt_kode, String $tugas_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->where('id', $tugas_id)->first();
        $peserta_id = Peserta::where('user_id', Auth::user()->id)->value('id');
        $submission = Submission::where('tugas_id', $tugas_id)->where('peserta_id', $peserta_id)->first();
        //dd($submission);
        //$submission_files = SubmissionFile::where('submission_id', $submission->id)->get();
        //dd($submission);
        // // Misalnya, untuk mengecek apakah submission sudah ada
        // $submissionExists = $peserta->submissions()->where('tugas_id', $tugasId)->exists();
        
        return view('peserta.detail_tugas', ['pelatihan' => $pelatihan, 'tugas' => $tugas, 'submission' => $submission]);
    }

    public function store(Request $request, String $kode): RedirectResponse {
        $validated = $request->validate([
            'judul' => ['required'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'deskripsi' => ['required', 'max:2000'],
            'file_tugas' => ['max:10240']
        ]);

        if ($request->has('file_tugas')) {
            $fileTugasPath = $request->file('file_tugas')->store('file_tugas', 'public');
            $filename = $request->file('file_tugas')->getClientOriginalName();
            $validated['file_tugas'] = $fileTugasPath;
            $validated['nama_tugas'] = $filename;
        }

        $validated['plt_kode'] = $kode;

        try {
            Tugas::create($validated);
            return redirect()->back()->with('success', 'Data tugas berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
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
       
        $validated = $request->validate([
            'judul' => ['required'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'deskripsi' => ['required', 'max:2000'],
            'file_tugas' => ['max:10240']
        ]);

        //dd($validated);
        try {
            DB::beginTransaction();
    
            $tugas = Tugas::find($id);
    
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
                //dd($filename);
                if ($fileTugasPathLama) {
                    Storage::delete('public/' . $fileTugasPathLama);
                }
            }
    
            $tugas->update(array_filter($updateData));
    
            DB::commit();
    
            return redirect()
                ->route('admin.viewDetailPelatihan', $plt_kode)
                ->with('success', 'Data tugas berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui data tugas.');
        }
    }

    public function delete($plt_kode, $id)
    {
        $tugas = Tugas::where('id', $id)->first();
        
        DB::beginTransaction();

        try {
            $tugas->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Tugas berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus tugas');
        }
    }
}
