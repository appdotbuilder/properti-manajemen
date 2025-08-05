<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Welcome page with housing management overview
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard with role-based content
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Housing management routes
    Route::resource('houses', HouseController::class);
    Route::resource('residents', ResidentController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('complaints', ComplaintController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';