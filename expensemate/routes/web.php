<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing page - Fixed auth check for Laravel Breeze
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('landing');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaction routes
    Route::resource('transactions', TransactionController::class);

    // Category routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Analytics route
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Export routes
    Route::get('/export', [ExportController::class, 'index'])->name('export');
    Route::get('/export/csv', [ExportController::class, 'csv'])->name('export.csv');
    Route::get('/export/excel', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');

    // Settings route
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});

// Public Pages Routes
Route::controller(PageController::class)->group(function () {
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
    Route::get('/support', 'support')->name('support');
    Route::get('/help-center', 'helpCenter')->name('help.center');
    Route::get('/documentation', 'documentation')->name('documentation');
    Route::get('/api-reference', 'apiReference')->name('api.reference');
    Route::get('/tutorials', 'tutorials')->name('tutorials');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/privacy-policy', 'privacy')->name('privacy');
    Route::get('/terms-of-service', 'terms')->name('terms');
    Route::get('/cookie-policy', 'cookies')->name('cookies');
});

require __DIR__ . '/auth.php';
