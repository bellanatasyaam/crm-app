<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\CustomerVesselController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/print', [CustomerController::class, 'print'])->name('customers.print');
        Route::get('/{customer}/print', [CustomerController::class, 'printSingle'])->name('customers.print_single');
        Route::get('/{customer}/vessels-json', [CustomerController::class, 'getVessels'])->name('customers.get_vessels');
        // ⚡ Tambahan route update untuk customer biasa
        Route::put('/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
    });

    Route::resource('customers', CustomerController::class); // otomatis ada index, create, edit, update, destroy, show

    // Staff dashboard
    Route::get('/staff/dashboard', [CustomerController::class, 'index'])->name('staff.dashboard');

    // Marketing
    Route::get('/marketing', [CustomerController::class, 'index'])->name('marketing.index');

    // Global vessels
    Route::get('/vessels', [VesselController::class, 'index'])->name('vessels.index');
    Route::resource('vessels', VesselController::class);

    // Nested vessels opsional
    Route::resource('customers.vessels', VesselController::class);

    // Customer-Vessel relationship management
    Route::prefix('customers/{customer}')->group(function () {
        Route::get('/edit', [CustomerVesselController::class, 'edit'])->name('customers_vessels.edit');
        Route::get('/profile', [CustomerVesselController::class, 'profile'])->name('customers.profile');
        Route::get('/detail', [CustomerVesselController::class, 'show'])->name('customers.detail');

        // ⚡ Tambahan route update untuk CustomerVessel
        Route::put('/update', [CustomerVesselController::class, 'update'])->name('customers_vessels.update');

        // Vessel CRUD khusus customer
        Route::get('/vessels/create', [CustomerVesselController::class, 'create'])->name('customers.vessels.create');
        Route::post('/vessels', [CustomerVesselController::class, 'store'])->name('customers.vessels.store');
        Route::get('/vessels/{vessel}/edit', [CustomerVesselController::class, 'edit'])->name('customers.vessels.edit');
        Route::put('/vessels/{vessel}', [CustomerVesselController::class, 'update'])->name('customers.vessels.update');
        Route::delete('/vessels/{vessel}', [CustomerVesselController::class, 'destroy'])->name('customers.vessels.destroy');
    });

    // Customer-Vessel index (semua customer + vessels)
    Route::get('/customers-vessels', [CustomerVesselController::class, 'index'])->name('customers_vessels.index');

    // Users (admin only)
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__ . '/auth.php';
