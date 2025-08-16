<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Controller
use App\Http\Controllers\Home;
use App\Http\Controllers\Users;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\LacakController;
use App\Http\Controllers\PDFController;

// Home Page
Route::middleware(['auth', 'check.status', 'role:admin,teknisi,ob,superadmin'])->group(function () {
    Route::get('/', [Home::class, 'index']);
    Route::get('/dashboard', [Home::class, 'dashboard']);
    // routes/web.php atau routes/api.php
    Route::get('/dashboard/chart-data', [Home::class, 'chartData']);

    Route::get('/lacak', [LacakController::class, 'index']);
    Route::get('/lacak/{id}', [LacakController::class, 'show'])->name('lacak.show');
    Route::get('/laporan/{id}/pdf', [PDFController::class, 'generatePDF'])->name('laporan.pdf');


});
Route::middleware(['auth', 'check.status', 'role:admin'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/laporan/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::post('/admin/laporan/{id}/reject', [AdminController::class, 'reject'])->name('admin.reject');
    Route::get('/admin/laporan/{id}/approve', [AdminController::class, 'approve'])->name('admin.approve');
    Route::get('/admin/data', [AdminController::class, 'metabase'])->name('admin.data');

});

Route::middleware(['auth', 'check.status', 'role:superadmin'])->group(function () {

    Route::get('/users', [Users::class, 'index'])->name('users');
    Route::post('/users', [Users::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [Users::class, 'update'])->name('users.update');

    // Gedung
    Route::get('/gedung', [GedungController::class, 'index'])->name('gedung');
    Route::post('/gedung', [GedungController::class, 'store'])->name('gedung.store');
    Route::put('/gedung/update/{id}', [GedungController::class, 'update'])->name('gedung.update');

    //Lantai
    Route::get('/lantai', [LantaiController::class, 'index'])->name('lantai');
    Route::post('/lantai', [LantaiController::class, 'store'])->name('lantai.store');
    Route::put('/lantai/update/{id}', [LantaiController::class, 'update'])->name('lantai.update');

    // Ruangan
    Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan');
    Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
    Route::put('/ruangan/update/{id}', [RuanganController::class, 'update'])->name('ruangan.update');

    // Fasilitas
    Route::get('/fasilitas', [FasilitasController::class, 'index'])->name('fasilitas');
    Route::post('/fasilitas', [FasilitasController::class, 'store'])->name('fasilitas.store');
    Route::put('/fasilitas/update/{id}', [FasilitasController::class, 'update'])->name('fasilitas.update');

});

// Only accessible by authenticated admin
Route::middleware(['auth', 'check.status', 'role:ob,teknisi'])->group(function () {
    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::put('/laporan/update/{id}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{id}/cancel', [LaporanController::class, 'cancel'])->name('laporan.cancel');

    // Get data for dropdowns
    Route::get('/get-lantai/{gedung_id}', action: [LaporanController::class, 'getLantai']);
    Route::get('/get-ruangan/{lantai_id}', [LaporanController::class, 'getRuangan']);
    Route::get('/get-fasilitas/{ruangan_id}', [LaporanController::class, 'getFasilitas']);
    // web.php
    Route::get('/laporan/{id}/edit-data', [LaporanController::class, 'getEditData']);

});


Route::middleware(['auth', 'check.status', 'role:teknisi'])->group(function () {
    // Teknisi Dashboard
    Route::get('/teknisi/dashboard', [TeknisiController::class, 'index'])->name('teknisi.dashboard');
    Route::get('/teknisi/laporan/{id}', [TeknisiController::class, 'show'])->name('teknisi.show');
    Route::put('/teknisi/laporan/{id}/process', [TeknisiController::class, 'process']);
    Route::post('/teknisi/laporan/{id}/finish', [TeknisiController::class, 'finish'])->name('teknisi.finish');
    Route::post('/teknisi/laporan/{id}/reject', [TeknisiController::class, 'reject'])->name('teknisi.reject');
    Route::post('/teknisi/laporan/{id}/vendor', [TeknisiController::class, 'vendor'])->name('teknisi.vendor');
});
// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');