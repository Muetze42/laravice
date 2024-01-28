<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Rate Limiter
    |--------------------------------------------------------------------------
    |
    | This value is the rate limit for the API rate limiter of your application.
    | This value is used to limit any action during a specified window of time
    | for any API request.
    |
    */

    'api' => env('RATE_LIMITER_API_LIMIT', 60),

];
