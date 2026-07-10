<?php

use App\Http\Controllers\KurangKirimController;
use Illuminate\Support\Facades\Route;

Route::any('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [KurangKirimController::class, 'dashboard'])->name('dashboard');
Route::post('/dashboard', [KurangKirimController::class, 'store'])->name('kurang-kirim.store');

Route::get('/history', [KurangKirimController::class, 'history'])->name('history');
Route::get('/history/export', [KurangKirimController::class, 'export'])->name('history.export');

Route::get('/kurang-kirim/{kurangKirim}/edit', [KurangKirimController::class, 'edit'])->name('kurang-kirim.edit');
Route::put('/kurang-kirim/{kurangKirim}', [KurangKirimController::class, 'update'])->name('kurang-kirim.update');
Route::delete('/kurang-kirim/{kurangKirim}', [KurangKirimController::class, 'destroy'])->name('kurang-kirim.destroy');
Route::get('/kurang-kirim/{kurangKirim}/view', [KurangKirimController::class, 'view'])->name('kurang-kirim.view');
Route::get('/kurang-kirim/{kurangKirim}/download', [KurangKirimController::class, 'download'])->name('kurang-kirim.download');

// Master Toko
Route::get('/toko', [\App\Http\Controllers\TokoController::class, 'index'])->name('toko.index');
Route::get('/toko/create', [\App\Http\Controllers\TokoController::class, 'create'])->name('toko.create');
Route::post('/toko', [\App\Http\Controllers\TokoController::class, 'store'])->name('toko.store');
Route::get('/toko/{toko}/edit', [\App\Http\Controllers\TokoController::class, 'edit'])->name('toko.edit');
Route::put('/toko/{toko}', [\App\Http\Controllers\TokoController::class, 'update'])->name('toko.update');
Route::delete('/toko/{toko}', [\App\Http\Controllers\TokoController::class, 'destroy'])->name('toko.destroy');
Route::patch('/toko/{toko}/toggle-status', [\App\Http\Controllers\TokoController::class, 'toggleStatus'])->name('toko.toggle-status');
