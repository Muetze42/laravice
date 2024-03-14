<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->authorize($request);

        return $next($request);
    }

    /**
     * Determine if the current user is authorized to perform the request.
     */
    protected function authorize(Request $request): void
    {
        $ability = str_replace('/', ':', trim($request->route()->uri(), '/'));

        if (
            $this->hasAbility($ability, $request->user()->abilities) &&
            $this->hasAbility($ability, $request->user()->currentAccessToken()->abilities)
        ) {
            return;
        }

        $response = new JsonResponse([
            'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
            'reason' => 'Missing ability to perform this action.',
            'required_ability' => $ability,
            'token_abilities' => $request->user()->currentAccessToken()->abilities,
            'user_abilities' => $request->user()->abilities,
        ], Response::HTTP_UNAUTHORIZED);

        throw new HttpResponseException($response);
    }

    protected function hasAbility(string $ability, array $abilities): bool
    {
        if (in_array('*', $abilities) || in_array($ability, $abilities)) {
            return true;
        }

        for ($i = -1; $i >= -5; $i--) {
            $parents = explode(':', $ability, $i);
            if (empty($parents)) {
                break;
            }
            if (in_array(implode(':', $parents) . ':*', $abilities)) {
                return true;
            }
        }

        return false;
    }
}
