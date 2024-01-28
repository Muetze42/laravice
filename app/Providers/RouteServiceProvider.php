<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $apiLimit = (int) config('rate-limiter.api', 60);
        if ($apiLimit) {
            RateLimiter::for(
                'api',
                fn (Request $request): Limit => Limit::perMinute($apiLimit)
                    ->by($request->user()?->id ?: $request->ip())
            );
        }

        $this->routes(function () {
            Route::middleware(['api', 'auth:sanctum'])
                ->group(base_path('routes/api.php'));
        });
    }
}
