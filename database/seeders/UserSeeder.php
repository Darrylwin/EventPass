<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@eventpass.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Organisateur Test',
            'email' => 'orga@eventpass.com',
            'password' => Hash::make('password'),
            'role' => 'organisateur',
        ]);

        User::create([
            'name' => 'Participant Test',
            'email' => 'participant@eventpass.com',
            'password' => Hash::make('password'),
            'role' => 'participant',
        ]);
    }
}
