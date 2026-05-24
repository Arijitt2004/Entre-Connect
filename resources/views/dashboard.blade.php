@extends('layouts.app')

@section('title', 'Ecosystem Feed - EntreConnect')

@section('styles')
<style>
    .feed-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }

    .tab-item {
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        padding: 0.6rem 1.2rem;
        border-radius: 9999px;
        text-decoration: none;
        font-family: var(--font-outfit);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .tab-item:hover, .tab-item.active {
        color: var(--text-primary);
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.2);
    }

    .tab-item.active[data-tab="all"] { border-color: #6366f1; background: rgba(99, 102, 241, 0.1); color: #818cf8; }
    .tab-item.active[data-tab="founder"] { border-color: var(--accent-founder); background: rgba(129, 140, 248, 0.1); color: var(--accent-founder); }
    .tab-item.active[data-tab="co_founder"] { border-color: var(--accent-cofounder); background: rgba(45, 212, 191, 0.1); color: var(--accent-cofounder); }
    .tab-item.active[data-tab="investor"] { border-color: var(--accent-investor); background: rgba(52, 211, 153, 0.1); color: var(--accent-investor); }
    .tab-item.active[data-tab="mentor"] { border-color: var(--accent-mentor); background: rgba(251, 146, 60, 0.1); color: var(--accent-mentor); }
    .tab-item.active[data-tab="entrepreneur"] { border-color: var(--accent-entrepreneur); background: rgba(244, 114, 182, 0.1); color: var(--accent-entrepreneur); }

    /* Feed Grid */
    .feed-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1.5rem;
    }

    .user-card {
        padding: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .user-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    /* Color indicators on cards */
    .user-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--border-color);
    }

    .card-founder::before { background: var(--accent-founder); }
    .card-investor::before { background: var(--accent-investor); }
    .card-mentor::before { background: var(--accent-mentor); }
    .card-cofounder::before { background: var(--accent-cofounder); }
    .card-entrepreneur::before { background: var(--accent-entrepreneur); }

    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        background: rgba(255, 255, 255, 0.05);
    }

    .card-title-wrap {
        display: flex;
        flex-direction: column;
    }

    .card-name {
        font-family: var(--font-outfit);
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .card-title {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .card-bio {
        font-size: 0.85rem;
        color: var(--text-secondary);
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-meta {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.03);
        border-radius: 8px;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        font-size: 0.8rem;
    }

    .meta-row {
        display: flex;
        justify-content: space-between;
    }

    .meta-label {
        color: var(--text-muted);
        font-weight: 600;
    }

    .meta-val {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        min-height: 25px;
    }

    .card-tag {
        font-size: 0.7rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-secondary);
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
    }

    /* Pending Panel Sidebar */
    .sidebar-panel {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-self: start;
    }

    .sidebar-title {
        font-family: var(--font-outfit);
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .request-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .request-card {
        padding: 1rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.04);
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .request-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .request-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
    }

    .request-info {
        display: flex;
        flex-direction: column;
    }

    .request-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .request-role {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #818cf8;
    }

    .request-actions {
        display: flex;
        gap: 0.5rem;
    }

    .req-btn {
        flex: 1;
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
    }

    .req-btn-accept {
        background: #10b981;
        color: #ffffff;
    }

    .req-btn-accept:hover {
        background: #059669;
    }

    .req-btn-decline {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .req-btn-decline:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .empty-state {
        text-align: center;
        color: var(--text-muted);
        font-size: 0.85rem;
        padding: 1rem 0;
    }

    .success-alert {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #a7f3d0;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 900px) {
        .feed-layout {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@if (session('success'))
    <div class="success-alert">
        {{ session('success') }}
    </div>
@endif

<div class="feed-layout">
    <!-- Main Feed Section -->
    <div>
        <!-- Role Filters -->
        <div class="filter-tabs">
            <a href="{{ route('dashboard', ['role' => 'all']) }}" class="tab-item {{ $selectedRole === 'all' ? 'active' : '' }}" data-tab="all">All Ecosystem</a>
            <a href="{{ route('dashboard', ['role' => 'founder']) }}" class="tab-item {{ $selectedRole === 'founder' ? 'active' : '' }}" data-tab="founder">Founders</a>
            <a href="{{ route('dashboard', ['role' => 'co_founder']) }}" class="tab-item {{ $selectedRole === 'co_founder' ? 'active' : '' }}" data-tab="co_founder">Co-Founders</a>
            <a href="{{ route('dashboard', ['role' => 'investor']) }}" class="tab-item {{ $selectedRole === 'investor' ? 'active' : '' }}" data-tab="investor">Investors</a>
            <a href="{{ route('dashboard', ['role' => 'mentor']) }}" class="tab-item {{ $selectedRole === 'mentor' ? 'active' : '' }}" data-tab="mentor">Mentors</a>
            <a href="{{ route('dashboard', ['role' => 'entrepreneur']) }}" class="tab-item {{ $selectedRole === 'entrepreneur' ? 'active' : '' }}" data-tab="entrepreneur">Entrepreneurs</a>
        </div>

        @if(count($users) === 0)
            <div class="glass-panel" style="padding: 4rem; text-align: center; color: var(--text-secondary);">
                <p style="font-size: 1.2rem; font-family: var(--font-outfit); font-weight: 600; margin-bottom: 0.5rem;">No Members Found</p>
                <p style="font-size: 0.9rem; color: var(--text-muted);">Be the pioneer to invite participants of this role to EntreConnect!</p>
            </div>
        @else
            <!-- Feed Grid -->
            <div class="feed-grid">
                @foreach ($users as $user)
                    <div class="glass-panel user-card card-{{ $user->role }}">
                        <div class="card-header">
                            <img src="{{ $user->profile_image }}" alt="avatar" class="card-avatar">
                            <div class="card-title-wrap">
                                <span class="card-name">{{ $user->name }}</span>
                                <span class="card-title">{{ $user->title }}</span>
                            </div>
                        </div>
                        
                        <p class="card-bio">{{ $user->bio }}</p>

                        <!-- Role Specific Criteria Meta -->
                        <div class="card-meta">
                            <div class="meta-row">
                                <span class="meta-label">Industry:</span>
                                <span class="meta-val">{{ $user->industry ?: 'Not Specified' }}</span>
                            </div>
                            
                            @if ($user->role === 'founder' || $user->role === 'co_founder')
                                <div class="meta-row">
                                    <span class="meta-label">Startup Stage:</span>
                                    <span class="meta-val" style="color: var(--accent-founder);">{{ $user->stage ?: 'Idea Stage' }}</span>
                                </div>
                            @elseif ($user->role === 'investor')
                                <div class="meta-row">
                                    <span class="meta-label">Ticket Size:</span>
                                    <span class="meta-val" style="color: var(--accent-investor);">{{ $user->ticket_size ?: 'Not Specified' }}</span>
                                </div>
                            @elseif ($user->role === 'mentor')
                                <div class="meta-row">
                                    <span class="meta-label">Mentorship:</span>
                                    <span class="meta-val" style="color: var(--accent-mentor);">Active Mentor</span>
                                </div>
                            @endif
                        </div>

                        <!-- Skills Tags -->
                        <div class="card-tags">
                            @if ($user->skills)
                                @foreach (explode(',', $user->skills) as $skill)
                                    @if (trim($skill))
                                        <span class="card-tag">{{ trim($skill) }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span class="card-tag" style="background:transparent; border: 1px dashed var(--border-color); color: var(--text-muted);">No tags listed</span>
                            @endif
                        </div>

                        <!-- Connect Action Logic -->
                        @php
                            $conn = $connectionMap[$user->id] ?? null;
                        @endphp

                        @if (!$conn)
                            <!-- Not connected: Send request form -->
                            <form action="{{ route('connect', ['userId' => $user->id]) }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                    Connect & Match
                                </button>
                            </form>
                        @elseif ($conn['status'] === 'pending')
                            @if ($conn['is_sender'])
                                <!-- Sent pending request -->
                                <button class="btn btn-secondary" style="width: 100%; padding: 0.5rem 1rem; font-size: 0.85rem; cursor: not-allowed; opacity: 0.7;" disabled>
                                    📤 Request Pending
                                </button>
                            @else
                                <!-- Received pending request -->
                                <div style="display: flex; gap: 0.5rem; width: 100%;">
                                    <form action="{{ route('connect.accept', ['connectionId' => $conn['id']]) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="req-btn req-btn-accept" style="width:100%; padding: 0.5rem;">Accept</button>
                                    </form>
                                    <form action="{{ route('connect.decline', ['connectionId' => $conn['id']]) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        <button type="submit" class="req-btn req-btn-decline" style="width:100%; padding: 0.5rem;">Decline</button>
                                    </form>
                                </div>
                            @endif
                        @elseif ($conn['status'] === 'accepted')
                            <!-- Accepted: Open Chat Thread -->
                            <a href="{{ route('messages.thread', ['userId' => $user->id]) }}" class="btn btn-secondary" style="width: 100%; padding: 0.5rem 1rem; font-size: 0.85rem; border-color: rgba(52, 211, 153, 0.3); color: #34d399;">
                                💬 Send Message
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Sidebar Connection Request Panel -->
    <div class="glass-panel sidebar-panel">
        <h2 class="sidebar-title">
            <span>📥</span> Connection Requests
        </h2>
        
        <div class="request-list">
            @if (count($pendingRequests) === 0)
                <div class="empty-state">
                    No pending invites.<br>Your feed is up to date!
                </div>
            @else
                @foreach ($pendingRequests as $req)
                    <div class="request-card">
                        <div class="request-user">
                            <img src="{{ $req->sender->profile_image }}" alt="avatar" class="request-avatar">
                            <div class="request-info">
                                <span class="request-name">{{ $req->sender->name }}</span>
                                <span class="request-role">{{ str_replace('_', ' ', $req->sender->role) }}</span>
                            </div>
                        </div>
                        
                        <div class="request-actions">
                            <form action="{{ route('connect.accept', ['connectionId' => $req->id]) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="req-btn req-btn-accept" style="width: 100%;">Accept</button>
                            </form>
                            
                            <form action="{{ route('connect.decline', ['connectionId' => $req->id]) }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="req-btn req-btn-decline" style="width: 100%;">Decline</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
