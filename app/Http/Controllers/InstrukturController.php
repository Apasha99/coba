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
      
            return view('instruktur.dashboard',['instruktur'=>$instruktur, 'pelatihan'=>$pelatihan]);
        }
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

        $pelatihan = Pelatihan::select('kode','nama')->get();

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
            dd($e->getMessage());
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

    public function download()
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
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.download_instruktur', [
            'admin' => $admin,
            'instruktur' => $instruktur,
            'instruktur2' => $instruktur2,
        ]);

        return $pdf->stream('daftar_instruktur'.'.pdf');
    }

}
