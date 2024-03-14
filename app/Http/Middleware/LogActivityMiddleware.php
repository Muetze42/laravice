<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            User::withoutTimestamps(
                fn () => $user->forceFill(['active_at' => now()])->save()
            );

            if ($token = $user->currentAccessToken()) {
                /* @var \App\Models\ApiRequest $apiRequest */
                $apiRequest = $user->apiRequests()->create([
                    'path' => $request->path(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                ]);

                $apiRequest->token()->associate($token)->save();
            }
        }

        return $next($request);
    }
}
