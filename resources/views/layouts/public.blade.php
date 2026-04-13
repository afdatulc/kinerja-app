<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'KinerjaApp') }} - Pelaporan Kinerja</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <style>
        :root {
            --primary-accent: #6b1424;
            --primary-hover: #4e0d2e;
            --bg-light: #f8f9fa;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-light);
            color: #2d3436;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        .navbar {
            background: #fff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-accent) !important;
            display: flex;
            align-items: center;
        }
        
        .btn-accent {
            background-color: var(--primary-accent);
            color: #fff;
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-accent:hover {
            background-color: var(--primary-hover);
            color: #fff;
            transform: translateY(-2px);
        }
        
        .btn-outline-accent {
            border: 2px solid var(--primary-accent);
            color: var(--primary-accent);
            border-radius: 10px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-outline-accent:hover {
            background-color: var(--primary-accent);
            color: #fff;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            transition: transform 0.3s;
        }
        
        .form-select, .form-control {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-accent);
            box-shadow: 0 0 0 0.25rem rgba(107, 20, 36, 0.1);
        }
        
        footer {
            background: transparent;
            padding: 1.5rem 0;
            margin-top: 2rem;
            border-top: 1px solid rgba(0,0,0,0.05);
        }

        .footer-content {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
    @yield('styles')
</head>
<body>

    <nav class="navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-layer-group me-2"></i> KinerjaApp
            </a>
            <div class="ms-auto">
                <a href="{{ route('login') }}" class="btn btn-outline-accent btn-sm">
                    <i class="fas fa-user-shield me-2"></i> Admin Login
                </a>
            </div>
        </div>
    </nav>

    <main class="py-5">
        @yield('content')
    </main>

    <footer>
        <div class="container text-center">
            <div class="footer-content">
                <p class="mb-0 text-muted small">&copy; {{ date('Y') }} KinerjaApp - Monitoring Kinerja Triwulanan</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @yield('scripts')
</body>
</html>
