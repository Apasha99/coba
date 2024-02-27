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
    Route::get('admin/daftar-admin', 'daftar_admin')->name('admin.viewDaftarAdmin');
    Route::get('admin/daftar-admin/detail/{admin_id}', 'detail_admin')->name('admin.viewDetailAdmin');
    Route::get('admin/pelatihan/{plt_kode}', 'viewDetailPelatihan')->middleware('only_admin')->name('admin.viewDetailPelatihan');
    Route::get('admin/pelatihan/{plt_kode}/daftar-partisipan', 'viewDaftarPartisipan')->middleware('only_admin')->name('admin.viewDaftarPartisipan');
    Route::get('admin/daftar-pelatihan', 'viewDaftarPelatihan')->middleware('only_admin')->name('admin.viewDaftarPelatihan');
    Route::get('admin/daftar-admin/create', 'create')->name('admin.createAdmin');
    Route::post('admin/daftar-admin/store', 'store')->name('admin.storeAdmin');
    Route::get('admin/daftar-admin/search', 'searchAdmin')->name('admin.searchAdmin');
    Route::get('admin/daftar-admin/edit/{admin_id}', 'edit')->name('admin.editAdmin');
    Route::post('admin/daftar-admin/edit/{admin_id}', 'update')->name('admin.updateAdmin');
    Route::delete('/admin/daftar-admin/delete/{admin_id}', 'delete')->name('admin.deleteAdmin');
    Route::get('admin/daftar-admin/download', 'download')->name('admin.downloadAdmin');
    Route::get('admin/ubah-password', 'ubahPassword')->middleware('only_admin')->name('admin.ubahPassword');
    Route::post('admin/ubah-password/{admin_id}', 'updatePassword')->middleware('only_admin')->name('admin.updatePassword');
    Route::get('admin/profil', 'profil')->middleware('only_admin')->name('admin.profil');
    Route::get('admin/profil/edit-profil', 'editProfil')->middleware('only_admin')->name('admin.editProfil');
    Route::post('admin/profil/edit-profil/{admin_id}', 'updateProfil')->middleware('only_admin')->name('admin.updateProfil');
});

Route::controller(PesertaController::class)->middleware('only_peserta')->group(function(){
    Route::get('peserta/dashboard', 'peserta')->name('peserta.dashboard');
    Route::get('peserta/pelatihan/{plt_kode}/{notif_id}', 'detailPelatihan')->middleware('only_peserta')->name('peserta.detailPelatihan');
    Route::get('peserta/daftar-pelatihan', 'viewDaftarPelatihan')->name('peserta.viewDaftarPelatihan');
    Route::get('peserta/history-pelatihan', 'viewHistoryPelatihan')->name('peserta.viewHistoryPelatihan');
    Route::get('peserta/pelatihan/{plt_kode}', 'viewDetailPelatihan')->middleware('only_peserta')->name('peserta.viewDetailPelatihan');
    Route::get('peserta/sertifikat', 'generateSertifikat')->name('peserta.generateSertifikat');
    Route::get('peserta/cetak-sertifikat/{pelatihan}/{peserta}', 'cetakSertifikat')->name('peserta.cetakSertifikat');
    Route::get('peserta/ubah-password', 'ubahPassword')->middleware('only_peserta')->name('peserta.ubahPassword');
    Route::post('peserta/ubah-password/{peserta_id}', 'updatePassword')->middleware('only_peserta')->name('peserta.updatePassword');
    Route::get('peserta/profil', 'profil')->middleware('only_peserta')->name('peserta.profil');
    Route::get('peserta/profil/edit-profil', 'editProfil')->middleware('only_peserta')->name('peserta.editProfil');
    Route::post('peserta/profil/edit-profil/{peserta_id}', 'updateProfil')->middleware('only_peserta')->name('peserta.updateProfil');
});

Route::controller(PesertaController::class)->middleware('only_admin')->group(function(){
    Route::get('admin/daftar-peserta', 'daftar_peserta')->name('admin.viewDaftarPeserta');
    Route::get('admin/daftar-peserta/detail/{peserta_id}', 'detail_peserta')->name('admin.viewDetailPeserta');
    Route::get('admin/daftar-peserta/create', 'create')->name('admin.createPeserta');
    Route::post('admin/daftar-peserta/store', 'store')->name('admin.storePeserta');
    Route::get('admin/daftar-peserta/search', 'searchPeserta')->name('admin.search');
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
    Route::get('instruktur/daftar-instruktur', 'daftar_instruktur')->name('admin.viewDaftarInstruktur');
    Route::get('instruktur/daftar-instruktur/detail/{instruktur_id}', 'detail_instruktur')->name('admin.viewDetailInstruktur');
    Route::get('instruktur/daftar-instruktur/tambah', 'viewTambahInstruktur')->name('admin.viewTambahInstruktur');
    Route::post('instruktur/daftar-instruktur/store', 'store')->name('admin.storeInstruktur');
    Route::get('instruktur/daftar-instruktur/search', 'searchInstruktur')->name('admin.searchInstruktur');
    Route::get('instruktur/daftar-instruktur/edit/{user_id}', 'viewEditInstruktur')->name('admin.editInstruktur');
    Route::post('instruktur/daftar-instruktur/edit/{id}', 'update')->name('admin.updateInstruktur');
    Route::delete('/instruktur/daftar-instruktur/delete/{instruktur_id}', 'delete')->name('admin.deleteInstruktur');
    Route::get('/instruktur/daftar-instruktur/import','tambah')->name('admin.tambahInstruktur');
    Route::get('/instruktur/daftar-instruktur/import/preview', 'preview')->name('admin.previewInstruktur');
    Route::get('/instruktur/daftar-instruktur/send-email', 'daftar_instruktur')->name('admin.tulisInstruktur');
    Route::post('/instruktur/daftar-instruktur/send-email', 'sendEmail')->name('admin.sendEmailInstruktur');
    Route::get('instruktur/daftar-instruktur/download', 'download')->name('admin.downloadInstruktur');
});

Route::controller(InstrukturController::class)->group(function(){
    Route::get('instruktur/dashboard', 'instruktur')->middleware('only_instruktur')->name('instruktur.dashboard');
    Route::get('instruktur/daftar-pelatihan', 'viewDaftarPelatihan')->middleware('only_instruktur')->name('instruktur.viewDaftarPelatihan');
    Route::get('instruktur/pelatihan/{plt_kode}', 'viewDetailPelatihan')->middleware('only_instruktur')->name('instruktur.viewDetailPelatihan');
    Route::get('instruktur/pelatihan/{plt_kode}/daftar-partisipan', 'viewDaftarPartisipan')->middleware('only_instruktur')->name('instruktur.viewDaftarPartisipan');
    Route::get('instruktur/pelatihan/{plt_kode}/tambah-materi', 'viewTambahMateri')->middleware('only_instruktur')->name('instruktur.viewTambahMateri');
    Route::get('instruktur/pelatihan/{plt_kode}/materi/{id}', 'viewEditMateri')->middleware('only_instruktur')->name('instruktur.viewEditMateri');
    Route::get('instruktur/pelatihan/{plt_kode}/tambah-tugas', 'viewTambahTugas')->middleware('only_instruktur')->name('instruktur.viewTambahTugas');
    Route::get('instruktur/pelatihan/{plt_kode}/tugas/{id}', 'viewEditTugas')->middleware('only_instruktur')->name('instruktur.viewEditTugas');
    Route::get('instruktur/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions', 'viewDaftarSubmissionTugas')->middleware('only_instruktur')->name('instruktur.viewDaftarSubmissionTugas');
    Route::get('instruktur/ubah-password', 'ubahPassword')->middleware('only_instruktur')->name('instruktur.ubahPassword');
    Route::post('instruktur/ubah-password/{instruktur_id}', 'updatePassword')->middleware('only_instruktur')->name('instruktur.updatePassword');
    Route::post('instruktur/gabung-pelatihan', 'gabungPelatihan')->middleware('only_instruktur')->name('instruktur.gabungPelatihan');
    Route::get('instruktur/profil', 'profil')->middleware('only_instruktur')->name('instruktur.profil');
    Route::get('instruktur/profil/edit-profil', 'editProfil')->middleware('only_instruktur')->name('instruktur.editProfil');
    Route::post('instruktur/profil/edit-profil/{instruktur_id}', 'updateProfil')->middleware('only_instruktur')->name('instruktur.updateProfil');
});

Route::controller(PelatihanController::class)->group(function(){
    Route::post('joinPelatihan', 'joinPelatihan')->middleware('only_peserta')->name('peserta.joinPelatihan');
    Route::get('admin/daftar-pelatihan/add', 'create')->middleware('only_admin')->name('admin.addPelatihan');
    Route::post('admin/daftar-pelatihan/store', 'store')->middleware('only_admin')->name('admin.storePelatihan');
    Route::get('admin/daftar-pelatihan/edit/{plt_id}', 'edit')->middleware('only_admin')->name('admin.editPelatihan');
    Route::post('admin/daftar-pelatihan/edit/{plt_id}', 'update')->middleware('only_admin')->name('admin.updatePelatihan');
    Route::delete('/admin/daftar-pelatihan/delete/{plt_kode}', 'delete')->middleware('only_admin')->name('admin.deletePelatihan');
    Route::get('/admin/search-pelatihan', 'searchPelatihan')->middleware('only_admin')->name('admin.searchPelatihan');
    Route::post('/admin/pelatihan/{plt_kode}/invite-instruktur', 'inviteInstruktur')->middleware('only_admin')->name('admin.inviteInstruktur');
    Route::post('/admin/pelatihan/{plt_kode}/invite-peserta', 'invitePeserta')->middleware('only_admin')->name('admin.invitePeserta');
    Route::delete('/admin/pelatihan/{plt_kode}/remove-instruktur/{instruktur_id}', 'removeInstruktur')->middleware('only_admin')->name('admin.removeInstruktur');
    Route::delete('/admin/pelatihan/{plt_kode}/remove-peserta/{peserta_id}', 'removePeserta')->middleware('only_admin')->name('admin.removePeserta');
});

Route::controller(MateriController::class)->group(function(){
    Route::get('admin/pelatihan/{plt_kode}/tambah-materi', 'viewTambahMateri')->middleware('only_admin')->name('admin.viewTambahMateri');
    Route::post('pelatihan/{plt_kode}/materi/store', 'store')->withoutMiddleware('only_peserta')->name('materi.store');
    Route::get('admin/pelatihan/{plt_kode}/materi/{id}', 'viewEdit')->middleware('only_admin')->name('admin.viewEditMateri');
    Route::post('pelatihan/{plt_kode}/materi/{id}', 'update')->withoutMiddleware('only_peserta')->name('materi.update');
    Route::post('pelatihan/{plt_kode}/materi/{id}/delete', 'delete')->withoutMiddleware('only_peserta')->name('materi.delete');
    //Route::get('admin/pelatihan/{plt_kode}/materi/{id}', 'view')->middleware('only_admin')->name('admin.storeMateri');
});

Route::controller(TugasController::class)->group(function(){
    Route::get('admin/pelatihan/{plt_kode}/tambah-tugas', 'viewTambahTugas')->middleware('only_admin')->name('admin.viewTambahTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas', 'store')->withoutMiddleware('only_peserta')->name('tugas.store');
    Route::get('admin/pelatihan/{plt_kode}/tugas/{id}', 'viewEdit')->middleware('only_admin')->name('admin.viewEditTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{id}', 'update')->withoutMiddleware('only_peserta')->name('tugas.update');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{id}/delete', 'delete')->withoutMiddleware('only_peserta')->name('tugas.delete');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{id}', 'viewDetailTugas')->middleware('only_peserta')->name('peserta.viewDetailTugas');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{id}/{notif_id}', 'detailTugas')->middleware('only_peserta')->name('peserta.detailTugas');
});

Route::controller(TestController::class)->group(function(){
    Route::get('/pelatihan/{plt_kode}/create/test', 'create')->withoutMiddleware('only_peserta')->name('test.add');
    Route::post('/pelatihan/{plt_kode}/test', 'store')->withoutMiddleware('only_peserta')->name('test.store');
    Route::post('pelatihan/{plt_kode}/delete/{test_id}', 'delete')->withoutMiddleware('only_peserta')->name('test.delete');
    Route::get('pelatihan/{plt_kode}/edit/{test_id}', 'edit')->withoutMiddleware('only_peserta')->name('test.edit');
    Route::post('pelatihan/{plt_kode}/edit/{test_id}/update', 'update')->withoutMiddleware('only_peserta')->name('test.update');
    Route::get('pelatihan/{plt_kode}/test/{test_id}/detail', 'DetailTest')->withoutMiddleware('only_peserta')->name('test.detail');
    Route::get('pelatihan/{plt_kode}/test/{test_id}/createSoal', 'createSoal')->withoutMiddleware('only_peserta')->name('soal.add');
    Route::post('pelatihan/{plt_kode}/test/{test_id}/storeSoal', 'storeSoal')->withoutMiddleware('only_peserta')->name('soal.store');
    Route::get('pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/edit', 'editSoal')->withoutMiddleware('only_peserta')->name('soal.edit');
    Route::post('pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/update', 'updateSoal')->withoutMiddleware('only_peserta')->name('soal.update');
    Route::delete('pelatihan/{plt_kode}/test/{test_id}/delete/{soal_id}', 'deleteSoal')->withoutMiddleware('only_peserta')->name('soal.delete');
    Route::post('pelatihan/{plt_kode}/test/{test_id}/detail/{soal_id}/edit/{jawaban_id}/delete', 'deleteJawaban')->withoutMiddleware('only_peserta')->name('jawaban.delete');
});

Route::controller(SubmissionController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'viewSubmissionForm')->middleware('only_peserta')->name('peserta.viewSubmissionForm');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/edit', 'viewEditSubmission')->middleware('only_peserta')->name('peserta.viewEditSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'store')->middleware('only_peserta')->name('peserta.storeSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'update')->middleware('only_peserta')->name('peserta.updateSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/delete', 'delete')->middleware('only_peserta')->name('peserta.deleteSubmission');

    Route::get('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions', 'viewDaftarSubmissionTugas')->middleware('only_admin')->name('admin.viewDaftarSubmissionTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'inputNilai')->withoutMiddleware('only_peserta')->name('submissionTugas.inputNilai');
    Route::get('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions/download/{submission_id}', 'download')->withoutMiddleware('only_peserta')->name('submissionTugas.downloadSubmissionTugas');
});

Route::controller(SubmissionTestController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}/test/{test_id}/{notif_id}', 'detailTest')->middleware('only_peserta')->name('peserta.detailTest');
    Route::get('peserta/pelatihan/{plt_kode}/test/{test_id}', 'viewDetailTest')->middleware('only_peserta')->name('peserta.viewDetailTest');
    Route::get('/peserta/pelatihan/{plt_kode}/test/{test_id}/soal', 'test')->middleware('only_peserta')->name('peserta.test');
    Route::post('peserta/pelatihan/{plt_kode}/test/{test_id}/submit', 'submitAnswer')->middleware('only_peserta')->name('peserta.submitAnswer');
    Route::post('/save-answer', 'Answer')->middleware('only_peserta')->name('peserta.saveAnswer');
    Route::get('/peserta/pelatihan/{plt_kode}/test/{test_id}/hasil', 'hasil')->middleware('only_peserta')->name('peserta.hasil');
});

Route::controller(RekapController::class)->group(function(){
    Route::get('pelatihan/{plt_kode}/rekap', 'rekapTest')->withoutMiddleware('only_peserta')->name('test.rekap');
    Route::get('pelatihan/{plt_kode}/rekap/download', 'download')->withoutMiddleware('only_peserta')->name('rekap.download');
    Route::get('pelatihan/{plt_kode}/rekap/{test_id}/detail/download', 'downloadRekap')->withoutMiddleware('only_peserta')->name('rekap.downloadDetail');
    Route::get('pelatihan/{plt_kode}/rekap/{test_id}/detail', 'detailRekapTest')->withoutMiddleware('only_peserta')->name('rekap.detailRekap');
});
