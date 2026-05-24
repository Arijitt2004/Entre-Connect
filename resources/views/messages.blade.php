@extends('layouts.app')

@section('title', 'Direct Messages - EntreConnect')

@section('styles')
<style>
    .chat-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: calc(100vh - 180px);
        min-height: 500px;
        gap: 1.5rem;
    }

    /* Threads Sidebar */
    .threads-sidebar {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .threads-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        font-family: var(--font-outfit);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .threads-list {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .thread-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.02);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .thread-item:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .thread-item.active {
        background: rgba(99, 102, 241, 0.08);
        border-left: 3px solid #6366f1;
    }

    .thread-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-color);
    }

    .thread-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
        overflow: hidden;
    }

    .thread-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .thread-role {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        align-self: start;
    }

    /* Chat Pane */
    .chat-pane {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .chat-user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid var(--border-color);
    }

    .chat-user-details {
        display: flex;
        flex-direction: column;
    }

    .chat-user-name {
        font-family: var(--font-outfit);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .chat-user-title {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .chat-history {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        background: rgba(0, 0, 0, 0.15);
    }

    /* Bubbles */
    .msg-wrap {
        display: flex;
        flex-direction: column;
        max-width: 70%;
    }

    .msg-incoming {
        align-self: flex-start;
    }

    .msg-outgoing {
        align-self: flex-end;
    }

    .msg-bubble {
        padding: 0.85rem 1.15rem;
        border-radius: 14px;
        font-size: 0.925rem;
        line-height: 1.4;
    }

    .msg-incoming .msg-bubble {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        border-top-left-radius: 4px;
    }

    .msg-outgoing .msg-bubble {
        background: var(--gradient-primary);
        color: #ffffff;
        border-top-right-radius: 4px;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.15);
    }

    .msg-time {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
        margin-left: 0.25rem;
    }

    .msg-outgoing .msg-time {
        align-self: flex-end;
        margin-right: 0.25rem;
    }

    /* Message Input */
    .chat-input-area {
        padding: 1.25rem;
        border-top: 1px solid var(--border-color);
        background: rgba(11, 15, 25, 0.4);
    }

    .chat-form {
        display: flex;
        gap: 0.75rem;
    }

    .chat-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        font-family: var(--font-inter);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .chat-input:focus {
        outline: none;
        border-color: #6366f1;
        background: rgba(255, 255, 255, 0.06);
    }

    .chat-send-btn {
        background: var(--gradient-primary);
        color: #ffffff;
        border: none;
        padding: 0 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-family: var(--font-outfit);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-send-btn:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* Empty states */
    .chat-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
        gap: 1rem;
    }

    .chat-empty-icon {
        font-size: 3rem;
    }

    .chat-empty-title {
        font-family: var(--font-outfit);
        font-size: 1.4rem;
        font-weight: 700;
    }

    .chat-empty-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        max-width: 400px;
    }

    @media (max-width: 768px) {
        .chat-layout {
            grid-template-columns: 1fr;
        }
        .threads-sidebar {
            display: none;
        }
        .threads-sidebar.show-mobile {
            display: flex;
        }
    }
</style>
@endsection

@section('content')
<div class="chat-layout">
    <!-- Active Threads Sidebar -->
    <div class="glass-panel threads-sidebar">
        <div class="threads-header">Connected Members</div>
        <div class="threads-list">
            @if (count($connectedUsers) === 0)
                <div class="empty-state" style="padding: 3rem 1rem;">
                    No matches established yet.<br>Connect with members on the Feed!
                </div>
            @else
                @foreach ($connectedUsers as $u)
                    <a href="{{ route('messages.thread', ['userId' => $u->id]) }}" class="thread-item {{ ($activeUser && $activeUser->id === $u->id) ? 'active' : '' }}">
                        <img src="{{ $u->profile_image }}" alt="avatar" class="thread-avatar">
                        <div class="thread-info">
                            <span class="thread-name">{{ $u->name }}</span>
                            <span class="thread-role" style="color: var(--accent-{{ $u->role }})">
                                {{ str_replace('_', ' ', $u->role) }}
                            </span>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Active Chat Pane -->
    <div class="glass-panel chat-pane">
        @if (!$activeUser)
            <!-- Chat Empty State -->
            <div class="chat-empty">
                <span class="chat-empty-icon">💬</span>
                <h3 class="chat-empty-title">Ecosystem Chat</h3>
                <p class="chat-empty-desc">
                    Establish accepted connections with Founders, Co-founders, Investors, or Mentors in the Discovery Feed to unlock direct messaging.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary" style="margin-top: 1rem;">Discover Ecosystem</a>
            </div>
        @else
            <!-- Chat Header -->
            <div class="chat-header">
                <img src="{{ $activeUser->profile_image }}" alt="avatar" class="chat-user-avatar">
                <div class="chat-user-details">
                    <span class="chat-user-name">{{ $activeUser->name }}</span>
                    <span class="chat-user-title">{{ $activeUser->title }}</span>
                </div>
            </div>

            <!-- Chat History -->
            <div class="chat-history" id="chat-history">
                @if (count($messages) === 0)
                    <div class="chat-empty" style="padding: 2rem 0;">
                        <span class="chat-empty-icon" style="font-size: 2rem;">🤝</span>
                        <h4 class="chat-empty-title" style="font-size: 1.1rem;">Connection Unlocked!</h4>
                        <p class="chat-empty-desc" style="font-size: 0.85rem;">
                            You are now connected with {{ $activeUser->name }}. Send the first message to pitch your idea or introduce yourself!
                        </p>
                    </div>
                @else
                    @foreach ($messages as $msg)
                        @php
                            $isOutgoing = ($msg->sender_id === Auth::id());
                        @endphp
                        <div class="msg-wrap {{ $isOutgoing ? 'msg-outgoing' : 'msg-incoming' }}">
                            <div class="msg-bubble">
                                {{ $msg->message }}
                            </div>
                            <span class="msg-time">
                                {{ $msg->created_at ? $msg->created_at->format('H:i') : now()->format('H:i') }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Chat Input Area -->
            <div class="chat-input-area">
                <form action="{{ route('messages.send') }}" method="POST" class="chat-form">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $activeUser->id }}">
                    <input type="text" name="message" class="chat-input" placeholder="Type a message, ask for a pitch deck or schedule a call..." required autocomplete="off">
                    <button type="submit" class="chat-send-btn">Send</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-scroll chat history to the bottom
        const chatHistory = document.getElementById('chat-history');
        if (chatHistory) {
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }
    });
</script>
@endsection
