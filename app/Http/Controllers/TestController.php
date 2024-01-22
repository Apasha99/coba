<?php

namespace App\Http\Controllers;
use App\Models\Pelatihan;
use App\Models\Test;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TestController extends Controller
{
    public function viewDetailTest(String $plt_kode, String $test_id) {
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        return view('peserta.detail_test', ['pelatihan' => $pelatihan, 'test' => $test]);
    }

    public function store(Request $request, String $kode): RedirectResponse {
        $validated = $request->validate([
            'nama' => ['required'],
            'start_date' => ['required', 'date','after_or_equal:today'],
            'end_date' => ['required', 'date','after_or_equal:start_date'],
            'isActive' => ['required', 'boolean'],
            'deskripsi' => ['nullable', 'max:2000', 'string'],
            'acak_soal' => ['required'],
            'acak_jawaban' => ['required'],
            'tampil_hasil' => ['required'],
            'totalnilai' => ['required', 'numeric', 'max:100', 'min:0'],
        ]);
        //dd($validated);
    
        $isActive = $request->input('isActive');
    
        try {
            $test = new Test([
                'nama' => $validated['nama'],
                'isActive' => $isActive,
                'totalnilai' => $validated['totalnilai'],
                'plt_kode' => $kode,
                'start_date' => $validated['start_date'], // Tambahkan nilai untuk start_date
                'end_date' => $validated['end_date'],
                'acak_soal' => $validated['acak_soal'],
                'deskripsi' => $validated['deskripsi'],
                'acak_jawaban' => $validated['acak_jawaban'],
                'tampil_hasil' => $validated['tampil_hasil'],
            ]);
            //dd($test);
    
            $test->save();
            return redirect()->back()->with('success', 'Data test berhasil disimpan');
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan atau log jika diperlukan
            //dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }    

    public function DetailTest(String $plt_kode, String $test_id) {
        $admin = Admin::leftJoin('users', 'admin.user_id', '=', 'users.id')
                ->where('admin.user_id', Auth::user()->id)
                ->select('admin.nama', 'admin.id', 'users.username')
                ->first();
        $pelatihan = Pelatihan::where('kode', $plt_kode)->first();
        $test = Test::where('plt_kode', $plt_kode)->where('id', $test_id)->first();
        return view('admin.detail_test', ['pelatihan' => $pelatihan, 'test' => $test]);
    }
    
}
