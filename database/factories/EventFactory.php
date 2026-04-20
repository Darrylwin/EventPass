<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organizer_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'starts_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'location' => $this->faker->city,
            'capacity' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->randomElement([0, 1000, 5000, 10000]),
            'status' => 'publié',
        ];
    }
}
