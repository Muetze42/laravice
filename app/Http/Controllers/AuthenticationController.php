<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\AbilitiesRule;
use App\Support\Ability;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends AbstractController
{
    /**
     * Display a listing of available abilities.
     */
    public function abilities()
    {
        return new JsonResource(Ability::grouped());
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'required|string|max:255',
            'expires_in' => 'nullable|int|min:10|max:525600',
            'abilities' => ['nullable', 'array', new AbilitiesRule()],
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => [__('auth.failed')],
            ]);
        }

        $expiresAt = $request->input('expires_in');
        if ($expiresAt) {
            $expiresAt = now()->addMinutes($expiresAt);
        }

        $token = $user->createToken($request->input('token_name'), ['*'], $expiresAt);

        /* @var \App\Models\PersonalAccessToken $accessToken */
        $accessToken = $token->accessToken;

        return new JsonResource([
            'token' => $token->plainTextToken,
            'name' => $accessToken->name,
            'expires_at' => $accessToken->expires_at,
            'user' => $user,
        ]);
    }
}
