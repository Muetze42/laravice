<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->isAdmin()->create(['email' => 'test-admin@huth.it']);
        \App\Models\User::factory()->create(['email' => 'test-user@huth.it']);
        \App\Models\User::factory(100)->create();
    }
}
