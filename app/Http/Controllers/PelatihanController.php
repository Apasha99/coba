<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Admin;
use App\Models\Jawaban_Test;
use App\Models\Materi;
use App\Models\Attempt;
use App\Models\Bidang;
use App\Models\Instruktur;
use App\Models\Instruktur_Pelatihan;
use App\Models\Jawaban_User_Pilgan;
use App\Models\Jawaban_User_Singkat;
use App\Models\Nilai_Test;
use App\Models\Peserta;
use App\Models\Peserta_Pelatihan;
use App\Models\Soal_Test;
use App\Models\Test;
use App\Models\Tugas;
use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PelatihanController extends Controller
{
    public function create(){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $bidang = Bidang::all();
        if($admin){
            $pelatihan = Pelatihan::all();
            return view('admin.tambah_pelatihan', ['bidang'=>$bidang,'admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

    public function store(Request $request): RedirectResponse {
        //dd($request->poster);
        //dd($request);
        $validated = $request->validate([
            'kode' => ['required', 'regex:/^[A-Z0-9]{6}$/',Rule::unique('pelatihan')],
            'nama' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'bidang_id'=> ['required'],
            'status' => ['required', 'in:Not started yet,On going,Completed'],
            'tempat' => ['required'],
            'deskripsi' => ['required', 'max:255'],
        ]);
        
        if ($request->has('poster')) {
            $posterPath = $request->file('poster')->store('poster', 'public');
            $validated['poster'] = $posterPath;
        }

        // Proses penyimpanan data jika validasi berhasil
        Pelatihan::create($validated);
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Data pelatihan berhasil disimpan');
    }
 
    public function delete(Request $request, String $plt_kode)
    {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();

        if (!$pelatihan) {
            return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menemukan pelatihan yang ingin dihapus.');
        }
        $materi = Materi::where('plt_kode',$plt_kode)->get();
        // Memeriksa apakah ada tugas yang terkait dengan pelatihan
        $tugas = Tugas::where('plt_kode', $plt_kode)->first();

        // Memeriksa apakah ada test yang terkait dengan pelatihan
        $test = Test::where('plt_kode', $plt_kode)->first();

        // Memeriksa apakah ada submission untuk tugas atau test yang terkait dengan pelatihan
        $submissiontugas = Tugas::where('plt_kode', $plt_kode)->exists();

        $submissiontest = Soal_Test::whereHas('test', function ($query) use ($plt_kode) {
            $query->where('plt_kode', $plt_kode);
        })->exists();

        // Membuka transaksi database
        DB::beginTransaction();

        try {
            // Memeriksa apakah pelatihan memiliki tugas atau test yang masih aktif
            if ($test && $test->status == 1) {
                return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menghapus pelatihan dengan test yang masih aktif');
            }

            // Memeriksa apakah ada submission untuk tugas atau test terkait dengan pelatihan
            if ($submissiontugas) {
                return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Pelatihan gagal dihapus karena terdapat data tugas');
            }else{
                $materi->delete();
                $pelatihan->delete();
            }

            if ($submissiontest) {
                return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Pelatihan gagal dihapus karena terdapat data test.');
            }else{
                $materi->delete();
                $pelatihan->delete();
            }


            // Menghapus semua data terkait dengan pelatihan
            if ($tugas) {
                // Menghapus submission dan file terkait
                Submission::where('tugas_id', $tugas->id)->delete();
                SubmissionFile::where('tugas_id', $tugas->id)->delete();

                // Menghapus tugas dan semua data terkait
                $tugas->delete();
            }

            if ($test) {
                // Menghapus semua data terkait dengan test
                Attempt::where('test_id', $test->id)->delete();
                Jawaban_User_Pilgan::where('test_id', $test->id)->delete();
                Jawaban_User_Singkat::where('test_id', $test->id)->delete();
                Nilai_Test::where('test_id', $test->id)->delete();
                Jawaban_Test::where('test_id', $test->id)->delete();
                Soal_Test::where('test_id', $test->id)->delete();

                // Menghapus test dan semua data terkait
                $test->delete();
            }

            // Menghapus pelatihan
            $pelatihan->delete();

            // Commit transaksi database
            DB::commit();

            return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Pelatihan dan semua data terkait berhasil dihapus.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();

            return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Terjadi kesalahan saat menghapus pelatihan dan data terkait.');
        }
    }


    public function edit($plt_id){
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $bidang = Bidang::all();
        $selectedBidangId = Pelatihan::join('bidang','bidang.id','=','pelatihan.bidang_id')->where('pelatihan.id',$plt_id)->select('bidang_id')->first()->bidang_id;;
        //dd($selectedBidangId);
        if($admin){
            $plt = Pelatihan::find($plt_id);
            return view('admin.edit_pelatihan', ['selectedBidangId'=>$selectedBidangId,'bidang'=>$bidang,'admin' => $admin, 'plt' => $plt]);
        }
    }

    public function update(Request $request, $plt_id)
    {
        //dd($request);
        $plt = Pelatihan::find($plt_id);
        if (!$plt) {
            return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menemukan pelatihan yang ingin diedit.');
        }
        //dd($plt);
        $validated = $request->validate([
            'nama' => ['required'],
            'status' => [ 'nullable','in:Not started yet,On going,Completed'],
            'start_date' => ['required'],
            'end_date' => ['required', 'after:start_date'],
            'tempat' => ['nullable'],
            'deskripsi' => ['required', 'max:255'],
            'poster' => [ 'max:10240'],
            'bidang_id'  => ['required'],
        ]);

        try {
            DB::beginTransaction();
    
            $updateData = [
                'nama' => $validated['nama'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'] ?? null,
                'tempat' => $validated['tempat'] ?? null,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'poster' => $validated['poster'] ?? null,
                'bidang_id' => $validated['bidang_id'] ?? null,
            ];
            //dd($updateData);
            if ($request->has('poster')) {
                $posterPath = $request->file('poster')->store('poster', 'public');
                $updateData['poster'] = $posterPath;
            } 
            //dd($posterPath);
            $plt->update(array_filter($updateData));
    
            DB::commit();

            return redirect()
                ->route('admin.viewDaftarPelatihan')
                ->with('success', 'Data pelatihan berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data pelatihan.');
        }
    }

    public function joinPelatihan(Request $request) {
        //dd($request);
        $validated = $request->validate([
            'kode' => ['required', 'exists:pelatihan,kode'],
        ]);
        //dd($validated);
        // Cek apakah kode pelatihan valid dan masih terbuka untuk pendaftaran
        $pelatihan = Pelatihan::where('kode', $validated['kode'])
            ->where('status', 'On going')
            ->first();
    
        if (!$pelatihan) {
            return redirect()->back()->with('error', 'Kode pelatihan tidak valid atau pelatihan sudah ditutup untuk pendaftaran');
        }
    
        // Tambahkan peserta ke tabel Peserta_Pelatihan
        $pesertaPelatihan = new Peserta_Pelatihan();
        $pesertaPelatihan->peserta_id = Auth::user()->peserta->id; // Ganti dengan cara Anda untuk mendapatkan ID peserta saat ini
        $pesertaPelatihan->plt_kode = $validated['kode'];
        $pesertaPelatihan->save();
    
        // Redirect ke halaman atau tindakan yang sesuai setelah bergabung dengan pelatihan
        return redirect()->route('peserta.dashboard')->with('success', 'Berhasil bergabung dengan pelatihan!');
    }

    public function searchPelatihan(Request $request)
    {
        //dd($request);
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();

        $pelatihan = Pelatihan::select('nama', 'status', 'kode','id')
            ->where(function ($query) use ($search) {
                $query
                    ->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('kode', 'like', '%' . $search . '%');
            })
            ->get();

        return view('admin.daftar_pelatihan', ['pelatihan' => $pelatihan, 'admin' => $admin, 'search' => $search]);
    }

    public function inviteInstruktur(Request $request, String $plt_kode)
    {
        try {
            $request->validate([
                'instruktur_ids' => 'required|array',
                'instruktur_ids.*' => 'exists:instruktur,id',
            ]);

            $instrukturIds = $request->instruktur_ids;

            foreach ($instrukturIds as $instrukturId) {
                Instruktur_Pelatihan::create([
                    'instruktur_id' => $instrukturId,
                    'plt_kode' => $plt_kode,
                ]);
            }

            return redirect()->route('admin.viewDaftarPartisipan', $plt_kode)->with('success', 'Instruktur berhasil diundang');
        } catch (\Exception $e) {
            return redirect()->route('admin.viewDaftarPartisipan', $plt_kode)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function invitePeserta(Request $request, String $plt_kode)
    {
        try {
            $request->validate([
                'peserta_ids' => 'required|array',
                'peserta_ids.*' => 'exists:peserta,id',
            ]);

            $pesertaIds = $request->peserta_ids;

            foreach ($pesertaIds as $pesertaId) {
                Peserta_Pelatihan::create([
                    'peserta_id' => $pesertaId,
                    'plt_kode' => $plt_kode,
                ]);
            }

            return redirect()->route('admin.viewDaftarPartisipan', $plt_kode)->with('success', 'Peserta berhasil diundang');
        } catch (\Exception $e) {
            return redirect()->route('admin.viewDaftarPartisipan', $plt_kode)->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function removeInstruktur($plt_kode, $instruktur_id)
    {
        try {
            Instruktur_Pelatihan::where('plt_kode', $plt_kode)
                                ->where('instruktur_id', $instruktur_id)
                                ->delete();
            
            return redirect()->back()->with('success', 'Instruktur berhasil dihapus dari pelatihan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus instruktur dari pelatihan. Error: ' . $e->getMessage());
        }
    }

    public function removePeserta($plt_kode, $peserta_id)
    {
        try {
            Peserta_Pelatihan::where('plt_kode', $plt_kode)
                                ->where('peserta_id', $peserta_id)
                                ->delete();
            
            return redirect()->back()->with('success', 'Peserta berhasil dihapus dari pelatihan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus peserta dari pelatihan. Error: ' . $e->getMessage());
        }
    }

}
