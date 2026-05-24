<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $selectedRole = $request->query('role', 'all');

        // 1. Fetch other ecosystem participants based on filters
        $usersQuery = User::where('_id', '!=', $currentUser->id);
        if ($selectedRole !== 'all') {
            $usersQuery->where('role', $selectedRole);
        }
        $users = $usersQuery->get();

        // 2. Fetch all connections involving current user to map statuses
        $connections = Connection::where(function ($query) use ($currentUser) {
            $query->where('sender_id', $currentUser->id)
                  ->orWhere('receiver_id', $currentUser->id);
        })->get();

        $connectionMap = [];
        foreach ($connections as $conn) {
            $otherUserId = ($conn->sender_id === $currentUser->id) ? $conn->receiver_id : $conn->sender_id;
            $connectionMap[$otherUserId] = [
                'id' => $conn->id,
                'status' => $conn->status,
                'is_sender' => ($conn->sender_id === $currentUser->id)
            ];
        }

        // 3. Fetch incoming pending requests for the action sidebar
        $pendingRequests = Connection::where('receiver_id', $currentUser->id)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return view('dashboard', compact('users', 'selectedRole', 'connectionMap', 'pendingRequests'));
    }
}
