<?php

namespace Tests\Feature\Participant;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed'); // Pour avoir les rôles si nécessaire, mais ici on va créer manuellement
    }

    public function test_participant_can_register_to_upcoming_event()
    {
        $participant = User::factory()->create(['role' => 'participant']);
        $organizer = User::factory()->create(['role' => 'organisateur']);

        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Concert de Test',
            'description' => 'Un super concert.',
            'starts_at' => now()->addDays(5),
            'location' => 'Paris',
            'capacity' => 100,
            'price' => 5000,
            'status' => 'publié',
        ]);

        $response = $this->actingAs($participant)
            ->post(route('participant.events.register', $event));

        $response->assertRedirect(route('participant.dashboard'));
        $this->assertDatabaseHas('registrations', [
            'event_id' => $event->id,
            'user_id' => $participant->id,
            'status' => 'validé',
        ]);

        $registration = Registration::first();
        $this->assertNotNull($registration->pass_code);
        $this->assertEquals(8, strlen($registration->pass_code));
    }

    public function test_participant_cannot_register_twice_for_same_event()
    {
        $participant = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['starts_at' => now()->addDays(1), 'capacity' => 10]);

        // Première inscription
        $this->actingAs($participant)->post(route('participant.events.register', $event));

        // Deuxième essai
        $response = $this->actingAs($participant)
            ->from(route('participant.dashboard'))
            ->post(route('participant.events.register', $event));

        $response->assertRedirect(route('participant.dashboard'));
        $response->assertSessionHas('error', 'Vous êtes déjà inscrit à cet événement.');
        $this->assertEquals(1, Registration::count());
    }

    public function test_participant_cannot_register_to_full_event()
    {
        $participant = User::factory()->create(['role' => 'participant']);
        $event = Event::factory()->create(['starts_at' => now()->addDays(1), 'capacity' => 1]);

        // On remplit l'événement
        Registration::create([
            'event_id' => $event->id,
            'user_id' => User::factory()->create()->id,
            'pass_code' => 'FULLTEST',
            'status' => 'validé',
            'registered_at' => now(),
        ]);

        $response = $this->actingAs($participant)
            ->post(route('participant.events.register', $event));

        $response->assertSessionHas('error', 'Désolé, cet événement est complet.');
        $this->assertEquals(1, Registration::where('event_id', $event->id)->count());
    }
}
