<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportHistoryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UrgencyController;
use App\Http\Controllers\WorkerController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Siswa: buat laporan. (Didefinisikan sebelum /laporan/{report} agar "buat" tidak dianggap ID.)
    Route::middleware(RoleMiddleware::class . ':siswa')->group(function () {
        Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    });

    // Admin + Siswa (siswa dibatasi pada laporan miliknya di dalam controller).
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');

    // Admin saja
    Route::middleware(RoleMiddleware::class . ':admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('students', StudentController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('urgencies', UrgencyController::class)->except('show');
        Route::resource('workers', WorkerController::class)->except('show');
        Route::get('/riwayat', [ReportHistoryController::class, 'index'])->name('histories.index');
    });

    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        Route::get('/laporan-cetak', [ReportController::class, 'print'])->name('reports.print');
        Route::patch('/laporan/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.status');
        Route::patch('/laporan/{report}/petugas', [ReportController::class, 'assignWorker'])->name('reports.worker');
        Route::patch('/laporan/{report}/catatan', [ReportController::class, 'updateRepairNote'])->name('reports.note');
    });

    Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show');
});