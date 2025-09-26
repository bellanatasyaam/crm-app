<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal redirect ke login
Route::get('/', fn() => redirect()->route('login'));

// Semua route harus login & verified
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard default redirect ke customers index
    Route::get('/dashboard', fn() => redirect()->route('customers.index'))->name('dashboard');

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Update password
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // Customer routes
    Route::get('customers/print', [CustomerController::class, 'print'])->name('customers.print');
    Route::resource('customers', CustomerController::class);

    // Print satu customer
    Route::get('/customers/{id}/print', [CustomerController::class, 'printSingle'])
        ->name('customers.print_single');

    // Staff dashboard (alias)
    Route::get('/staff/dashboard', [CustomerController::class, 'index'])->name('staff.dashboard');

    // User management hanya untuk admin
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Vessel routes (nested ke customer)
    Route::prefix('customers/{customer}')->group(function () {
        Route::resource('vessels', VesselController::class);
    });
});

// Auth routes (login, register, password reset, dll)
require __DIR__ . '/auth.php';
