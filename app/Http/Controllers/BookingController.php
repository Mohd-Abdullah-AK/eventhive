<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Attendees can see their bookings
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'attendee') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return Booking::with('event')->where('attendee_id', $user->id)->get();
    }

    // Attendees can book an event
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'attendee') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::find($validated['event_id']);

        if ($event->end_time < now()) {
            return response()->json(['message' => 'Cannot book past events.'], 400);
        }

        // Check for duplicate booking
        $alreadyBooked = Booking::where('event_id', $event->id)
            ->where('attendee_id', $user->id)
            ->exists();

        if ($alreadyBooked) {
            return response()->json(['message' => 'You have already booked this event.'], 409);
        }

        // Check for capacity
        $currentBookings = Booking::where('event_id', $event->id)->count();
        if ($currentBookings >= $event->capacity) {
            return response()->json(['message' => 'This event is fully booked.'], 409);
        }

        $booking = Booking::create([
            'event_id' => $event->id,
            'attendee_id' => $user->id,
        ]);

        return response()->json($booking->load('event'), 201);
    }
}
