@extends('layouts.app')

@section('title', 'Pitch Board - EntreConnect')

@section('styles')
<style>
    .pitches-container {
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }

    .pitch-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .pitch-header h1 {
        font-family: var(--font-outfit);
        font-size: 2.5rem;
        font-weight: 800;
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .pitch-header p {
        color: var(--text-secondary);
    }

    .create-pitch-card {
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .pitch-textarea {
        width: 100%;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        color: var(--text-primary);
        font-family: var(--font-inter);
        font-size: 1rem;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .pitch-textarea:focus {
        outline: none;
        border-color: rgba(99, 102, 241, 0.5);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .pitch-actions {
        display: flex;
        justify-content: flex-end;
    }

    .pitch-feed {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .pitch-card {
        padding: 1.5rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .pitch-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }

    .pitch-author {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .author-info h3 {
        font-family: var(--font-outfit);
        font-size: 1.1rem;
        color: var(--text-primary);
    }

    .author-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    .pitch-content {
        font-size: 1.05rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.5rem;
        white-space: pre-wrap;
    }

    .pitch-footer {
        display: flex;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1rem;
    }

    .like-btn {
        background: transparent;
        border: none;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .like-btn:hover {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }

    .like-btn.liked {
        color: #ef4444;
    }

    .like-btn.liked:hover {
        background: rgba(239, 68, 68, 0.1);
    }

    .like-icon {
        width: 18px;
        height: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<div class="pitches-container">
    <div class="pitch-header animate-fade-in">
        <h1>Pitch Board</h1>
        <p>Share updates, ideas, or seek collaboration with the ecosystem.</p>
    </div>

    <!-- Create Pitch Form -->
    <div class="glass-panel create-pitch-card animate-fade-in" style="animation-delay: 0.1s;">
        <form action="{{ route('pitches.store') }}" method="POST">
            @csrf
            <textarea 
                name="content" 
                class="pitch-textarea" 
                placeholder="What are you building? Share your pitch..."
                required
                maxlength="1000"
            ></textarea>
            @error('content')
                <div style="color: #ef4444; font-size: 0.85rem; margin-bottom: 1rem; margin-top: -0.5rem;">
                    {{ $message }}
                </div>
            @enderror
            <div class="pitch-actions">
                <button type="submit" class="btn btn-primary">Post Pitch</button>
            </div>
        </form>
    </div>

    <!-- Pitch Feed -->
    <div class="pitch-feed">
        @forelse($pitches as $index => $pitch)
            <div class="glass-panel pitch-card animate-fade-in" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s;">
                <div class="pitch-author">
                    <img src="{{ $pitch->user->profile_image }}" alt="avatar" class="avatar" style="width: 48px; height: 48px;">
                    <div class="author-info">
                        <h3>{{ $pitch->user->name }}</h3>
                        <div class="author-meta">
                            <span class="role-badge role-{{ $pitch->user->role }}">{{ str_replace('_', ' ', $pitch->user->role) }}</span>
                            <span>&bull;</span>
                            <span>{{ $pitch->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="pitch-content">{{ $pitch->content }}</div>
                
                <div class="pitch-footer">
                    <form action="{{ route('pitches.like', $pitch->id) }}" method="POST">
                        @csrf
                        @php
                            $hasLiked = in_array(Auth::id(), $pitch->likes ?? []);
                            $likeCount = count($pitch->likes ?? []);
                        @endphp
                        <button type="submit" class="like-btn {{ $hasLiked ? 'liked' : '' }}">
                            <svg class="like-icon" fill="{{ $hasLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            {{ $likeCount }} {{ Str::plural('Like', $likeCount) }}
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-panel empty-state animate-fade-in" style="animation-delay: 0.2s;">
                <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <h3>No pitches yet!</h3>
                <p>Be the first to share your startup idea or update.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
