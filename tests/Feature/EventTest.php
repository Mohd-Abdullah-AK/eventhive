<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_event()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $token = $organizer->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json',
        ])->postJson('/api/events', [
            'title' => 'Sample Event',
            'description' => 'Test event description',
            'address' => '123 Test St',
            'city' => 'Testville',
            'country' => 'Testland',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDays(2),
            'capacity' => 100,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'city']);
    }
}
