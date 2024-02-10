<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InstrukturController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SubmissionTestController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->middleware('auth');

Route::get('login', [AuthController::class,'login'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);

Route::controller(AuthController::class)->middleware('auth')->group(function(){
    Route::get('logout', 'logout')->name('logout');
});

Route::controller(AdminController::class)->middleware('only_admin')->group(function(){
    Route::get('admin/dashboard', 'admin')->name('admin.dashboard');
});

Route::controller(PesertaController::class)->middleware('only_peserta')->group(function(){
    Route::get('peserta/dashboard', 'peserta')->name('peserta.dashboard');
    Route::get('peserta/sertifikat', 'generateSertifikat')->name('peserta.generateSertifikat');
    Route::get('peserta/cetak-sertifikat', 'cetakSertifikat')->name('peserta.cetakSertifikat');
});

Route::controller(PesertaController::class)->middleware('only_admin')->group(function(){
    Route::get('admin/daftar-peserta', 'daftar_peserta')->name('admin.viewDaftarPeserta');
    Route::post('admin/daftar-peserta/store', 'store')->name('admin.storePeserta');
    Route::get('admin/daftar-peserta/search', 'searchPeserta')->name('admin.searchPeserta');
    Route::get('admin/daftar-peserta/edit/{id}', 'edit')->name('admin.editPeserta');
    Route::post('admin/daftar-peserta/edit/{id}', 'update')->name('admin.updatePeserta');
    Route::delete('/admin/daftar-peserta/delete/{peserta_id}', 'delete')->name('admin.deletePeserta');
    Route::get('/admin/daftar-peserta/import','tambah')->name('admin.tambahPeserta');
    Route::post('/admin/daftar-peserta/import','import')->name('admin.importPeserta');
    Route::get('/admin/daftar-peserta/import/preview', 'preview')->name('admin.previewPeserta');
    Route::post('/admin/daftar-peserta/generate-akun','generateAkun')->name('admin.generatePeserta');
    Route::get('/admin/daftar-peserta/export','export')->name('admin.exportPeserta');
    Route::get('/admin/daftar-peserta/send-email', 'daftar_peserta')->name('admin.tulisEmail');
    Route::post('/admin/daftar-peserta/send-email', 'sendEmail')->name('admin.sendEmail');
});

Route::controller(InstrukturController::class)->middleware('only_admin')->group(function(){
    Route::get('admin/daftar-instruktur', 'daftar_instruktur')->name('admin.viewDaftarInstruktur');
    Route::get('admin/tambah-instruktur', 'viewTambahInstruktur')->name('admin.viewTambahInstruktur');
    Route::post('admin/daftar-instruktur/store', 'store')->name('admin.storeInstruktur');
    Route::get('admin/daftar-peserta/search', 'searchInstruktur')->name('admin.searchInstruktur');
    Route::get('admin/daftar-peserta/edit/{id}', 'edit')->name('admin.editInstruktur');
    Route::post('admin/daftar-peserta/edit/{id}', 'update')->name('admin.updatePeserta');
    Route::delete('/admin/daftar-peserta/delete/{peserta_id}', 'delete')->name('admin.deleteInstruktur');
    Route::get('/admin/daftar-peserta/import','tambah')->name('admin.tambahPeserta');
    Route::post('/admin/daftar-peserta/import','import')->name('admin.importPeserta');
    Route::get('/admin/daftar-peserta/import/preview', 'preview')->name('admin.previewPeserta');
    Route::post('/admin/daftar-peserta/generate-akun','generateAkun')->name('admin.generatePeserta');
    Route::get('/admin/daftar-peserta/export','export')->name('admin.exportPeserta');
    Route::get('/admin/daftar-peserta/send-email', 'daftar_peserta')->name('admin.tulisEmail');
    Route::post('/admin/daftar-peserta/send-email', 'sendEmail')->name('admin.sendEmail');
});

Route::controller(PelatihanController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}', 'viewDetailPelatihanPeserta')->middleware('only_peserta')->name('peserta.viewDetailPelatihan');
    Route::post('joinPelatihan', 'joinPelatihan')->middleware('only_peserta')->name('peserta.joinPelatihan');
    Route::get('admin/pelatihan/{plt_kode}', 'viewDetailPelatihanAdmin')->middleware('only_admin')->name('admin.viewDetailPelatihan');
    Route::get('admin/daftar-pelatihan', 'viewDaftarPelatihan')->middleware('only_admin')->name('admin.viewDaftarPelatihan');
    Route::get('admin/daftar-pelatihan/add', 'create')->middleware('only_admin')->name('admin.addPelatihan');
    Route::post('admin/daftar-pelatihan/store', 'store')->middleware('only_admin')->name('admin.storePelatihan');
    Route::get('admin/daftar-pelatihan/edit/{plt_id}', 'edit')->middleware('only_admin')->name('admin.editPelatihan');
    Route::post('admin/daftar-pelatihan/edit/{plt_id}', 'update')->middleware('only_admin')->name('admin.updatePelatihan');
    Route::delete('/admin/daftar-pelatihan/delete/{plt_kode}', 'delete')->middleware('only_admin')->name('admin.deletePelatihan');
    Route::get('/admin/search-pelatihan', 'searchPelatihan')->middleware('only_admin')->name('admin.searchPelatihan');
});

Route::controller(MateriController::class)->group(function(){
    Route::get('admin/pelatihan/{plt_kode}/tambah-materi', 'viewTambahMateri')->middleware('only_admin')->name('admin.viewTambahMateri');
    Route::post('admin/pelatihan/{plt_kode}/materi', 'store')->middleware('only_admin')->name('admin.storeMateri');
    Route::get('admin/pelatihan/{plt_kode}/materi/{id}', 'viewEdit')->middleware('only_admin')->name('admin.viewEditMateri');
    Route::post('admin/pelatihan/{plt_kode}/materi/{id}', 'update')->middleware('only_admin')->name('admin.updateMateri');
    Route::post('admin/pelatihan/{plt_kode}/materi/{id}/delete', 'delete')->middleware('only_admin')->name('admin.deleteMateri');
    //Route::get('admin/pelatihan/{plt_kode}/materi/{id}', 'view')->middleware('only_admin')->name('admin.storeMateri');
});

Route::controller(TugasController::class)->group(function(){
    Route::post('admin/pelatihan/{plt_kode}/tugas', 'store')->middleware('only_admin')->name('admin.storeTugas');
    Route::get('admin/pelatihan/{plt_kode}/tugas/{id}', 'viewEdit')->middleware('only_admin')->name('admin.viewEditTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{id}', 'update')->middleware('only_admin')->name('admin.updateTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{id}/delete', 'delete')->middleware('only_admin')->name('admin.deleteTugas');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{id}', 'viewDetailTugas')->middleware('only_peserta')->name('peserta.viewDetailTugas');
});

Route::controller(TestController::class)->group(function(){
    Route::post('admin/pelatihan/{plt_kode}/test', 'store')->middleware('only_admin')->name('admin.storeTest');
    Route::post('admin/pelatihan/{plt_kode}/delete/{test_id}', 'delete')->middleware('only_admin')->name('admin.deleteTest');
    Route::get('admin/pelatihan/{plt_kode}/edit/{test_id}', 'edit')->middleware('only_admin')->name('admin.editTest');
    Route::post('admin/pelatihan/{plt_kode}/edit/{test_id}/update', 'update')->middleware('only_admin')->name('admin.updateTest');
    Route::get('admin/pelatihan/{plt_kode}/test/{test_id}/detail', 'DetailTest')->middleware('only_admin')->name('admin.detailTest');
    Route::post('admin/pelatihan/{plt_kode}/test/{test_id}/storeSoal', 'storeSoal')->middleware('only_admin')->name('admin.storeSoal');
    Route::get('admin/pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/edit', 'editSoal')->middleware('only_admin')->name('admin.editSoal');
    Route::post('admin/pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/update', 'updateSoal')->middleware('only_admin')->name('admin.updateSoal');
    Route::delete('admin/pelatihan/{plt_kode}/test/{test_id}/delete/{soal_id}', 'deleteSoal')->middleware('only_admin')->name('admin.deleteSoal');
    Route::post('admin/pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/edit/{jawaban_id}/delete', 'deleteJawaban')->middleware('only_admin')->name('admin.deleteJawaban');
});

Route::controller(SubmissionController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'viewSubmissionForm')->middleware('only_peserta')->name('peserta.viewSubmissionForm');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/edit', 'viewEditSubmission')->middleware('only_peserta')->name('peserta.viewEditSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'store')->middleware('only_peserta')->name('peserta.storeSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'update')->middleware('only_peserta')->name('peserta.updateSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/delete', 'delete')->middleware('only_peserta')->name('peserta.deleteSubmission');

    Route::get('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions', 'viewDaftarSubmissionTugas')->middleware('only_admin')->name('admin.viewDaftarSubmissionTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'inputNilai')->middleware('only_admin')->name('admin.inputNilai');
    Route::get('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions/download/{submission_id}', 'download')->middleware('only_admin')->name('admin.downloadSubmissionTugas');
});

Route::controller(SubmissionTestController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}/test/{test_id}', 'viewDetailTest')->middleware('only_peserta')->name('peserta.viewDetailTest');
    Route::get('/peserta/pelatihan/{plt_kode}/test/{test_id}/soal', 'test')->middleware('only_peserta')->name('peserta.test');
    Route::post('peserta/pelatihan/{plt_kode}/test/{test_id}/submit', 'submitAnswer')->middleware('only_peserta')->name('peserta.submitAnswer');
    Route::post('/save-answer', 'Answer')->middleware('only_peserta')->name('peserta.saveAnswer');
    Route::get('/peserta/pelatihan/{plt_kode}/test/{test_id}/hasil', 'hasil')->middleware('only_peserta')->name('peserta.hasil');
});

Route::controller(RekapController::class)->group(function(){
    Route::get('admin/pelatihan/{plt_kode}/rekap', 'rekapTest')->middleware('only_admin')->name('admin.rekapTest');
    Route::get('admin/pelatihan/{plt_kode}/rekap/download', 'download')->middleware('only_admin')->name('admin.downloadRekap');
    Route::get('admin/pelatihan/{plt_kode}/rekap/{test_id}/detail/download', 'downloadRekap')->middleware('only_admin')->name('admin.downloadDetailRekap');
    Route::get('admin/pelatihan/{plt_kode}/rekap/search', 'searchTest')->middleware('only_admin')->name('admin.searchTest');
    Route::get('admin/pelatihan/{plt_kode}/rekap/{test_id}/detail/search', 'searchDetailTest')->middleware('only_admin')->name('admin.searchDetailTest');
    Route::get('admin/pelatihan/{plt_kode}/rekap/{test_id}/detail', 'detailRekapTest')->middleware('only_admin')->name('admin.detailRekapTest');
});
