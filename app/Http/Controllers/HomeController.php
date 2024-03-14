<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Inspiring;

class HomeController extends AbstractController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return response()->json([
            'message' => 'Response successfully rendered in ' . round((microtime(true) - LARAVEL_START) * 1000) . 'ms.',
            'an_inspiring_quote' => Inspiring::quotes()->random(),
        ]);
    }
}
