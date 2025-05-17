<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_can_book_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'capacity' => 10,
        ]);

        $token = $attendee->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ])->postJson('/api/bookings', [
            'event_id' => $event->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'event']);
    }

    public function test_booking_fails_when_event_is_full()
    {
        $attendee1 = User::factory()->create(['role' => 'attendee']);
        $attendee2 = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'capacity' => 1,
        ]);

        // First attendee books
        $event->bookings()->create([
            'attendee_id' => $attendee1->id,
        ]);

        // Second attendee tries to book
        $token = $attendee2->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ])->postJson('/api/bookings', [
            'event_id' => $event->id,
        ]);

        $response->assertStatus(409)
                 ->assertJson(['message' => 'This event is fully booked.']);
    }

    public function test_booking_fails_for_past_event()
    {
        $attendee = User::factory()->create(['role' => 'attendee']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $event = Event::factory()->create([
            'organizer_id' => $organizer->id,
            'start_time' => now()->subDays(2),
            'end_time' => now()->subDay(),
            'capacity' => 100,
        ]);

        $token = $attendee->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ])->postJson('/api/bookings', [
            'event_id' => $event->id,
        ]);

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Cannot book past events.']);
    }
}
