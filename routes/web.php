<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\CustomerVesselController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * ======================
     * DASHBOARD
     * ======================
     */
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    /**
     * ======================
     * PROFILE USER
     * ======================
     */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    /**
     * ======================
     * CUSTOMERS
     * ======================
     */
    Route::prefix('customers')->group(function () {
        Route::get('/print', [CustomerController::class, 'print'])->name('customers.print');
        Route::get('/{customer}/print', [CustomerController::class, 'printSingle'])->name('customers.print_single');
        Route::get('/{customer}/vessels-json', [CustomerController::class, 'getVessels'])->name('customers.get_vessels');
    });
    Route::resource('customers', CustomerController::class);

    // Staff dashboard
    Route::get('/staff/dashboard', [CustomerController::class, 'index'])->name('staff.dashboard');

    /**
     * ======================
     * VESSELS (GLOBAL)
     * ======================
     */
    Route::resource('vessels', VesselController::class);

    /**
     * ======================
     * CUSTOMERS + VESSELS (RELASI)
     * ======================
     */
    // Semua vessels (global view)
    Route::get('/customers-vessels', [CustomerVesselController::class, 'index'])->name('customers_vessels.index');

    // Create vessels global
    Route::get('/customers-vessels/create', [CustomerVesselController::class, 'create'])->name('customers_vessels.create');
    Route::post('/customers-vessels', [CustomerVesselController::class, 'store'])->name('customers_vessels.store');

    // Update global
    Route::put('/customers-vessels/{customer}', [CustomerVesselController::class, 'update'])->name('customers_vessels.update');

    // Detail per customer
    Route::prefix('customers/{customer}')->group(function () {
        Route::get('/edit', [CustomerVesselController::class, 'edit'])->name('customers_vessels.edit');
        Route::get('/profile', [CustomerVesselController::class, 'profile'])->name('customers.profile');
        Route::get('/detail', [CustomerVesselController::class, 'show'])->name('customers.detail');

        // Vessel CRUD khusus Customer
        Route::get('/vessels/create', [CustomerVesselController::class, 'create'])->name('customers.vessels.create');
        Route::post('/vessels', [CustomerVesselController::class, 'store'])->name('customers.vessels.store');
        Route::get('/vessels/{vessel}/edit', [CustomerVesselController::class, 'edit'])->name('customers.vessels.edit');
        Route::put('/vessels/{vessel}', [CustomerVesselController::class, 'update'])->name('customers.vessels.update');
        Route::delete('/vessels/{vessel}', [CustomerVesselController::class, 'destroy'])->name('customers.vessels.destroy');
    });

    /**
     * ======================
     * MARKETING (alias CUSTOMER)
     * ======================
     */
    Route::resource('marketing', CustomerController::class)
        ->parameters(['marketing' => 'customer'])
        ->names([
            'index'   => 'marketing.index',
            'create'  => 'marketing.create',
            'store'   => 'marketing.store',
            'show'    => 'marketing.show',
            'edit'    => 'marketing.edit',
            'update'  => 'marketing.update',
            'destroy' => 'marketing.destroy',
        ]);

    /**
     * ======================
     * USERS (ADMIN ONLY)
     * ======================
     */
    Route::middleware('can:isAdmin')->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__ . '/auth.php';
