<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Admin;
use App\Models\Jawaban_Test;
use App\Models\Materi;
use App\Models\Bidang;
use App\Models\Instruktur;
use App\Models\Instruktur_Pelatihan;
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
    public function viewDetailPelatihanPeserta(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $peserta = Peserta::where('user_id', Auth::user()->id)->first();

        $done = 0;
        foreach ($tugas as $tgs) {
            if ($tgs->submissions()->where('peserta_id', $peserta->id)->first()) {
                $done++;
            }
        }

        $completed = false;
        if ($done == count($tugas)) {
            $completed = true;
        }

        return view('peserta.detail_pelatihan', ['pelatihan' => $pelatihan, 'materi' => $materi, 
                                                'tugas' => $tugas, 'test' => $test, 'peserta' => $peserta,
                                                'completed' => $completed]);
    }

    public function viewDetailPelatihanAdmin(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('admin.detail_pelatihan', ['bidang'=>$bidang,'pelatihan' => $pelatihan, 'materi' => $materi, 'tugas' => $tugas, 'test' => $test]);
    }

    public function viewDaftarPartisipan(String $plt_kode){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->pluck('peserta_id');
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->pluck('instruktur_id');
        $allPeserta = Peserta::whereNotIn('id', $pesertaTerdaftar)->get();
        $allInstruktur = Instruktur::whereNotIn('id', $instrukturTerdaftar)->get();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->get();
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->get();
        //dd($allPeserta);
    
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('admin.daftar_partisipan', ['bidang' => $bidang, 'pelatihan' => $pelatihan, 
                                                'pesertaTerdaftar' => $pesertaTerdaftar, 'instrukturTerdaftar' => $instrukturTerdaftar,
                                                'allPeserta' => $allPeserta, 'allInstruktur' => $allInstruktur]);
    }
    

    public function viewDaftarPelatihan() {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
    
        if($admin){
            $pelatihan = Pelatihan::all();
    
            // Menggunakan loop untuk menghitung peserta pelatihan untuk setiap pelatihan
            foreach ($pelatihan as $p) {
                $kodePelatihan = $p->kode;
    
                // Menghitung peserta_pelatihan berdasarkan kode_pelatihan
                $pesertaPelatihan = Peserta_Pelatihan::where('plt_kode', $kodePelatihan)->count();
    
                // Menambahkan informasi pesertaPelatihan ke dalam objek pelatihan
                $p->pesertaPelatihan = $pesertaPelatihan;
            }
    
            return view('admin.daftar_pelatihan', ['admin' => $admin, 'pelatihan' => $pelatihan]);
        }
    }

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

        //dd($validated);
       
       
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

        $test = Test::where('plt_kode', $plt_kode)->first();
        $tugas = Tugas::where('plt_kode', $plt_kode)->first();

        DB::beginTransaction();

        try {
            if ($test && $test->status == 1) {
                return redirect()->route('admin.viewDaftarPelatihan')->with('error', 'Tidak dapat menghapus pelatihan dengan test yang masih aktif.');
            }

            if (!$tugas) {
                $pelatihan->delete();

                DB::commit();

                return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Pelatihan berhasil dihapus karena tidak ada tugas terkait.');
            }


            if (!$test) {
                $pelatihan->delete();

                DB::commit();

                return redirect()->route('admin.viewDaftarPelatihan')->with('success', 'Pelatihan berhasil dihapus karena tidak ada test terkait.');
            }

            if ($pelatihan->status !== 'On going') {
                // Delete related data if status is not 'On going'
                Nilai_Test::where('test_id', $test)->delete();
                Jawaban_Test::where('test_id', $test)->delete();
                Soal_Test::where('test_id', $test)->delete();
                Submission::where('tugas_id', $tugas)->delete();
                SubmissionFile::where('tugas_id', $tugas)->delete();
                Test::where('plt_kode', $plt_kode)->delete();
                Materi::where('plt_kode', $plt_kode)->delete();
                Tugas::where('plt_kode', $plt_kode)->delete();
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

}
