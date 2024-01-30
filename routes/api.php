<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Images\Manipulation\SpatieImageController;
use App\Http\Controllers\Images\RemoveBackground\ImglyBackgroundRemovalNodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhoamiController;
use App\Http\Controllers\Images\WebPConverter\ImagickController;
use App\Services\Images\WebPConverter\ImagickService;
use App\Services\Images\Manipulation\SpatieImageService;
use App\Services\Images\RemoveBackground\ImglyBackgroundRemovalNodeService;
use Illuminate\Foundation\Inspiring;
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

Route::withoutMiddleware('auth:sanctum')->group(function () {
    Route::get('/', fn () => response()->message(Inspiring::quotes()->random()))->name('home');
    Route::post('login', AuthenticationController::class)->name('auth.login');

    /* Yes I know. Not so nice, but I want this way. :P - Norman */
    if (app()->isLocal() && class_exists('App\Http\Controllers\DebugController')) {
        Route::get('debug', \App\Http\Controllers\DebugController::class);
    }
});

Route::apiResource('users', UserController::class);
Route::get('whoami', WhoamiController::class)->name('whoami');

if (ImglyBackgroundRemovalNodeService::active()) {
    Route::post('images/remove-background/imgly-background-removal-node', ImglyBackgroundRemovalNodeController::class);
}
if (SpatieImageService::active()) {
    Route::post('images/manipulation/spatie-image', SpatieImageController::class);
}
if (ImagickService::active()) {
    Route::post('images/webp-converter/imagick', ImagickController::class);
}
