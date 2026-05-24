<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function connect($userId)
    {
        $currentUserId = Auth::id();

        // Prevent self-connections
        if ($currentUserId === $userId) {
            return back()->withErrors(['error' => 'You cannot connect with yourself.']);
        }

        // Check if connection already exists
        $existing = Connection::where(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $currentUserId)->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $userId)->where('receiver_id', $currentUserId);
        })->first();

        if (!$existing) {
            Connection::create([
                'sender_id' => $currentUserId,
                'receiver_id' => $userId,
                'status' => 'pending',
            ]);
        }

        return back()->with('success', 'Connection request sent!');
    }

    public function accept($connectionId)
    {
        $connection = Connection::findOrFail($connectionId);

        // Ensure current user is the receiver
        if ($connection->receiver_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }

        $connection->update(['status' => 'accepted']);

        return back()->with('success', 'Connection request accepted!');
    }

    public function decline($connectionId)
    {
        $connection = Connection::findOrFail($connectionId);

        // Ensure current user is the receiver
        if ($connection->receiver_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Unauthorized action.']);
        }

        // We delete on decline so they can connect again later if needed
        $connection->delete();

        return back()->with('success', 'Connection request declined.');
    }
}
