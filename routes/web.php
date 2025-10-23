<?php 

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VesselController;
use App\Http\Controllers\CustomerVesselController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * ======================
     * DASHBOARD
     * ======================
     */
    Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE USER
    Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
            Route::get('/password', [ProfileController::class, 'editPassword'])->name('password.edit');
            Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        });

        // ... sisanya sama seperti sebelumnya
    });

    /**
     * ======================
     * CUSTOMERS
     * ======================
     */
    Route::prefix('companies')->group(function () {
        Route::get('/print', [CompanyController::class, 'print'])->name('companies.print');
        Route::get('/{company}/print', [CompanyController::class, 'printSingle'])->name('companies.print_single');
        Route::get('/{company}/vessels-json', [CompanyController::class, 'getVessels'])->name('companies.get_vessels');
    });
    Route::resource('companies', CompanyController::class);
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

    // Staff dashboard
    Route::get('/staff/dashboard', [CompanyController::class, 'index'])->name('staff.dashboard');

    /**
     * ======================
     * VESSELS (GLOBAL)
     * ======================
     */
    Route::resource('vessels', VesselController::class);

    /**
     * ======================
     * CUSTOMERS + VESSELS (GLOBAL LIST)
     * ======================
     * contoh: /customers-vessels/create
     * ini PAKAI route name customers_vessels.store
     */
    Route::resource('customers-vessels', CustomerVesselController::class)
        ->parameters(['customers-vessels' => 'id'])
        ->names([
            'index'   => 'customers_vessels.index',
            'create'  => 'customers_vessels.create',
            'store'   => 'customers_vessels.store',
            'show'    => 'customers_vessels.show',
            'edit'    => 'customers_vessels.edit',
            'update'  => 'customers_vessels.update',
            'destroy' => 'customers_vessels.destroy',
        ]);

    /**
     * ======================
     * CUSTOMERS + VESSELS (NESTED PER CUSTOMER)
     * ======================
     * contoh: /customers/{customer}/vessels/create
     * ini PAKAI route name companies.vessels.store
     */
    Route::prefix('customers/{customer}')->group(function () {
        Route::get('/profile', [CustomerVesselController::class, 'profile'])->name('companies.profile');
        Route::get('/detail', [CustomerVesselController::class, 'show'])->name('companies.detail');

        // Vessel nested routes
        Route::get('/vessels/create', [CustomerVesselController::class, 'create'])->name('companies.vessels.create');
        Route::post('/vessels', [CustomerVesselController::class, 'store'])->name('companies.vessels.store');
        Route::get('/vessels/{vessel}', [CustomerVesselController::class, 'show'])->name('companies.vessels.show');
        Route::get('/vessels/{vessel}/edit', [CustomerVesselController::class, 'edit'])->name('companies.vessels.edit');
        Route::put('/vessels/{vessel}', [CustomerVesselController::class, 'update'])->name('companies.vessels.update');
        Route::delete('/vessels/{vessel}', [CustomerVesselController::class, 'destroy'])->name('companies.vessels.destroy');
    });

    /**
     * ======================
     * MARKETING (alias CUSTOMER)
     * ======================
     */
    Route::resource('marketing', CompanyController::class)
        ->parameters(['marketing' => 'company'])
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
