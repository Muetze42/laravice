<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        JsonResource::withoutWrapping();

        Password::defaults(static function () {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });

        $this->bootMacros();
    }

    /**
     * Bootstrap application macros.
     */
    protected function bootMacros(): void
    {
        Response::macro('error', function (
            string $message,
            int $status = 500,
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

        Storage::macro('relativePath', function (string $path): string {
            /* @var \Illuminate\Support\Facades\Storage $this */
            return ltrim(str_replace(base_path(''), '', $this->path($path)), '/\\');
        });

        Request::macro('enumD', function (string $key, $enumClass, mixed $default): mixed {
            /* @var \Illuminate\Support\Facades\Request $this */
            if (is_null($this->input($key))) {
                return $default;
            }

            return $this->enum($key, $enumClass);
        });
    }

    /**
     * Register the application filesystem managers.
     */
    protected function registerManagers(): void
    {
        $this->app->singleton('filesystem.media', function ($app) {
            return (new FilesystemManager($app))->disk('media');
        });
        $this->app->singleton('filesystem.temporary', function ($app) {
            return (new FilesystemManager($app))->disk('temporary');
        });
    }
}
