@extends('layouts.app')

@section('title', 'EntreConnect - Where Startups Are Born')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        padding: 4rem 1rem;
        overflow: hidden;
    }

    /* Animated Background Orbs */
    .hero-section::before,
    .hero-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        filter: blur(80px);
        z-index: -1;
        animation: float 10s infinite ease-in-out alternate;
    }

    .hero-section::before {
        background: rgba(99, 102, 241, 0.2);
        top: 10%;
        left: 20%;
    }

    .hero-section::after {
        background: rgba(56, 189, 248, 0.2);
        bottom: 10%;
        right: 20%;
        animation-delay: -5s;
    }

    [data-theme="light"] .hero-section::before { background: rgba(99, 102, 241, 0.15); }
    [data-theme="light"] .hero-section::after { background: rgba(56, 189, 248, 0.15); }

    @keyframes float {
        0% { transform: translate(0, 0) scale(1); }
        100% { transform: translate(30px, 50px) scale(1.1); }
    }

    .hero-title {
        font-family: var(--font-outfit);
        font-size: clamp(3rem, 6vw, 5.5rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #ffffff, #a5b4fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    [data-theme="light"] .hero-title {
        background: linear-gradient(135deg, #111827, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-title span {
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: clamp(1.1rem, 2vw, 1.3rem);
        color: var(--text-secondary);
        max-width: 650px;
        margin: 0 auto 2.5rem;
        line-height: 1.6;
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        animation-delay: 0.1s;
        opacity: 0;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        animation-delay: 0.2s;
        opacity: 0;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .btn-hero {
        padding: 1rem 2rem;
        font-size: 1.1rem;
    }

    /* Features Section */
    .features-section {
        padding: 5rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .section-title {
        text-align: center;
        font-family: var(--font-outfit);
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 3rem;
        color: var(--text-primary);
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        padding: 2rem;
        border-radius: 16px;
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        backdrop-filter: blur(12px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    [data-theme="light"] .feature-card:hover {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: rgba(99, 102, 241, 0.1);
        color: #818cf8;
    }

    [data-theme="light"] .feature-icon {
        background: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
    }

    .feature-card h3 {
        font-family: var(--font-outfit);
        font-size: 1.4rem;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .feature-card p {
        color: var(--text-secondary);
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* CTA Section */
    .cta-section {
        padding: 6rem 1rem;
        text-align: center;
        background: linear-gradient(to top, rgba(99, 102, 241, 0.05), transparent);
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 2rem;
        border-radius: 24px;
    }

    .cta-section h2 {
        font-family: var(--font-outfit);
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .hero-buttons {
            flex-direction: column;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">Where Startups<br>Are <span>Born</span>.</h1>
        <p class="hero-subtitle">
            The exclusive ecosystem connecting ambitious Founders, visionary Investors, and industry-leading Mentors to build the next generation of great companies.
        </p>
        <div class="hero-buttons">
            <a href="{{ route('register') }}" class="btn btn-primary btn-hero">Join the Ecosystem</a>
            <a href="{{ route('login') }}" class="btn btn-secondary btn-hero">Sign In</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2 class="section-title">Everything you need to scale</h2>
        <div class="features-grid">
            
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3>Find Co-Founders</h3>
                <p>Discover talented engineers, marketers, and operators who share your vision and are ready to build.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Meet Investors</h3>
                <p>Post updates to the Pitch Board and connect directly with Angel Investors and Venture Capitalists.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h3>Get Mentorship</h3>
                <p>Connect with industry veterans who have successfully exited startups and can guide your journey.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3>Direct Messaging</h3>
                <p>Cut through the noise. Send direct messages to people in the ecosystem without paywalls.</p>
            </div>

        </div>
    </section>

    <!-- Final CTA -->
    <section class="cta-section">
        <h2>Ready to build the future?</h2>
        <p class="hero-subtitle" style="margin-bottom: 2rem;">Join hundreds of founders and investors already collaborating on EntreConnect.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-hero">Create Free Profile</a>
    </section>
@endsection
