<!DOCTYPE html>
<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\layouts\admin.blade.php -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'ClipRush') }} | @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Neobrutalism Style -->
    <style>
        :root {
            --primary: #ff4b2b;
            --primary-gradient-start: #ff4b2b;
            --primary-gradient-end: #ff9a55;
            --secondary: #121212;
            --light: #f8f9fa;
            --light-gray: #e9ecef;
            --dark: #212529;
            --success: #25b865;
            --danger: #ff2c55;
            --warning: #ffca28;
            --info: #56c5ed;
            --shadow-color: rgba(0, 0, 0, 0.35);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f5f5f5;
            color: var(--secondary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Admin specific styles */
        .admin-container {
            display: flex;
            flex: 1;
        }

        .admin-sidebar {
            width: 260px;
            background: white;
            border-right: 3px solid var(--secondary);
            box-shadow: 4px 0 0 var(--shadow-color);
            height: 100vh;
            position: fixed;
            overflow-y: auto;
        }

        .admin-sidebar .brand {
            padding: 15px 20px;
            background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            font-weight: bold;
            text-align: center;
            border-bottom: 3px solid var(--secondary);
        }

        .admin-sidebar .brand img {
            height: 40px;
        }

        .admin-sidebar .nav-item {
            border-bottom: 1px solid #eee;
        }

        .admin-sidebar .nav-link {
            padding: 12px 20px;
            color: var(--secondary);
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .admin-sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .admin-sidebar .nav-link:hover {
            background-color: var(--light-gray);
        }

        .admin-sidebar .nav-link.active {
            background-color: var(--light);
            color: var(--primary);
            border-left: 4px solid var(--primary);
        }

        .admin-content {
            margin-left: 260px;
            padding: 20px;
            flex: 1;
        }

        .admin-header {
            background: white;
            padding: 15px 20px;
            border-bottom: 3px solid var(--secondary);
            box-shadow: 0 4px 0 var(--shadow-color);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Neo Components */
        .neo-btn {
            background: var(--primary);
            color: white;
            border: 3px solid var(--secondary);
            border-radius: 8px;
            box-shadow: 6px 6px 0 var(--secondary);
            font-weight: 700;
            transition: all 0.2s ease;
            text-transform: uppercase;
            padding: 10px 20px;
        }

        .neo-btn:hover {
            transform: translate(3px, 3px);
            box-shadow: 3px 3px 0 var(--secondary);
        }

        .neo-btn:active {
            transform: translate(6px, 6px);
            box-shadow: none;
        }

        .neo-btn.btn-secondary {
            background: white;
            color: var(--secondary);
        }

        .neo-card {
            border: 3px solid var(--secondary);
            border-radius: 8px;
            box-shadow: 8px 8px 0 var(--shadow-color);
            background: white;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .neo-card .card-header {
            background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            font-weight: bold;
            border-bottom: 3px solid var(--secondary);
            padding: 12px 20px;
        }

        .neo-form-control {
            border: 3px solid var(--secondary);
            border-radius: 8px;
            padding: 10px 15px;
            box-shadow: 4px 4px 0 var(--shadow-color);
            transition: all 0.2s ease;
            width: 100%;
        }

        .neo-form-control:focus {
            box-shadow: 2px 2px 0 var(--shadow-color);
            transform: translate(2px, 2px);
            outline: none;
        }

        /* Stats Card */
        .stats-card {
            background: white;
            border: 3px solid var(--secondary);
            border-radius: 8px;
            box-shadow: 6px 6px 0 var(--shadow-color);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .stats-card:hover {
            transform: translate(3px, 3px);
            box-shadow: 3px 3px 0 var(--shadow-color);
        }

        .stats-card .stat-icon {
            font-size: 35px;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .stats-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-card .stat-label {
            color: #6c757d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .text-primary-gradient {
        background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        }


        .bg-primary-gradient {
            background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="brand">
                <h4 class="mb-0">{{ config('app.name', 'ClipRush') }} Admin</h4>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.downloads.index') }}" class="nav-link {{ request()->routeIs('admin.downloads.*') ? 'active' : '' }}">
                        <i class="fas fa-download"></i> Downloads
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.schedules.index') }}" class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Schedules
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tokens.index') }}" class="nav-link {{ request()->routeIs('admin.tokens.*') ? 'active' : '' }}">
                        <i class="fas fa-coins"></i> Tokens
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i> Activities
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.system.settings') }}" class="nav-link {{ request()->routeIs('admin.system.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i> System
                    </a>
                </li>
                <li class="nav-item mt-4">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fas fa-chevron-left"></i> Back to App
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>
        </aside>

        <div class="admin-content">
            <div class="admin-header">
                <h2 class="mb-0">@yield('title', 'Dashboard')</h2>
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <span>Welcome, <strong>{{ auth()->user()->name }}</strong></span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ff4b2b&color=fff" alt="{{ auth()->user()->name }}" class="rounded-circle me-2" width="32" height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="{{ route('account') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @include('components.alerts')
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (for some components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
