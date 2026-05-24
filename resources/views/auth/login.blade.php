@extends('layouts.app')

@section('title', 'Sign In - EntreConnect')

@section('styles')
<style>
    .auth-card {
        max-width: 450px;
        margin: 4rem auto;
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

    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .remember-me input {
        cursor: pointer;
        accent-color: #6366f1;
    }

    .forgot-pass {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .forgot-pass:hover {
        text-decoration: underline;
    }

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
</style>
@endsection

@section('content')
<div class="glass-panel auth-card">
    <div>
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Reconnect with your startup ecosystem</p>
    </div>

    @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
        </div>

        <div class="form-options">
            <label class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                Remember me
            </label>
            <a href="#" class="forgot-pass">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In to Platform</button>
    </form>

    <p class="auth-footer">
        New to EntreConnect? <a href="{{ route('register') }}">Join the Ecosystem</a>
    </p>
</div>
@endsection
