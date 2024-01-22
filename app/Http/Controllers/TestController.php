<?php

namespace App\Http\Controllers;
use App\Models\Pelatihan;
use App\Models\Test;
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
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'isActive' => ['required', 'boolean'], // Tambahkan aturan boolean
            'totalnilai' => ['required', 'numeric', 'max:100', 'min:0'], // Tambahkan aturan numeric
        ]);
    
        $isActive = $request->input('isActive');
    
        try {
            $test = new Test([
                'nama' => $validated['nama'], // Gunakan data yang sudah divalidasi
                'isActive' => $isActive,
                'totalnilai' => $validated['totalnilai'], // Gunakan data yang sudah divalidasi
                'plt_kode' => $kode,
            ]);
    
            $test->save(); // Gunakan metode save untuk menyimpan data
            return redirect()->back()->with('success', 'Data test berhasil disimpan');
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan atau log jika diperlukan
            dd($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }
    
}
