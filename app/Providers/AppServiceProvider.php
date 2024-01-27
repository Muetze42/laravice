<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerManagers();
    }

    /**
     * Register the application filesystem managers.
     *
     * @return void
     */
    protected function registerManagers(): void
    {
        $this->app->singleton('filesystem.documents', function ($app) {
            return (new FilesystemManager($app))->disk('documents');
        });
        $this->app->singleton('filesystem.media', function ($app) {
            return (new FilesystemManager($app))->disk('media');
        });
        $this->app->singleton('filesystem.temporary', function ($app) {
            return (new FilesystemManager($app))->disk('temporary');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        $this->bootMacros();
        Password::defaults(
            static fn () => Password::min(size: 12)->letters()->mixedCase()->numbers()->symbols()->uncompromised()
        );
    }

    /**
     * Bootstrap application macros.
     */
    protected function bootMacros(): void
    {
        Response::macro('message', function (
            string $message,
            int $status = 200,
            array $headers = [],
            int $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        ): JsonResponse {
            /* @var \Illuminate\Support\Facades\Response $this */
            return $this->json(
                ['message' => $message],
                $status,
                $headers,
                $options
            );
        });
    }
}
