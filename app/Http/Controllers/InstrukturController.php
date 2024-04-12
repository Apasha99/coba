<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Bidang;
use App\Models\Instruktur;
use App\Models\Instruktur_Pelatihan;
use App\Models\Pelatihan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstrukturRegistered;
use App\Models\Materi;
use App\Models\Peserta;
use App\Models\Peserta_Pelatihan;
use App\Models\Submission;
use App\Models\Test;
use App\Models\Tugas;

class InstrukturController extends Controller
{
    public function instruktur() {
        if (Auth::user()->role_id === 3) {
            $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->leftJoin('instruktur_pelatihan','instruktur.id','=','instruktur_pelatihan.instruktur_id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username','instruktur_pelatihan.plt_kode')
                ->first();

            $pelatihan = Pelatihan::join('instruktur_pelatihan', 'pelatihan.kode', '=', 'instruktur_pelatihan.plt_kode')
                ->where('instruktur_pelatihan.instruktur_id', $instruktur->id)
                ->get();

            $last_accessed = DB::table('instruktur_pelatihan')
            ->where('instruktur_id', $instruktur->id)
            ->orderBy('last_accessed', 'desc')
            ->take(3)
            ->get();

            $last_accessed_pelatihan = [];

            foreach ($last_accessed as $item) {
                $pelatihan = Pelatihan::where('kode', $item->plt_kode)->first();
                if ($pelatihan) {
                    $last_accessed_pelatihan[] = $pelatihan;
                }
            }
            
            return view('instruktur.dashboard',['instruktur'=>$instruktur, 'pelatihan'=>$pelatihan, 'last_accessed_pelatihan' => $last_accessed_pelatihan]);
        }
    }

    public function viewDaftarPelatihan() {
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->leftJoin('instruktur_pelatihan','instruktur.id','=','instruktur_pelatihan.instruktur_id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username','instruktur_pelatihan.plt_kode')
                ->first();

            $pelatihan = Pelatihan::join('instruktur_pelatihan', 'pelatihan.kode', '=', 'instruktur_pelatihan.plt_kode')
                ->where('instruktur_pelatihan.instruktur_id', $instruktur->id)
                ->where('status', 'On going')
                ->get();
    
            return view('instruktur.daftar_pelatihan', ['instruktur'=>$instruktur, 'pelatihan' => $pelatihan]);
    }

    public function viewHistoryPelatihan() {
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->leftJoin('instruktur_pelatihan','instruktur.id','=','instruktur_pelatihan.instruktur_id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->select('instruktur.nama', 'instruktur.id', 'users.username','instruktur_pelatihan.plt_kode')
                ->first();

        $pelatihan = Pelatihan::join('instruktur_pelatihan', 'pelatihan.kode', '=', 'instruktur_pelatihan.plt_kode')
            ->where('instruktur_pelatihan.instruktur_id', $instruktur->id)
            ->where('status', 'Completed')
            ->get();

        return view('instruktur.history_pelatihan', ['instruktur'=>$instruktur, 'pelatihan' => $pelatihan]);
    }

    public function viewDetailPelatihan(String $plt_kode) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $materi = Materi::where('plt_kode', $plt_kode)->get();
        $tugas = Tugas::where('plt_kode', $plt_kode)->get();
        $test = Test::where('plt_kode', $plt_kode)->get();
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('instruktur.detail_pelatihan', ['bidang'=>$bidang,'pelatihan' => $pelatihan, 'materi' => $materi, 'tugas' => $tugas, 'test' => $test]);
    }

    public function viewDaftarPartisipan(String $plt_kode){
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->pluck('peserta_id');
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->pluck('instruktur_id');
        $allPeserta = Peserta::whereNotIn('id', $pesertaTerdaftar)->get();
        $allInstruktur = Instruktur::whereNotIn('id', $instrukturTerdaftar)->get();
        $pesertaTerdaftar = Peserta_Pelatihan::where('plt_kode', $plt_kode)->get();
        $instrukturTerdaftar = Instruktur_Pelatihan::where('plt_kode', $plt_kode)->get();
    
        $bidang = Bidang::join('pelatihan','pelatihan.bidang_id','=','bidang.id')
                        ->where('kode',$plt_kode)->select('bidang.nama as bidang_nama')->first()->bidang_nama;
        return view('instruktur.daftar_partisipan', ['bidang' => $bidang, 'pelatihan' => $pelatihan, 
                                                'pesertaTerdaftar' => $pesertaTerdaftar, 'instrukturTerdaftar' => $instrukturTerdaftar,
                                                'allPeserta' => $allPeserta, 'allInstruktur' => $allInstruktur]);
    }

    public function viewTambahMateri($plt_kode){
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('instruktur.tambah_materi', ['pelatihan' => $pelatihan]);
    }

    public function viewEditMateri($plt_kode, $id){
        $materi = Materi::find($id);
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('instruktur.edit_materi', ['materi' => $materi, 'pelatihan' => $pelatihan]);
    }

    public function viewTambahTugas($plt_kode){
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('instruktur.tambah_tugas', ['pelatihan' => $pelatihan]);
    }

    public function viewEditTugas($plt_kode, $id){
        $tugas = Tugas::find($id);
        $pelatihan = Pelatihan::where('kode',$plt_kode)->first();
        return view('instruktur.edit_tugas', ['tugas' => $tugas, 'pelatihan' => $pelatihan]);
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
       
        return view('instruktur.daftar_submission_tugas', [
            'pelatihan' => $pelatihan,
            'tugas' => $tugas,
            'submissions' => $submissions,
            'peserta' => $peserta,
            'submission_peserta' => $submission_peserta
        ]);
    }

    public function daftar_instruktur()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
            ->select('instruktur.user_id as instruktur_id','instruktur.nama as instruktur_nama', 'instruktur.bidang', 'users.foto', 'users.password_awal', 'instruktur.id', 'users.username', 'users.email', 'users.password_awal')
            ->get();

        $instruktur2 = User::join('instruktur', 'instruktur.user_id', '=', 'users.id')
            ->where('users.role_id', '=', 3)
            ->select('instruktur.user_id as users_id', 'instruktur.nama')
            ->get();

        //dd($ins);

        $pelatihan = Pelatihan::select('kode','nama','status')->where('status','!=','Completed')->get();

        return view('admin.daftar_instruktur', ['admin' => $admin, 'instruktur' => $instruktur,'instruktur2'=>$instruktur2,'pelatihan'=>$pelatihan]);
    }

    public function detail_instruktur($instruktur_id)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();
        
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
            ->where('instruktur.user_id',$instruktur_id)
            ->select('instruktur.user_id as instruktur_id','instruktur.nama as instruktur_nama', 'instruktur.bidang', 'users.foto', 'users.password_awal', 'instruktur.id', 'users.username', 'users.email', 'users.password_awal')
            ->first();

        $pltins = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
            ->leftjoin('instruktur_pelatihan','instruktur_pelatihan.instruktur_id', '=','instruktur.id')
            ->leftjoin('pelatihan','pelatihan.kode','=','instruktur_pelatihan.plt_kode')
            ->where('instruktur.user_id',$instruktur_id)
            ->select('instruktur_pelatihan.plt_kode','pelatihan.nama as pltnama')
            ->get();

        $ins = User::where('role_id', '=', 3)->select('id')->get();

        $pelatihan = Pelatihan::select('kode','nama')->get();

        return view('admin.detail_instruktur', ['pltins'=>$pltins,'admin' => $admin, 'instruktur' => $instruktur,'ins'=>$ins,'pelatihan'=>$pelatihan]);
    }

    public function viewTambahInstruktur(){
        $bidang = Bidang::get();
        return view('admin.tambah_instruktur', ['bidang' => $bidang]);
    }

    public function viewEditInstruktur($user_id){
        $bidang = Bidang::get();
        $instruktur = Instruktur::where('user_id', $user_id)->first();
        
        return view('admin.edit_instruktur', ['instruktur' => $instruktur, 'bidang' => $bidang]);
    }

    public function store(Request $request): RedirectResponse {
        $validated = $request->validate([
            'nama' => ['required'],
            'email' => ['required', 'unique:users,email','email'],
            'bidang' => ['required'],
        ]);        
        //dd($validated);

        DB::beginTransaction(); 
        
        try {
            $username = Str::slug($request->nama, ''); 
            $username .= Str::random(4);
            $password = Str::random(8);

            $user = User::create([
                'username' => $username,
                'email' => $request->email,
                'password' => $password,
                'password_awal' => $password,
                'role_id' => 3,
            ]);

            $instruktur = new Instruktur();
            $instruktur->nama = $validated['nama'];
            $instruktur->bidang = $validated['bidang'];
            $instruktur->user_id = $user->id;
            $instruktur->save();
        
            DB::commit(); 
    
            return redirect()->route('admin.viewDaftarInstruktur')
                ->with('success', 'Data instruktur berhasil ditambahkan');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback(); 
            return redirect()->route('admin.viewDaftarInstruktur')
                ->with('error', 'Gagal menambahkan data instruktur.');
        }
    }

    public function searchInstruktur(Request $request)
    {
        //dd($request);
        $pelatihan = Pelatihan::select('kode','nama')->get();
        $search = $request->input('search');
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $instruktur2 = User::join('instruktur', 'instruktur.user_id', '=', 'users.id')
                ->where('users.role_id', '=', 3)
                ->select('instruktur.user_id as users_id', 'instruktur.nama')
                ->get();
        $instruktur = Instruktur::leftjoin('users','users.id','=','instruktur.user_id')
            ->select('instruktur.nama as instruktur_nama', 'users.username','users.email','instruktur.user_id as instruktur_id','password_awal')
            ->where(function ($query) use ($search) {
                $query
                    ->where('instruktur.nama', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%')
                    ->orWhere('instruktur.user_id', 'like', '%' . $search . '%');
            })
            ->get();

        return view('admin.daftar_instruktur', ['instruktur2'=>$instruktur2,'pelatihan'=>$pelatihan,'instruktur' => $instruktur, 'admin' => $admin, 'search' => $search]);
    }

    public function update(Request $request, $id)
    {
        $instruktur = Instruktur::leftJoin('users', 'users.id', '=', 'instruktur.user_id')
                            ->where('instruktur.user_id', $id)
                            ->select('instruktur.id as id', 'user_id','nama','username', 'email',
                            'password','password_awal')
                            ->first();
        //dd($instruktur);
        if (!$instruktur) {
            return redirect()->route('admin.viewDaftarInstruktur')->with('error', 'Tidak dapat menemukan instruktur yang ingin diedit.');
        }

        $validated = $request->validate([
            'nama' => ['required'],
            'username' => ['required'],
            'email' => ['required', 'email'],
            'bidang' => ['required'],
            'new_password' => ['nullable', 'min:8', 'string'],
            'conf_password' => ['nullable', 'same:new_password'],
        ]);
        //dd($request);
        
        try {
            DB::beginTransaction();

            $updateData = [
                'nama' => $validated['nama'],
                'bidang' => $validated['bidang']
            ];
            //dd($updateData);
            $instruktur->update(array_filter($updateData));
            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
            ];

            if ($request->has('new_password')) {
                $updateData2['password'] = ($validated['new_password']);
            }

            $instruktur->user->update(array_filter($updateData2));

            DB::commit();

            return redirect()
                ->route('admin.viewDaftarInstruktur')
                ->with('success', 'Data instruktur berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data instruktur');
        }
    }

    public function delete(Request $request, String $instruktur_id)
    {
        try {
            $instruktur = Instruktur::leftJoin('users', 'users.id', '=', 'instruktur.user_id')
                ->where('instruktur.user_id', $instruktur_id)
                ->select('instruktur.user_id','users.id')
                ->first();

            $instruktur_pelatihan = Instruktur_Pelatihan::leftJoin('instruktur', 'instruktur.id', '=', 'instruktur_pelatihan.instruktur_id')
                ->leftJoin('users', 'users.id', '=', 'instruktur.user_id')
                ->leftJoin('pelatihan', 'pelatihan.kode', '=', 'instruktur_pelatihan.plt_kode')
                ->where('instruktur.user_id', $instruktur_id)
                ->select('instruktur.user_id', 'users.id','instruktur.nama', 'pelatihan.status', 'instruktur_pelatihan.plt_kode')
                ->first();
            //dd($instruktur_pelatihan);

            DB::beginTransaction();

            try {
                if ($instruktur_pelatihan && $instruktur_pelatihan->status == 'On going') {
                    return redirect()->route('admin.viewDaftarInstruktur')->with('error', 'Tidak dapat menghapus instruktur dengan pelatihan yang masih berlangsung.');
                }

                
                Instruktur::where('user_id', $instruktur_id)->delete();
                User::where('id', $instruktur_id)->delete();

                DB::commit();

                return redirect()->route('admin.viewDaftarInstruktur')->with('success', 'Instruktur dan semua data terkait berhasil dihapus');
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);

                return redirect()->route('admin.viewDaftarInstruktur')->with('error', 'Terjadi kesalahan saat menghapus instruktur dan data terkait');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('admin.viewDaftarInstruktur')->with('error', 'Tidak dapat menemukan instruktur yang ingin dihapus.');
        }
    }

    public function tambah()
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $instruktur = Instruktur::select('user_id', 'nama', 'noHP', 'alamat')
            ->whereNull('user_id')
            ->get();

        return view('admin.import_instruktur', compact('admin', 'instruktur'));
    }

    public function sendEmail(Request $request)
    {
        //dd($request);
        // Validasi input
        $request->validate([
            'subjek' => 'required|string',
            'deliver_option' => 'required|in:all,range',
            'start_user_id' => 'required_if:deliver_option,range|exists:users,id',
            'end_user_id' => 'required_if:deliver_option,range|exists:users,id', 'after_or_equal:start_user_id',
            'kode' => 'required|exists:pelatihan,kode',
        ]);    
        
        $fromName = 'Dinas Kominfo Kota Semarang';
        $subjek = $request->input('subjek');
        $kode = $request->input('kode');

        $exportOption = $request->input('deliver_option');
        $startUserId = $request->input('start_user_id');
        $endUserId = $request->input('end_user_id');

        $users = null;

        if ($exportOption === 'all') {
            $users = User::where('role_id', '=', 3)->get();
        } elseif ($exportOption === 'range' && $startUserId && $endUserId) {
            // Ambil user dalam rentang tertentu
            $users = User::where('role_id', '=', 3)
                ->whereBetween('id', [$startUserId, $endUserId])
                ->get();
        } else {
            return redirect()->back()->with('error', 'Opsi pengiriman tidak valid');
        }

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih');
        }

        // Loop melalui pengguna dan kirim email
        foreach ($users as $user) {
            $username = $user->username;
            $password = $user->password_awal;
            $toAddress = $user->email;
            
            Mail::to($toAddress)
                ->send(new InstrukturRegistered($username, $password, $kode,$fromName, $subjek));
        }

        return redirect()->back()->with('success', 'Berhasil mengirim email');
    }

    public function download(Request $request)
    {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
            ->where('admin.user_id', Auth::user()->id)
            ->select('admin.nama', 'admin.id', 'users.username')
            ->first();

        $instruktur2 = User::join('instruktur', 'instruktur.user_id', '=', 'users.id')
            ->where('users.role_id', '=', 3)
            ->select('instruktur.user_id as users_id', 'instruktur.nama')
            ->get();
        $request->validate([
            'export_option' => 'required',
            'start_user_id' => 'required_if:export_option,range|exists:users,id',
            'end_user_id' => 'required_if:export_option,range|exists:users,id|after_or_equal:start_user_id',
        ]);

        $exportOption = $request->input('export_option');
        $startUserId = $request->input('start_user_id');
        $endUserId = $request->input('end_user_id');

        $query = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
            ->select('instruktur.nama', 'instruktur.bidang','instruktur.user_id as instruktur_id', 'users.username', 'users.foto', 'users.password_awal', 'users.email');

        // Filter berdasarkan opsi yang dipilih
        if ($exportOption === 'range' && $startUserId && $endUserId) {
            // Filter berdasarkan rentang user_id
            $query->whereBetween('instruktur.user_id', [$startUserId, $endUserId]);
        }

        $instruktur = $query->get(); // Mengambil data setelah filter

        $pst = User::where('role_id', '=', 3)->select('id')->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_instruktur', [
            'admin' => $admin,
            'instruktur'=>$instruktur,
            'instruktur2' => $instruktur2,
            'pst' => $pst,
        ]);

        $filename = $exportOption === 'range' ? 'daftar-list-instruktur-range.pdf' : 'daftar-list-instruktur.pdf';

        return $pdf->stream($filename);
    }

    public function ubahPassword(){
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                    ->where('instruktur.user_id', Auth::user()->id)
                    ->first();
    
        return view('instruktur.ubah_password', compact('instruktur'));
    }
    

    public function updatePassword(Request $request, $instruktur_id){
        $request->validate([
            'password' => 'required',
            'new_password' => 'required|min:8|string',
            'conf_password' => 'required|same:new_password',
        ]);
    
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                ->where('instruktur.user_id', Auth::user()->id)
                ->where('user_id',$instruktur_id)
                ->first();
    
        if (!$instruktur) {
            return redirect()->back()->with('error' ,'Instruktur not found');
        }
    
        // Verifikasi password yang dimasukkan dengan password_awal
        if (!Hash::check($request->input('password'), $instruktur->password)) {
            return redirect()->back()->with('error' ,'Current password does not match');
        }
    
        try{
            DB::beginTransaction();
    
            $updateData = [];
    
            if ($request->has('new_password')) {
                $updateData['password'] = Hash::make($request->input('new_password'));
                $updateData['password_awal'] = $request->input('new_password');
            }
    
            $instruktur->user()->update($updateData);
    
            DB::commit();
    
            return redirect()->back()->with('success','Password berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error','Gagal update password');
        }
    }
    
    public function profil(){
        $instruktur = Instruktur::where('instruktur.user_id', Auth::user()->id)
                    ->first();
        return view('instruktur.profil', compact('instruktur'));
    }

    public function editprofil(){
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                    ->where('instruktur.user_id', Auth::user()->id)
                    ->first();
        return view('instruktur.edit_profil', compact('instruktur'));
    }

    public function updateProfil(Request $request, $instruktur_id){
        $instruktur = Instruktur::leftJoin('users', 'instruktur.user_id', '=', 'users.id')
                    ->where('instruktur.user_id', Auth::user()->id)
                    ->where('user_id',$instruktur_id)
                    ->first();

        $validated = $request->validate([
            'username' => ['required'],
            'email' => ['required', 'email'],
            'foto' => [ 'max:10240'],
        ]);
        //dd($validated);
        try {
            DB::beginTransaction();

            $updateData2 = [
                'username' => $validated['username'] ?? null,
                'email' => $validated['email'] ?? null,
                'foto' => $validated['foto'] ?? null,
            ];

            if ($request->has('foto')) {
                $fotoPath = $request->file('foto')->store('foto', 'public');
                $updateData2['foto'] = $fotoPath;
            }

            $instruktur->user->update(array_filter($updateData2));

            DB::commit();

            return redirect()
                ->route('instruktur.profil')
                ->with('success', 'Data user berhasil diperbarui');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui data user');
        }
    }
    

}
