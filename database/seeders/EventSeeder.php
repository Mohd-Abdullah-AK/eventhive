<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = User::where('role', 'organizer')->first();

        Event::create([
            'title' => 'Tech Conference 2025',
            'description' => 'A conference about future technologies.',
            'address' => '123 Innovation Blvd',
            'city' => 'San Francisco',
            'country' => 'USA',
            'start_time' => now()->addDays(15),
            'end_time' => now()->addDays(16),
            'capacity' => 200,
            'organizer_id' => $organizer->id,
        ]);

        Event::create([
            'title' => 'Startup Pitch Night',
            'description' => 'Local startups pitch their ideas.',
            'address' => '456 Business Ave',
            'city' => 'Berlin',
            'country' => 'Germany',
            'start_time' => now()->addDays(30),
            'end_time' => now()->addDays(30)->addHours(3),
            'capacity' => 100,
            'organizer_id' => $organizer->id,
        ]);
    }
}
