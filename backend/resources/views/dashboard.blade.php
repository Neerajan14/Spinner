<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 20px;
        }

        h1 {
            font-size: 30px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 32px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 24px;
        }

        .stat-card-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: bold;
            color: #1f2937;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 32px;
            height: 32px;
        }

        .stat-icon.blue {
            background-color: #dbeafe;
        }

        .stat-icon.blue svg {
            color: #2563eb;
        }

        .stat-icon.green {
            background-color: #d1fae5;
        }

        .stat-icon.green svg {
            color: #059669;
        }

        .stat-icon.yellow {
            background-color: #fef3c7;
        }

        .stat-icon.yellow svg {
            color: #d97706;
        }

        .stat-link {
            display: inline-block;
            margin-top: 16px;
            color: #2563eb;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .stat-link:hover {
            color: #1e40af;
        }

        .stat-link.green {
            color: #059669;
        }

        .stat-link.green:hover {
            color: #047857;
        }

        /* Quick Links Grid */
        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .link-card {
            display: block;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 24px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .link-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .link-card h3 {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .link-card p {
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .stats-grid,
            .links-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 24px;
            }

            .container {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container">
        <h1>Dashboard Overview</h1>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <p class="stat-label">Total Users</p>
                        <p class="stat-value">{{ $totalUsers }}</p>
                    </div>
                    <div class="stat-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('users') }}" class="stat-link">
                    View All Users →
                </a>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <p class="stat-label">Total Wins</p>
                        <p class="stat-value">{{ $totalWins }}</p>
                    </div>
                    <div class="stat-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('user-wins') }}" class="stat-link green">
                    View All Wins →
                </a>
            </div>

            <div class="stat-card">
                <div class="stat-card-content">
                    <div class="stat-info">
                        <p class="stat-label">Total Prize Value</p>
                        <p class="stat-value">${{ number_format($totalPrizeValue, 2) }}</p>
                    </div>
                    <div class="stat-icon yellow">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="links-grid">
            <a href="{{ route('users') }}" class="link-card">
                <h3>Manage Users</h3>
                <p>View and manage all registered users</p>
            </a>

            <a href="{{ route('user-wins') }}" class="link-card">
                <h3>View Winners</h3>
                <p>See all prize winners and their details</p>
            </a>
        </div>
    </div>
</body>
</html>