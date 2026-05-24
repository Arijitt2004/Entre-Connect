<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('host')->orderBy('event_date', 'asc')->get();
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required|string',
            'meeting_link' => 'nullable|url'
        ]);

        Event::create([
            'host_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'meeting_link' => $request->meeting_link,
            'attendees' => [],
        ]);

        return redirect()->route('events.index')->with('success', 'Event successfully created!');
    }

    public function rsvp($id)
    {
        $event = Event::findOrFail($id);
        $userId = Auth::id();

        $attendees = $event->attendees ?? [];

        if (in_array($userId, $attendees)) {
            // Cancel RSVP
            $attendees = array_diff($attendees, [$userId]);
        } else {
            // RSVP
            $attendees[] = $userId;
        }

        // Re-index array for MongoDB
        $event->attendees = array_values($attendees);
        $event->save();

        return redirect()->route('events.index');
    }
}
