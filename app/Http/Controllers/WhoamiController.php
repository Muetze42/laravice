<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhoamiController extends Controller
{
    /**
     * Display the resource and used API token of the authenticated user token.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        /* @var \App\Models\PersonalAccessToken $accessToken */
        $accessToken = $user->currentAccessToken();

        return array_merge(
            $user->toArray(),
            ['token' => $accessToken->makeHidden(['tokenable', 'tokenable_type', 'tokenable_id'])->toArray()]
        );
    }
}
