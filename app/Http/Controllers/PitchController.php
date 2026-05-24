<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PitchController extends Controller
{
    public function index()
    {
        $pitches = Pitch::with('user')->orderBy('created_at', 'desc')->get();
        return view('pitches.index', compact('pitches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Pitch::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'likes' => [],
        ]);

        return redirect()->route('pitches.index')->with('success', 'Your pitch has been posted!');
    }

    public function like($id)
    {
        $pitch = Pitch::findOrFail($id);
        $userId = Auth::id();

        $likes = $pitch->likes ?? [];

        if (in_array($userId, $likes)) {
            // Unlike
            $likes = array_diff($likes, [$userId]);
        } else {
            // Like
            $likes[] = $userId;
        }

        // Re-index array to prevent associative array issues in MongoDB
        $pitch->likes = array_values($likes);
        $pitch->save();

        return redirect()->route('pitches.index');
    }
}
