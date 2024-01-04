<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelatihanController;

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
    Route::get('admin/pelatihan/{plt_kode}', 'viewDetailPelatihanAdmin')->middleware('only_admin')->name('admin.viewDetailPelatihan');
    Route::get('admin/daftar-pelatihan', 'viewDaftarPelatihan')->middleware('only_admin')->name('admin.viewDaftarPelatihan');
});
