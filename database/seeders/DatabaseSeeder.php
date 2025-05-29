<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'role' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Author1',
            'role' => 'author',
            'email' => 'author1@test.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Author2',
            'role' => 'author',
            'email' => 'author2@test.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Author3',
            'role' => 'author',
            'email' => 'author3@test.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'User1',
            'role' => 'user',
            'email' => 'user1@test.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'User2',
            'role' => 'user',
            'email' => 'user2@test.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'User3',
            'role' => 'user',
            'email' => 'user3@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}
