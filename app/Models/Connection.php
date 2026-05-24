<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Connection extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'connections';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status', // 'pending', 'accepted', 'declined'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
