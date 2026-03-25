<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organisateur = User::where('email', 'orga@eventpass.com')->first();

        Event::create([
            'organizer_id' => $organisateur->id,
            'title' => 'Conférence Laravel 2025',
            'description' => 'Une journée autour de Laravel et de son écosystème.',
            'starts_at' => now()->addDays(10),
            'location' => 'Lomé, Togo',
            'capacity' => 50,
            'price' => 0,
            'status' => 'publié',
        ]);

        Event::create([
            'organizer_id' => $organisateur->id,
            'title' => 'Atelier Vue.js',
            'description' => 'Initiation pratique à Vue.js.',
            'starts_at' => now()->addDays(20),
            'location' => 'Lomé, Togo',
            'capacity' => 30,
            'price' => 5000,
            'status' => 'publié',
        ]);

        Event::create([
            'organizer_id' => $organisateur->id,
            'title' => 'Meetup passé',
            'description' => 'Événement déjà terminé.',
            'starts_at' => now()->subDays(5),
            'location' => 'Lomé, Togo',
            'capacity' => 20,
            'price' => 0,
            'status' => 'terminé',
        ]);
    }
}
