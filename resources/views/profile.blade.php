@extends('layouts.app')

@section('title', 'My Profile - EntreConnect')

@section('styles')
<style>
    .profile-card {
        max-width: 800px;
        margin: 2rem auto;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 2rem;
    }

    .profile-avatar-big {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
    }

    .profile-meta-header {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .profile-name-big {
        font-family: var(--font-outfit);
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .profile-role-badge {
        align-self: start;
        font-size: 0.8rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input, .form-textarea, .form-select {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-family: var(--font-inter);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1.25rem;
        padding-right: 2.5rem;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-input:focus, .form-textarea:focus, .form-select:focus {
        outline: none;
        border-color: #6366f1;
        background: rgba(255, 255, 255, 0.06);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    .form-helper {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.2rem;
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

    .error-box {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
        .profile-card {
            padding: 1.5rem;
        }
        .profile-header {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        .profile-role-badge {
            align-self: center;
        }
    }
</style>
@endsection

@section('content')
<div class="glass-panel profile-card">
    <div class="profile-header">
        <img src="{{ $user->profile_image }}" alt="avatar" class="profile-avatar-big">
        <div class="profile-meta-header">
            <h1 class="profile-name-big">{{ $user->name }}</h1>
            <span class="role-badge role-{{ $user->role }} profile-role-badge">
                {{ str_replace('_', ' ', $user->role) }}
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Professional Title</label>
                <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $user->title) }}" placeholder="e.g. Co-founder & CTO">
            </div>

            <div class="form-group">
                <label for="company" class="form-label">Company / Startup Name</label>
                <input type="text" name="company" id="company" class="form-input" value="{{ old('company', $user->company) }}" placeholder="e.g. Stripe / Stealth Tech">
            </div>

            <div class="form-group">
                <label for="industry" class="form-label">Industry Sector</label>
                <input type="text" name="industry" id="industry" class="form-input" value="{{ old('industry', $user->industry) }}" placeholder="e.g. SaaS, Fintech, AI">
            </div>

            <!-- Role-specific fields -->
            @if ($user->role === 'founder' || $user->role === 'co_founder')
                <div class="form-group">
                    <label for="stage" class="form-label">Startup Stage</label>
                    <select name="stage" id="stage" class="form-select">
                        <option value="Idea Stage" {{ old('stage', $user->stage) === 'Idea Stage' ? 'selected' : '' }}>Idea Stage</option>
                        <option value="Pre-MVP" {{ old('stage', $user->stage) === 'Pre-MVP' ? 'selected' : '' }}>Pre-MVP / Development</option>
                        <option value="MVP Released" {{ old('stage', $user->stage) === 'MVP Released' ? 'selected' : '' }}>MVP Released</option>
                        <option value="Seed / Generating Revenue" {{ old('stage', $user->stage) === 'Seed / Generating Revenue' ? 'selected' : '' }}>Seed / Generating Revenue</option>
                        <option value="Scaling / Series A+" {{ old('stage', $user->stage) === 'Scaling / Series A+' ? 'selected' : '' }}>Scaling / Series A+</option>
                    </select>
                </div>
            @elseif ($user->role === 'investor')
                <div class="form-group">
                    <label for="ticket_size" class="form-label">Average Ticket Size</label>
                    <select name="ticket_size" id="ticket_size" class="form-select">
                        <option value="$10k - $50k" {{ old('ticket_size', $user->ticket_size) === '$10k - $50k' ? 'selected' : '' }}>$10k - $50k (Angel)</option>
                        <option value="$50k - $250k" {{ old('ticket_size', $user->ticket_size) === '$50k - $250k' ? 'selected' : '' }}>$50k - $250k (Seed)</option>
                        <option value="$250k - $1M" {{ old('ticket_size', $user->ticket_size) === '$250k - $1M' ? 'selected' : '' }}>$250k - $1M (Early VC)</option>
                        <option value="$1M+" {{ old('ticket_size', $user->ticket_size) === '$1M+' ? 'selected' : '' }}>$1M+ (Growth VC)</option>
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label for="linkedin" class="form-label">LinkedIn Profile URL</label>
                <input type="url" name="linkedin" id="linkedin" class="form-input" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/username">
            </div>

            <div class="form-group form-group-full">
                <label for="profile_image" class="form-label">Profile Avatar URL</label>
                <input type="url" name="profile_image" id="profile_image" class="form-input" value="{{ old('profile_image', $user->profile_image) }}" placeholder="https://image-link.com/avatar.jpg">
                <span class="form-helper">Use any image URL or keep default custom avatar seed.</span>
            </div>

            <div class="form-group form-group-full">
                <label for="bio" class="form-label">Profile Biography</label>
                <textarea name="bio" id="bio" class="form-textarea" placeholder="Tell the ecosystem what you're building, searching for, or offering...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div class="form-group form-group-full">
                <label for="skills" class="form-label">Skills & Interests (Comma Separated)</label>
                <input type="text" name="skills" id="skills" class="form-input" value="{{ old('skills', $user->skills) }}" placeholder="e.g. React, Python, Fundraising, Growth Marketing">
                <span class="form-helper">Enter items separated by commas to display beautiful tag pills on your profile card.</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top: 2rem; width: 100%;">Save Profile Portfolio</button>
    </form>
</div>
@endsection
