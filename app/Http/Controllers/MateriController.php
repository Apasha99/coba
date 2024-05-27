<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Notifikasi;
use App\Models\Pelatihan;
use App\Models\Peserta_Pelatihan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MateriController extends Controller
{
    public function viewTambahMateri($plt_kode){
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('admin.tambah_materi', ['pelatihan' => $pelatihan]);
    }

    
    public function store(Request $request, String $kode): RedirectResponse {
        $validated = $request->validate([
            'judul' => ['required'],
            'file_materi' => ['required', 'max:10240']
        ]);
    
        if ($request->has('file_materi')) {
            $fileMateriPath = $request->file('file_materi')->store('file_materi', 'public');
            $filename = $request->file('file_materi')->getClientOriginalName();
            $validated['file_materi'] = $fileMateriPath;
            $validated['nama_file'] = $filename;
            // dd($validated['nama_file']);
        }

        $validated['plt_kode'] = $kode;
        $pelatihan = Pelatihan::where('kode', $kode)->first();
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');
        //dd($peserta_ids);

        try {
            Materi::create($validated);

            // Buat notifikasi untuk setiap peserta yang terkait dengan pelatihan ini
            foreach ($peserta_ids as $peserta_id) {
                Notifikasi::create([
                    'judul' => 'Materi',
                    'subjudul' => 'Ada materi baru: ' . $validated['judul'],
                    'plt_kode' => $kode,
                    'peserta_id' => $peserta_id,
                    'isChecked' => 0,
                ]);
            }
            //dd($peserta_ids);
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $kode)->with('success', 'Data materi berhasil disimpan');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $kode)->with('success', 'Data materi berhasil disimpan');
            }
        } catch (\Exception $e) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $kode)->with('success', 'Gagal menyimpan data materi');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $kode)->with('success', 'Gagal menyimpan data materi');
            }
        }
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
        $pelatihan2 = Pelatihan::where('kode', $plt_kode)->first();
        $peserta_ids = $pelatihan2->peserta_pelatihan()->pluck('peserta_id');
        $validated = $request->validate([
            'judul' => ['required'],
            'file_materi' => ['max:10240']
        ]);

        try {
            DB::beginTransaction();
    
            $updateData = [
                'judul' => $validated['judul'] ?? null
            ];

            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Materi')
                    ->where(function($query) use ($materi) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada materi baru: ', -1) = ?", [$materi->judul])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan materi: ', -1) = ?", [$materi->judul]);
                    })->first();
            
                if ($notifikasi) {
                    // Perbarui subjudul notifikasi
                    $notifikasi->subjudul = 'Ada pembaharuan materi: ' . $validated['judul'];
                    $notifikasi->isChecked = 0;
                    $notifikasi->save();
                } else {
                    // Buat notifikasi baru karena tidak ada notifikasi sebelumnya
                    $notifikasiBaru = new Notifikasi();
                    $notifikasiBaru->plt_kode = $plt_kode;
                    $notifikasiBaru->peserta_id = $peserta_id;
                    $notifikasiBaru->judul = 'Materi';
                    $notifikasiBaru->subjudul = 'Ada pembaharuan materi: ' . $validated['judul'];
                    $notifikasiBaru->isChecked = 0;
                    $notifikasiBaru->save();
                }
            }                        
    
            if ($request->hasFile('file_materi')) {
                $fileMateriPath = $request->file('file_materi')->store('file_materi', 'public');
                $filename = $request->file('file_materi')->getClientOriginalName();
                $updateData['file_materi'] = $fileMateriPath;
                $updateData['nama_file'] = $filename;
            }

            $materi->update(array_filter($updateData));
            
            DB::commit();

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $plt_kode)->with('success', 'Data materi berhasil diperbarui');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $plt_kode)->with('success', 'Data materi berhasil diperbarui');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.viewDetailPelatihan', $plt_kode)->with('success', 'Gagal memperbarui data materi');
            } else {
                return redirect()->route('instruktur.viewDetailPelatihan', $plt_kode)->with('success', 'Gagal memperbarui data materi');
            }
        }
    }

    public function delete($plt_kode, $id)
    {
        $materi = Materi::findOrFail($id);
        $pelatihan = Pelatihan::where('kode', $plt_kode)->firstOrFail();
        $peserta_ids = $pelatihan->peserta_pelatihan()->pluck('peserta_id');

        DB::beginTransaction();

        try {
            foreach ($peserta_ids as $peserta_id) {
                $notifikasi = Notifikasi::where('plt_kode', $plt_kode)
                    ->where('peserta_id', $peserta_id)
                    ->where('judul', '=', 'Materi')
                    ->where(function($query) use ($materi) {
                        $query->whereRaw("SUBSTRING_INDEX(subjudul, 'Ada materi baru: ', -1) = ?", [$materi->judul])
                            ->orWhereRaw("SUBSTRING_INDEX(subjudul, 'Ada pembaharuan materi: ', -1) = ?", [$materi->judul]);
                    })->delete();
            }

            $materi->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Materi berhasil dihapus');
            
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus materi');
        }
    }

}
