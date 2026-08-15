<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\C45Controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard accessible by both Admin & Pekerja Kandang
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Klasifikasi (Worker & Admin can input & view history)
    Route::prefix('klasifikasi')->name('klasifikasi.')->group(function () {
        Route::get('/', [KlasifikasiController::class, 'index'])->name('index');
        Route::get('/create', [KlasifikasiController::class, 'create'])->name('create');
        Route::post('/store', [KlasifikasiController::class, 'store'])->name('store');
        Route::get('/{id}', [KlasifikasiController::class, 'show'])->name('show');
        Route::delete('/{id}', [KlasifikasiController::class, 'destroy'])->middleware(RoleMiddleware::class . ':admin')->name('destroy');
    });

    // Admin Only Routes
    Route::middleware(RoleMiddleware::class . ':admin')->group(function () {
        // Dataset Latih Management
        Route::prefix('dataset')->name('dataset.')->group(function () {
            Route::get('/', [DatasetController::class, 'index'])->name('index');
            Route::post('/', [DatasetController::class, 'store'])->name('store');
            Route::put('/{id}', [DatasetController::class, 'update'])->name('update');
            Route::delete('/{id}', [DatasetController::class, 'destroy'])->name('destroy');
            Route::post('/reset', [DatasetController::class, 'reset'])->name('reset');
        });

        // Detail C4.5 Calculation & Tree Visualization
        Route::get('/c45', [C45Controller::class, 'index'])->name('c45.index');

        // Laporan Rekapitulasi
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});
