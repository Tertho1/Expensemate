<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('transactions', TransactionController::class);
});
// Analytics page (placeholder)
Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics')->middleware('auth');

// Export page (placeholder)
Route::get('/export', [ExportController::class, 'index'])->name('export')->middleware('auth');
Route::get('/export/pdf', [ExportController::class, 'downloadPdf'])->name('export.pdf')->middleware('auth');
Route::get('/export/csv', [ExportController::class, 'downloadCsv'])->name('export.csv')->middleware('auth');

// Settings page (placeholder)
Route::get('/settings', function () {
    return view('settings');
})->name('settings')->middleware('auth');

Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');


require __DIR__ . '/auth.php';
