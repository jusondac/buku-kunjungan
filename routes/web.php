<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\GuestDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AuthController;

// Public routes
Route::get('/', [GuestController::class, 'showForm'])->name('guests.form');
Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data Kunjungan (Guest List)
    Route::get('/guests', [GuestDataController::class, 'index'])->name('guests.index');
    Route::patch('/guests/{guest}/status', [GuestDataController::class, 'updateStatus'])->name('guests.updateStatus');
    Route::delete('/guests/{guest}', [GuestDataController::class, 'destroy'])->name('guests.destroy');

    // Reports & Exports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    
    // Legacy routes (backward compatibility)
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.exportExcel');
});
