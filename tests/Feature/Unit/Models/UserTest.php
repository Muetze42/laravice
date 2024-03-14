<?php

namespace Tests\Feature\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanBeCreatedWithFillableAttributes(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function testPasswordIsHashedWhenUserIsCreated(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function testHiddenAttributesAreNotSerialized(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'remember_token' => 'token',
        ]);

        $serializedUser = $user->toArray();

        $this->assertArrayNotHasKey('password', $serializedUser);
        $this->assertArrayNotHasKey('remember_token', $serializedUser);
    }

    public function testCastsAttributesAreCastedCorrectly(): void
    {
        $user = User::factory()->isAdmin()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'active_at' => now(),
        ]);

        $this->assertInstanceOf(\Carbon\Carbon::class, $user->active_at);
        $this->assertTrue($user->is_admin);
    }
}
