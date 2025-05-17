<?php

namespace Database\Factories;
use App\Models\Event;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDays(2),
            'capacity' => 100,
            'organizer_id' => User::factory()->create(['role' => 'organizer'])->id,
        ];
    }
}
