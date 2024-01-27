<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentication
 */
class AuthenticationController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'required|string|max:255',
            'expires_in' => 'nullable|int|min:10|max:525600',
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

        /* No named arguments. See: https://laravel.com/docs/10.x/releases#named-argument */
        $token = $user->createToken($request->input('token_name'), ['*'], $expiresAt);

        /* @var \App\Models\PersonalAccessToken $accessToken */
        $accessToken = $token->accessToken;

        return [
            'token' => $token->plainTextToken,
            'name' => $accessToken->name,
            'abilities' => $accessToken->abilities,
            'expires_at' => $accessToken->expires_at,
            'user' => $user,
        ];
    }
}
