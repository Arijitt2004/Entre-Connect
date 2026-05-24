<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:1000',
            'industry' => 'nullable|string|max:255',
            'stage' => 'nullable|string|max:255',
            'ticket_size' => 'nullable|string|max:255',
            'linkedin' => 'nullable|url|max:255',
            'profile_image' => 'nullable|url|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'title' => $request->title ?? ucfirst($user->role) . ' @ EntreConnect',
            'company' => $request->company ?? '',
            'bio' => $request->bio ?? '',
            'skills' => $request->skills ?? '',
            'industry' => $request->industry ?? '',
            'stage' => $request->stage ?? 'Idea Stage',
            'ticket_size' => $request->ticket_size ?? 'Not Specified',
            'linkedin' => $request->linkedin ?? '',
            'profile_image' => $request->profile_image ?? 'https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($request->name),
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }
}
