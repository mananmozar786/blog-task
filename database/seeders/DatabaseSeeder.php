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
            'name' => 'Rabindranath Tagore',
            'role' => 'author',
            'email' => 'rabindranathtagore@blog.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Arundhati Roy',
            'role' => 'author',
            'email' => 'arundhatiroy@blog.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Vikram Seth',
            'role' => 'author',
            'email' => 'vikramseth@blog.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Tarak Mehta',
            'role' => 'user',
            'email' => 'tarakmehta@user.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Mike Patel',
            'role' => 'user',
            'email' => 'mikepatel@user.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->create([
            'name' => 'Tony Patel',
            'role' => 'user',
            'email' => 'tonypatel@user.com',
            'password' => Hash::make('password'),
        ]);
    }
}
