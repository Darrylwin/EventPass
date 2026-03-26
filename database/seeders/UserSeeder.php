<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@eventpass.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'orga@eventpass.com'],
            [
                'name' => 'Organisateur Test',
                'password' => Hash::make('password'),
                'role' => 'organisateur',
            ]
        );

        User::updateOrCreate(
            ['email' => 'participant@eventpass.com'],
            [
                'name' => 'Participant Test',
                'password' => Hash::make('password'),
                'role' => 'participant',
            ]
        );
    }
}
