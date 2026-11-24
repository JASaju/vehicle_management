<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ParkingSlotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SecurityLogController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [SeoController::class, 'landing'])->name('landing');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');

// ---------------------------------------------------------------
// Guests: login only (no public registration; admins create users)
// ---------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
});

// ---------------------------------------------------------------
// Everything else needs a logged-in, active user.
// Deleting is handled inside the PowerGrid tables (wire:click), so no destroy routes.
// ---------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('vehicles', VehicleController::class)->except(['show', 'destroy']);
    Route::resource('drivers', DriverController::class)->except(['show', 'destroy']);
    Route::resource('maintenances', MaintenanceController::class)->except(['show', 'destroy']);
    Route::resource('fuel-logs', FuelLogController::class)
        ->parameters(['fuel-logs' => 'fuelLog'])
        ->except(['show', 'destroy']);

    // PDF reports (mPDF). Add ?download=1 to any report URL to force a download instead of opening inline.
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('vehicles', 'vehicles')->name('vehicles');
        Route::get('drivers', 'drivers')->name('drivers');
        Route::get('maintenance', 'maintenance')->name('maintenance');
        Route::get('fuel', 'fuel')->name('fuel');
        Route::get('expiry', 'expiry')->name('expiry');
        Route::get('costs', 'costs')->name('costs');
        Route::get('parking', 'parking')->name('parking');
        Route::get('vehicle/{vehicle}', 'vehicle')->name('vehicle');
    });

    // Parking: ground-floor map, park / release, slot management, history.
    Route::get('parking', [ParkingController::class, 'index'])->name('parking.index');
    Route::get('parking/history', [ParkingController::class, 'history'])->name('parking.history');
    Route::post('parking/{slot}/park', [ParkingController::class, 'park'])->name('parking.park');
    Route::post('parking/{slot}/release', [ParkingController::class, 'release'])->name('parking.release');
    Route::post('parking/{slot}/identify', [ParkingController::class, 'identify'])->name('parking.identify');

    Route::post('parking-slots/bulk', [ParkingSlotController::class, 'bulk'])->name('parking-slots.bulk');
    Route::resource('parking-slots', ParkingSlotController::class)
        ->parameters(['parking-slots' => 'slot'])
        ->except(['show', 'destroy']);

    // User management: administrators only.
    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::get('security-logs', [SecurityLogController::class, 'index'])->name('security-logs.index');
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
    });
});
