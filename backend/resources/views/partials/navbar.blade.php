<style>
    .navbar {
        background: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .navbar-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 64px;
    }

    .nav-links {
        display: flex;
        gap: 32px;
    }

    .nav-link {
        display: inline-flex;
        align-items: center;
        padding: 0 4px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        color: #6b7280;
        transition: all 0.2s;
    }

    .nav-link:hover {
        color: #374151;
    }

    .nav-link.active {
        border-bottom-color: #3b82f6;
        color: #1f2937;
    }

    .user-section {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .welcome-text {
        color: #374151;
        font-size: 14px;
    }

    .btn-logout {
        background: #ef4444;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-logout:hover {
        background: #dc2626;
    }

    @media (max-width: 768px) {
        .navbar-content {
            flex-direction: column;
            height: auto;
            padding: 16px 0;
            gap: 16px;
        }

        .nav-links {
            flex-direction: column;
            gap: 12px;
            width: 100%;
            text-align: center;
        }

        .user-section {
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .btn-logout {
            width: 100%;
        }
    }
</style>

<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-content">
            <div class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                    Users
                </a>
                <a href="{{ route('user-wins') }}" class="nav-link {{ request()->routeIs('user-wins') ? 'active' : '' }}">
                    User Wins
                </a>
            </div>

            <div class="user-section">
                <span class="welcome-text">
                    Welcome, <strong>{{ auth()->user()->name }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
