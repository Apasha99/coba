<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        // Proses penyimpanan data jika validasi berhasil
        Materi::create($validated);
    
        // Redirect atau proses lainnya setelah penyimpanan data berhasil
        return redirect()->back()->with('success', 'Data materi berhasil disimpan');
    }
}
