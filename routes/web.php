<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\TestController;
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
    Route::get('admin/pelatihan/{plt_kode}/test/{test_id}/detail', 'DetailTest')->middleware('only_admin')->name('admin.detailTest');
});

Route::controller(SubmissionController::class)->group(function(){
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'viewSubmissionForm')->middleware('only_peserta')->name('peserta.viewSubmissionForm');
    Route::get('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/edit', 'viewEditSubmission')->middleware('only_peserta')->name('peserta.viewEditSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission', 'store')->middleware('only_peserta')->name('peserta.storeSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'update')->middleware('only_peserta')->name('peserta.updateSubmission');
    Route::post('peserta/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}/delete', 'delete')->middleware('only_peserta')->name('peserta.deleteSubmission');

    Route::get('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submissions', 'viewDaftarSubmissionTugas')->middleware('only_admin')->name('admin.viewDaftarSubmissionTugas');
    Route::post('admin/pelatihan/{plt_kode}/tugas/{tugas_id}/submission/{submission_id}', 'inputNilai')->middleware('only_admin')->name('admin.inputNilai');
});
