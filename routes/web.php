<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\TugasController;

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
    Route::get('dashboardAdmin', 'admin')->name('admin.dashboard');
});

Route::controller(PesertaController::class)->middleware('only_peserta')->group(function(){
    Route::get('dashboardPeserta', 'peserta')->name('peserta.dashboard');
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
    //Route::get('admin/pelatihan/{plt_kode}/materi/{id}', 'view')->middleware('only_admin')->name('admin.storeMateri');
});
