<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rules\Password;

/**
 * @group User administration
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a users listing.
     */
    public function index()
    {
        return JsonResource::collection(User::simplePaginate(50));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => ['required', Password::defaults()],
            'is_admin' => 'bool|nullable',
        ]);

        return User::create($validated);
    }

    /**
     * Show the profile for a given user.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the given user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users|max:255',
            'password' => ['nullable', Password::defaults()],
            'is_admin' => 'bool|nullable',
        ]);

        if ($request->user()->is_admin) {
            $validated = $request->only(['name', 'email', 'password']);
        }

        /* Filters null, but not empty strings from validated array */
        $validated = array_filter($validated, fn ($value) => !is_null($value));
        $user->update($validated);

        return $user;
    }

    /**
     * Remove the given user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
