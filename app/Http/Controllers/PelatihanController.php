<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Admin;
use App\Models\Jawaban_Test;
use App\Models\Materi;
use App\Models\Nilai_Test;
use App\Models\Peserta_Pelatihan;
use App\Models\Soal_Test;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PelatihanController extends Controller
{
    public function viewDetailPelatihanPeserta(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('peserta.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function viewDaftarPelatihan() {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        if($admin){
            $pelatihan = Pelatihan::all();
            return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

    public function create(){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        if($admin){
            $pelatihan = Pelatihan::all();
            return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

    public function store(Request $request): RedirectResponse {
        //dd($request->poster);
        $validated = $request->validate([
            'kode' => ['required', 'regex:/^[A-Z0-9]{6}$/',Rule::unique('pelatihan')],
            'nama' => ['required'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:Not started yet,On going,Completed'],
            'penyelenggara' => ['required'],
            'tempat' => ['required', 'in:Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8,
            Gedung Balaikota,Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8,
            Gedung Juang 45,Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8,
            Ruang Rapat Lantai 4,Hall Balaikota Semarang,Halaman Balaikota Semarang,
            Ruang Rapat Lantai 6 Siber Pungli'],
            'deskripsi' => ['required', 'max:255'],
            'poster' => ['required', 'max:10240']
        ]);
        
        if ($request->has('poster')) {
            $posterPath = $request->file('poster')->store('poster', 'public');
            $validated['poster'] = $posterPath;
        }

        //dd($validated);
       
       
        // Proses penyimpanan data jika validasi berhasil
        Pelatihan::create($validated);
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Data pelatihan berhasil disimpan');
    }
    

    public function viewDetailPelatihanAdmin(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        return view('admin.detail_pelatihan',['pelatihan'=>$pelatihan]);
    }

    public function delete(Request $request, String $plt_kode)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();

        if (!$pelatihan) {
            return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menemukan pelatihan yang ingin dihapus.');
        }

        $test = Test::where('plt_kode', $plt_kode)->first();

        // Use DB transaction to ensure data consistency
        DB::beginTransaction();

        try {
            if ($test && $test->status == 1) {
                return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menghapus pelatihan dengan test yang masih aktif.');
            }

            if (!$test) {
                // Jika kode pelatihan tidak ditemukan di tabel Test, hapus pelatihan saja
                $pelatihan->delete();

                // Commit the transaction
                DB::commit();

                return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Pelatihan berhasil dihapus karena tidak ada test terkait.');
            }

            if ($pelatihan->status !== 'On going') {
                // Delete related data if status is not 'On going'
                Nilai_Test::where('test_id', $test)->delete();
                Jawaban_Test::where('test_id', $test)->delete();
                Soal_Test::where('test_id', $test)->delete();
                Test::where('plt_kode', $plt_kode)->delete();
                Materi::where('plt_kode', $plt_kode)->delete();
                Peserta_Pelatihan::where('plt_kode', $plt_kode)->delete();
            }

            // Finally, delete the pelatihan itself
            $pelatihan->delete();

            // Commit the transaction
            DB::commit();

            return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Pelatihan dan semua data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Rollback the transaction in case of any error
            DB::rollback();

            return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Terjadi kesalahan saat menghapus pelatihan dan data terkait.');
        }
    }



}
