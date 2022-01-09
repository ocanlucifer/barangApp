<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/barang');
});

Auth::routes();

Route::get('/cv', function () {
    // $filename = 'cv_.pdf';
    // $path = storage_path($filename);

    // return Response::make(file_get_contents($path), 200, [
    //     'Content-Type' => 'application/pdf',
    //     'Content-Disposition' => 'inline; filename="'.$filename.'"'
    // ]);
    return view('cv');
});

Route::get('/barang', [BarangController::class, 'index'])->name('barang');
Route::get('/barang/barang_masuk', [BarangController::class, 'barang_masuk'])->name('barang_masuk');
Route::get('/barang/barang_keluar', [BarangController::class, 'barang_keluar'])->name('barang_keluar');
Route::get('/barang/mutasi', [BarangController::class, 'mutasi'])->name('mutasi');
Route::get('/barang/log_barang', [BarangController::class, 'log_barang'])->name('log_barang');
Route::post('/barang/get_barang', [BarangController::class, 'get_barang']);
Route::post('/barang/create', [BarangController::class, 'create']);
Route::post('/barang/update', [BarangController::class, 'update']);
Route::post('/barang/update_stock', [BarangController::class, 'update_stock']);
Route::get('/barang/delete/{id}', [BarangController::class, 'delete']);
