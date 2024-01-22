<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Pelatihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriController extends Controller
{
    public function store(Request $request, String $kode): RedirectResponse {
        $validated = $request->validate([
            'judul' => ['required'],
            'file_materi' => ['required', 'max:10240']
        ]);
       
        if ($request->has('file_materi')) {
            $fileMateriPath = $request->file('file_materi')->store('file_materi', 'public');
            $validated['file_materi'] = $fileMateriPath;
        }

        $validated['plt_kode'] = $kode;

        Materi::create($validated);
    
        return redirect()->back()->with('success', 'Data materi berhasil disimpan');
    }

    public function viewEdit($plt_kode, $id){
        $materi = Materi::find($id);
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('admin.edit_materi', ['materi' => $materi, 'pelatihan' => $pelatihan]);
    }

    public function update(Request $request, $plt_kode, $id)
    {
        $materi = Materi::find($id);
        $pelatihan = Pelatihan::find($plt_kode);
       
        $validated = $request->validate([
            'judul' => ['required'],
            'file_materi' => ['max:10240']
        ]);

        try {
            DB::beginTransaction();
    
            $updateData = [
                'judul' => $validated['judul'] ?? null
            ];
    
            if ($request->hasFile('file_materi')) {
                $fileMateriPath = $request->file('file_materi')->store('file_materi', 'public');
                $updateData['file_materi'] = $fileMateriPath;
            }
    
            $materi->update(array_filter($updateData));
    
            DB::commit();

            return redirect()
                ->route('admin.viewDetailPelatihan', $plt_kode)
                ->with('success', 'Data materi berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui data materi.');
        }
    }

    public function delete($plt_kode, $id)
    {
        $materi = Materi::where('id', $id)->first();
        
        DB::beginTransaction();

        try {
            $materi->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Materi berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus materi');
        }
    }
}
