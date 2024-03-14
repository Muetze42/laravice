<?php

namespace App\Support;

use Illuminate\Routing\Route;

class Ability
{
    /**
     * List available abilities.
     */
    public static function list(): array
    {
        $router = app('router');

        return collect($router->getRoutes()->getRoutesByName())
            ->filter(
                fn (Route $route) => count(array_intersect(
                    $route->middleware(),
                    ['auth:sanctum', 'Illuminate\Auth\Middleware\Authenticate:sanctum']
                ))
            )->map(
                function (Route $route): string {
                    return self::format($route);
                }
            )->values()->unique()->toArray();
    }

    public static function format(Route $route): string
    {
        $ability = '';
        $parts = explode('/', trim($route->uri(), '/'));

        foreach ($parts as $part) {
            if ($ability) {
                $ability .= ':';
            }
            $ability .= str_starts_with($part, '{') ? $route->getActionMethod() : $part;
        }

        return $ability;
    }

    /**
     * List available abilities grouped with parents.
     */
    public static function grouped(): array
    {
        $abilities = self::list();

        $grouped = ['*'];

        foreach ($abilities as $ability) {
            self::group($ability, $grouped);
        }

        sort($grouped);

        return $grouped;
    }

    /**
     * Grouping ability.
     */
    protected static function group(string $ability, array &$grouped): void
    {
        $parts = explode(':', $ability);

        $count = count($parts) - 1;
        $parent = '';

        if ($count) {
            foreach ($parts as $key => $part) {
                if ($key < $count) {
                    $parent .= $part . ':';
                    if (!in_array($parent . '*', $grouped)) {
                        $grouped[] = $parent . '*';
                    }
                }
            }
        }

        $grouped[] = $ability;
    }
}
