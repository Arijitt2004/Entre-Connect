@extends('layouts.app')

@section('title', 'Join Ecosystem - EntreConnect')

@section('styles')
<style>
    .auth-card {
        max-width: 650px;
        margin: 2rem auto;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .auth-title {
        font-family: var(--font-outfit);
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #ffffff, #9ca3af);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-subtitle {
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-align: center;
        margin-top: -1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-family: var(--font-inter);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #6366f1;
        background: rgba(255, 255, 255, 0.06);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    /* Role Interactive Selection Grid */
    .role-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .role-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem 1rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .role-card:hover {
        transform: translateY(-4px);
        background: rgba(255, 255, 255, 0.05);
    }

    .role-icon {
        font-size: 1.5rem;
        filter: grayscale(0.2);
    }

    .role-name {
        font-family: var(--font-outfit);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--text-secondary);
    }

    /* Selected states with roles */
    .role-card.selected[data-role="founder"] { border-color: var(--accent-founder); box-shadow: 0 0 15px rgba(129, 140, 248, 0.2); background: rgba(129, 140, 248, 0.05); }
    .role-card.selected[data-role="founder"] .role-name { color: var(--accent-founder); }
    
    .role-card.selected[data-role="co_founder"] { border-color: var(--accent-cofounder); box-shadow: 0 0 15px rgba(45, 212, 191, 0.2); background: rgba(45, 212, 191, 0.05); }
    .role-card.selected[data-role="co_founder"] .role-name { color: var(--accent-cofounder); }

    .role-card.selected[data-role="investor"] { border-color: var(--accent-investor); box-shadow: 0 0 15px rgba(52, 211, 153, 0.2); background: rgba(52, 211, 153, 0.05); }
    .role-card.selected[data-role="investor"] .role-name { color: var(--accent-investor); }

    .role-card.selected[data-role="mentor"] { border-color: var(--accent-mentor); box-shadow: 0 0 15px rgba(251, 146, 60, 0.2); background: rgba(251, 146, 60, 0.05); }
    .role-card.selected[data-role="mentor"] .role-name { color: var(--accent-mentor); }

    .role-card.selected[data-role="entrepreneur"] { border-color: var(--accent-entrepreneur); box-shadow: 0 0 15px rgba(244, 114, 182, 0.2); background: rgba(244, 114, 182, 0.05); }
    .role-card.selected[data-role="entrepreneur"] .role-name { color: var(--accent-entrepreneur); }

    .error-box {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #fca5a5;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .auth-footer {
        text-align: center;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .auth-footer a {
        color: #2dd4bf;
        text-decoration: none;
        font-weight: 600;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .auth-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="glass-panel auth-card">
    <div>
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Step into a unified digital ecosystem</p>
    </div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        
        <input type="hidden" name="role" id="role-input" value="founder">

        <div class="form-group">
            <label class="form-label">Select Your Ecosystem Role</label>
            <div class="role-grid">
                <div class="role-card selected" data-role="founder">
                    <span class="role-icon">💡</span>
                    <span class="role-name">Founder</span>
                </div>
                <div class="role-card" data-role="co_founder">
                    <span class="role-icon">🤝</span>
                    <span class="role-name">Co-Founder</span>
                </div>
                <div class="role-card" data-role="investor">
                    <span class="role-icon">💎</span>
                    <span class="role-name">Investor</span>
                </div>
                <div class="role-card" data-role="mentor">
                    <span class="role-icon">🧠</span>
                    <span class="role-name">Mentor</span>
                </div>
                <div class="role-card" data-role="entrepreneur">
                    <span class="role-icon">🚀</span>
                    <span class="role-name">Entrepreneur</span>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" placeholder="john@example.com" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">Join the Ecosystem</button>
    </form>

    <p class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign In</a>
    </p>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.role-card');
        const roleInput = document.getElementById('role-input');

        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove selected class from all cards
                cards.forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                card.classList.add('selected');
                
                // Update hidden input
                const selectedRole = card.getAttribute('data-role');
                roleInput.value = selectedRole;
            });
        });
    });
</script>
@endsection
