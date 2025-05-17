<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Event;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@eventhive.com',
            'password' => \Hash::make('password'),
            'role' => 'admin',
        ]);

        // Organizer
        User::create([
            'name' => 'Organizer One',
            'email' => 'organizer@eventhive.com',
            'password' => \Hash::make('password'),
            'role' => 'organizer',
        ]);

        // Attendee
        User::create([
            'name' => 'Attendee One',
            'email' => 'attendee@eventhive.com',
            'password' => \Hash::make('password'),
            'role' => 'attendee',
        ]);
    }
}
