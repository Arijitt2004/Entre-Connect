<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'events';

    protected $fillable = [
        'host_id',
        'title',
        'description',
        'event_date',
        'event_time',
        'meeting_link',
        'attendees' // Array of user IDs who RSVP'd
    ];

    protected $casts = [
        'attendees' => 'array',
    ];

    /**
     * Get the user that is hosting the event.
     */
    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
}
