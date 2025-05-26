<!DOCTYPE html>
<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\layouts\app.blade.php -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/tabicon.png') }}">
    <title>{{ config('app.name', 'ClipRush') }} - @yield('title', 'Video Downloader')</title>

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
            --primary: #2B7EC1;
            --primary-gradient-start: #2B7EC1;
            --primary-gradient-end: #58A7E6;
            --secondary: #0B2B42;
            --light: #f0f9ff;
            --dark: #0f172a;
            --success: #25b865;
            --danger: #ff2c55;
            --warning: #ffca28;
            --info: #56c5ed;
            --shadow-color: rgba(0, 0, 0, 0.25);
        }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background-color: #f5f5f5;
            color: var(--secondary);
            position: relative;
            min-height: 100vh;
        }

        /* Neobrutalism Elements */
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
        }

        .neo-card .card-header {
            background: linear-gradient(45deg, var(--primary-gradient-start), var(--primary-gradient-end));
            color: white;
            font-weight: bold;
            border-bottom: 3px solid var(--secondary);
        }

        .neo-navbar {
            background: white;
            border-bottom: 3px solid var(--secondary);
            box-shadow: 0 4px 0 var(--shadow-color);
        }

        .neo-navbar .nav-link {
            font-weight: 700;
        }

        .navbar-brand img {
            height: 40px;
        }

        /* Sidebar */
        .neo-sidebar {
            background: white;
            border-right: 3px solid var(--secondary);
            box-shadow: 4px 0 0 var(--shadow-color);
            height: 100%;
        }

        /* Footer */
        .neo-footer {
            background: var(--light);
            border-top: 3px solid var(--secondary);
            padding: 20px 0;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        /* Form Controls */
        .neo-form-control {
            border: 3px solid var(--secondary);
            border-radius: 8px;
            padding: 10px 15px;
            box-shadow: 4px 4px 0 var(--shadow-color);
            transition: all 0.2s ease;
        }

        .neo-form-control:focus {
            box-shadow: 2px 2px 0 var(--shadow-color);
            transform: translate(2px, 2px);
        }

        .main-content {
            padding-bottom: 80px; /* Footer height */
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
    <div id="app">
        @include('layouts.navigation')

        <main class="main-content py-4">
            <div class="container">
                @include('components.alerts')
                @yield('content')
            </div>
        </main>

        <!-- <footer class="neo-footer mt-10">
            <div class="container text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'ClipRush') }} | All Rights Reserved</p>
                <div class="mt-2">
                    <a href="{{ route('about') }}" class="text-decoration-none me-3">About</a>
                    <a href="#" class="text-decoration-none me-3">Terms</a>
                    <a href="#" class="text-decoration-none me-3">Privacy</a>
                    <a href="#" class="text-decoration-none">Contact</a>
                </div>
            </div>
        </footer>  -->
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
