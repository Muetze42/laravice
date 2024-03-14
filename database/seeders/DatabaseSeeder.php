<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->isAdmin()->create([
            'email' => 'admin@huth.it',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'email' => 'user@huth.it',
            'password' => bcrypt('password'),
        ]);
    }
}
