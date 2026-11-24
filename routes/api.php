<?php

use App\Http\Controllers\ParkingSensorController;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\EnsureParkingSensorToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', EnsureActiveUser::class])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(EnsureParkingSensorToken::class)
    ->prefix('parking')
    ->name('api.parking.')
    ->controller(ParkingSensorController::class)
    ->group(function () {
        Route::post('sensor', 'sensor')->name('sensor');
        Route::post('plate', 'plate')->name('plate');
    });
