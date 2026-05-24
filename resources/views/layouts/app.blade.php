<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EntreConnect - Ecosystem')</title>
    
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Premium Styles -->
    <style>
        :root {
            --bg-color: #0b0f19;
            --surface-color: rgba(22, 30, 49, 0.6);
            --surface-hover: rgba(30, 41, 67, 0.85);
            --border-color: rgba(255, 255, 255, 0.08);
            
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            
            --accent-founder: #818cf8;
            --accent-investor: #34d399;
            --accent-mentor: #fb923c;
            --accent-cofounder: #2dd4bf;
            --accent-entrepreneur: #f472b6;
            
            --gradient-primary: linear-gradient(135deg, #6366f1, #38bdf8);
            --font-outfit: 'Outfit', sans-serif;
            --font-inter: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(56, 189, 248, 0.08) 0%, transparent 40%);
            color: var(--text-primary);
            font-family: var(--font-inter);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            line-height: 1.5;
        }

        /* Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Glass Panel Helper */
        .glass-panel {
            background: var(--surface-color);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }

        /* Header / Navbar */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            width: 100%;
            border-bottom: 1px solid var(--border-color);
            background: rgba(11, 15, 25, 0.8);
            backdrop-filter: blur(12px);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            font-family: var(--font-outfit);
            font-size: 1.5rem;
            font-weight: 800;
            text-decoration: none;
            background: linear-gradient(135deg, #818cf8, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo span {
            background: linear-gradient(135deg, #ffffff, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-item {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .role-badge {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.25rem 0.6rem;
            border-radius: 9999px;
            letter-spacing: 0.05em;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .role-founder { background: rgba(129, 140, 248, 0.15); color: var(--accent-founder); border-color: rgba(129, 140, 248, 0.3); }
        .role-investor { background: rgba(52, 211, 153, 0.15); color: var(--accent-investor); border-color: rgba(52, 211, 153, 0.3); }
        .role-mentor { background: rgba(251, 146, 60, 0.15); color: var(--accent-mentor); border-color: rgba(251, 146, 60, 0.3); }
        .role-cofounder { background: rgba(45, 212, 191, 0.15); color: var(--accent-cofounder); border-color: rgba(45, 212, 191, 0.3); }
        .role-entrepreneur { background: rgba(244, 114, 182, 0.15); color: var(--accent-entrepreneur); border-color: rgba(244, 114, 182, 0.3); }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .logout-btn {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        /* Container & Main Layout */
        main {
            flex: 1;
            width: 100%;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        footer {
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            background: rgba(11, 15, 25, 0.5);
            backdrop-filter: blur(8px);
        }

        /* Buttons & Forms globally */
        .btn {
            font-family: var(--font-outfit);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Flex wrap and grid responsiveness */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            main {
                padding: 0 1rem;
                margin: 1rem auto;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="{{ route('dashboard') }}" class="logo">
                Entre<span>Connect</span>
            </a>
            
            @auth
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">Discover</a>
                <a href="{{ route('messages') }}" class="nav-item {{ Route::is('messages') ? 'active' : '' }}">Messages</a>
                <a href="{{ route('profile') }}" class="nav-item {{ Route::is('profile') ? 'active' : '' }}">My Profile</a>
            </div>
            
            <div class="user-menu">
                <span class="role-badge role-{{ Auth::user()->role }}">
                    {{ str_replace('_', ' ', Auth::user()->role) }}
                </span>
                <img src="{{ Auth::user()->profile_image }}" alt="avatar" class="avatar">
                
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Log Out</button>
                </form>
            </div>
            @else
            <div class="nav-links">
                <a href="{{ route('login') }}" class="nav-item {{ Route::is('login') ? 'active' : '' }}">Sign In</a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.9rem;">Join Ecosystem</a>
            </div>
            @endauth
        </div>
    </header>

    <main class="animate-fade-in">
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} EntreConnect Ecosystem. Connect. Collaborate. Build.</p>
    </footer>

    @yield('scripts')
</body>
</html>
