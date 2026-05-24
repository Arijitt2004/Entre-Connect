@extends('layouts.app')

@section('title', 'Events & RSVPs - EntreConnect')

@section('styles')
<style>
    .events-container {
        max-width: 900px;
        margin: 0 auto;
        padding-bottom: 3rem;
    }

    .events-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .events-header h1 {
        font-family: var(--font-outfit);
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .events-header p {
        color: var(--text-secondary);
    }

    /* Form Styles */
    .host-event-card {
        padding: 2rem;
        margin-bottom: 3rem;
    }

    .host-event-card h2 {
        font-family: var(--font-outfit);
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-family: var(--font-inter);
        transition: all 0.3s ease;
    }

    [data-theme="light"] .form-control {
        background: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    /* Event Feed */
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .event-card {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .event-date-badge {
        display: inline-block;
        background: rgba(99, 102, 241, 0.15);
        color: #818cf8;
        padding: 0.3rem 0.8rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .event-title {
        font-family: var(--font-outfit);
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .event-host {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .event-host .avatar {
        width: 24px;
        height: 24px;
    }

    .event-desc {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .event-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
        margin-top: auto;
    }

    .attendee-count {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .rsvp-btn {
        background: transparent;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.4rem 1rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rsvp-btn:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .rsvp-btn.attending {
        background: var(--gradient-primary);
        color: #fff;
        border: none;
    }

    .rsvp-btn.attending:hover {
        opacity: 0.9;
    }

    .join-link {
        font-size: 0.85rem;
        color: #38bdf8;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .join-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="events-container">
    <div class="events-header animate-fade-in">
        <h1>Virtual Events</h1>
        <p>Host or discover pitch competitions, networking mixers, and workshops.</p>
    </div>

    <!-- Host Event Form -->
    <div class="glass-panel host-event-card animate-fade-in" style="animation-delay: 0.1s;">
        <h2>Host a New Event</h2>
        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Founder-Investor Virtual Mixer" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="3" placeholder="What will this event be about?" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="event_date">Date</label>
                    <input type="date" name="event_date" id="event_date" class="form-control" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label for="event_time">Time</label>
                    <input type="time" name="event_time" id="event_time" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="meeting_link">Meeting Link (Zoom, Meet, etc.) - Optional</label>
                <input type="url" name="meeting_link" id="meeting_link" class="form-control" placeholder="https://zoom.us/j/...">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">Create Event</button>
        </form>
    </div>

    <!-- Events Grid -->
    <div class="events-grid">
        @forelse($events as $index => $event)
            <div class="glass-panel event-card animate-fade-in" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">
                <div class="event-date-badge">
                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }} at {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                </div>
                
                <h3 class="event-title">{{ $event->title }}</h3>
                
                <div class="event-host">
                    <img src="{{ $event->host->profile_image }}" alt="Host Avatar" class="avatar">
                    <span>Hosted by <strong>{{ $event->host->name }}</strong> ({{ str_replace('_', ' ', $event->host->role) }})</span>
                </div>
                
                <p class="event-desc">{{ Str::limit($event->description, 150) }}</p>
                
                @if($event->meeting_link && in_array(Auth::id(), $event->attendees ?? []))
                    <a href="{{ $event->meeting_link }}" target="_blank" class="join-link" style="margin-bottom: 1rem;">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        Join Meeting
                    </a>
                @endif

                <div class="event-footer">
                    <div class="attendee-count">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        {{ count($event->attendees ?? []) }} Attending
                    </div>

                    <form action="{{ route('events.rsvp', $event->id) }}" method="POST">
                        @csrf
                        @php
                            $isAttending = in_array(Auth::id(), $event->attendees ?? []);
                        @endphp
                        <button type="submit" class="rsvp-btn {{ $isAttending ? 'attending' : '' }}">
                            {{ $isAttending ? 'Attending ✓' : 'RSVP' }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);" class="glass-panel">
                <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3>No upcoming events.</h3>
                <p>Be the first to host a virtual networking event!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
