<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // List all threads
    public function index()
    {
        $connectedUsers = $this->getConnectedUsers();
        $activeUser = count($connectedUsers) > 0 ? $connectedUsers[0] : null;
        $messages = [];

        if ($activeUser) {
            $messages = $this->getChatMessages($activeUser->id);
        }

        return view('messages', compact('connectedUsers', 'activeUser', 'messages'));
    }

    // View specific thread
    public function showThread($userId)
    {
        $connectedUsers = $this->getConnectedUsers();
        $activeUser = User::findOrFail($userId);
        
        // Ensure they are connected
        $isConnected = false;
        foreach ($connectedUsers as $u) {
            if ($u->id === $activeUser->id) {
                $isConnected = true;
                break;
            }
        }

        if (!$isConnected) {
            return redirect()->route('messages')->withErrors(['error' => 'You can only message connected members.']);
        }

        $messages = $this->getChatMessages($activeUser->id);

        return view('messages', compact('connectedUsers', 'activeUser', 'messages'));
    }

    // Send a message
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent!');
    }

    // Helper: get all connected users
    private function getConnectedUsers()
    {
        $myId = Auth::id();
        $connections = Connection::where('status', 'accepted')
            ->where(function ($query) use ($myId) {
                $query->where('sender_id', $myId)
                      ->orWhere('receiver_id', $myId);
            })->get();

        $connectedUsers = [];
        foreach ($connections as $conn) {
            $otherId = ($conn->sender_id === $myId) ? $conn->receiver_id : $conn->sender_id;
            $user = User::find($otherId);
            if ($user) {
                $connectedUsers[] = $user;
            }
        }

        return $connectedUsers;
    }

    // Helper: get message list for active user thread
    private function getChatMessages($otherUserId)
    {
        $myId = Auth::id();
        return Message::where(function ($query) use ($myId, $otherUserId) {
            $query->where('sender_id', $myId)->where('receiver_id', $otherUserId);
        })->orWhere(function ($query) use ($myId, $otherUserId) {
            $query->where('sender_id', $otherUserId)->where('receiver_id', $myId);
        })->orderBy('created_at', 'asc')->get();
    }
}
