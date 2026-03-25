<?php

namespace Tests\Feature\Organisateur;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event_with_image_upload(): void
    {
        Storage::fake('public');

        $organizer = User::factory()->create([
            'role' => 'organisateur',
        ]);

        $payload = [
            'title' => 'Salon Tech',
            'description' => 'Description test',
            'starts_at' => now()->addDays(7)->format('Y-m-d H:i:s'),
            'location' => 'Lome',
            'capacity' => 100,
            'price' => 15000,
            'status' => 'publié',
            'image_path' => UploadedFile::fake()->image('event.jpg'),
        ];

        $response = $this->actingAs($organizer)
            ->post(route('organisateur.events.store'), $payload);

        $response->assertRedirect(route('organisateur.events.index'));

        $event = Event::query()->first();

        $this->assertNotNull($event);
        $this->assertSame($organizer->id, $event->organizer_id);
        $this->assertSame('Salon Tech', $event->title);
        $this->assertNotNull($event->image_path);

        $this->assertTrue(Storage::disk('public')->exists($event->image_path));
    }

    public function test_organizer_can_invalidate_and_reactivate_a_pass(): void
    {
        $organizer = User::factory()->create([
            'role' => 'organisateur',
        ]);

        $participant = User::factory()->create([
            'role' => 'participant',
        ]);

        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Event Test',
            'description' => 'Description',
            'starts_at' => now()->addDay(),
            'location' => 'Lome',
            'capacity' => 50,
            'price' => 0,
            'status' => 'publié',
        ]);

        $registration = Registration::create([
            'event_id' => $event->id,
            'user_id' => $participant->id,
            'pass_code' => 'PASS1234',
            'status' => 'validé',
            'registered_at' => now(),
        ]);

        $this->actingAs($organizer)
            ->patch(route('organisateur.registrations.invalidate', $registration))
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'annulé',
        ]);

        $this->actingAs($organizer)
            ->patch(route('organisateur.registrations.reactivate', $registration))
            ->assertRedirect();

        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'validé',
        ]);
    }

    public function test_organizer_can_filter_events_by_status_and_date(): void
    {
        $organizer = User::factory()->create([
            'role' => 'organisateur',
        ]);

        Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Future Published',
            'description' => 'Description',
            'starts_at' => '2026-04-15 10:00:00',
            'location' => 'Lome',
            'capacity' => 20,
            'price' => 0,
            'status' => 'publié',
        ]);

        Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Past Draft',
            'description' => 'Description',
            'starts_at' => '2026-03-01 10:00:00',
            'location' => 'Lome',
            'capacity' => 20,
            'price' => 0,
            'status' => 'brouillon',
        ]);

        $response = $this->actingAs($organizer)
            ->get(route('organisateur.events.index', [
                'status' => 'publié',
                'starts_from' => '2026-04-01',
                'starts_to' => '2026-04-30',
            ]));

        $response->assertOk();
        $response->assertSee('Future Published');
        $response->assertDontSee('Past Draft');
    }
}
