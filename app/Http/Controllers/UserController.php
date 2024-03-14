<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\AbilitiesRule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Password;

class UserController extends AbstractController
{
    /**
     * Display a listing of the user resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', User::class);

        return JsonResource::collection(User::simplePaginate(50));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);

        $validated = $request->validate([
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'password' => ['required', Password::defaults()],
            'is_admin' => 'bool|nullable',
            'abilities' => ['nullable', 'array', new AbilitiesRule()],
        ]);

        return new JsonResource(User::create($validated));
    }

    /**
     * Display the specified user resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return new JsonResource($user);
    }

    /**
     * Update the specified user resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'email' => 'nullable|email:rfc,dns|unique:users|max:255',
            'password' => ['nullable', Password::defaults()],
            'is_admin' => 'bool|nullable',
            'abilities' => ['nullable', 'array', new AbilitiesRule()],
        ]);

        if (
            ($request->input('is_admin') !== null || !empty($request->input('abilities'))) &&
            !$request->user()->is_admin
        ) {
            abort_with_json([
                'reason' => 'You must be an administrator to update the is_admin or abilities attributes.',
            ]);
        }

        if ($request->input('is_admin') !== null) {
            $user->forceFill(['is_admin' => $request->boolean('is_admin')]);
        }

        /* Filters null, but not empty strings from validated array */
        $validated = array_filter($validated, fn ($value) => !is_null($value));
        $user->update($validated);

        return new JsonResource($user);
    }

    /**
     * Remove the specified user resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return new JsonResource($user);
    }

    /**
     * Restore the specified user resource from storage.
     */
    public function restore(User $user)
    {
        Gate::authorize('restore', $user);

        $user->restore();

        return new JsonResource($user);
    }
}
