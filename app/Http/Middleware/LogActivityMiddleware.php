<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            DB::table('users')
                ->where('id', $user->getKey())
                ->update(['active_at' => now()]);

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
