<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pitch extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pitches';

    protected $fillable = [
        'user_id',
        'content',
        'likes' // Array of user IDs who liked the pitch
    ];

    protected $casts = [
        'likes' => 'array',
    ];

    /**
     * Get the user that owns the pitch.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
