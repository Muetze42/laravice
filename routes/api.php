<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Images\RemoveBackgroundController;
use App\Http\Controllers\WhoamiController;
use App\Http\Middleware\CheckAbilityMiddleware;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
    ->name('health');

Route::get('abilities', [AuthenticationController::class, 'abilities'])
    ->name('authenticate');
Route::post('authenticate', [AuthenticationController::class, 'authenticate'])
    ->name('authenticate');

Route::middleware([Authenticate::using('sanctum'), CheckAbilityMiddleware::class])->group(function () {
    Route::get('whoami', WhoamiController::class)->name('whoami');

    /**
     * Images.
     */
    Route::prefix('images')->name('images.')->group(function () {
        /**
         * Remove background.
         */
        Route::prefix('remove-background')->name('remove-background.')
            ->controller(RemoveBackgroundController::class)
            ->group(function () {
                Route::post('imgly-background-removal-node', 'imglyBackgroundRemovalNode')
                    ->name('imgly-background-removal-node');
            });
    });
});
