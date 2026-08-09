<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HELPDESK AI - @yield('title')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2563eb">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #0f172a;
            --accent: #3b82f6;
            --bg-color: #f8fafc;
            --text-main: #334155;
            --text-light: #64748b;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.3);
            --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Abstract shapes */
        .shape {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            border-radius: 50%;
            opacity: 0.6;
            animation: float 10s infinite alternate;
            pointer-events: none;
        }
        .shape-1 { top: -100px; left: -100px; width: 400px; height: 400px; background: rgba(59, 130, 246, 0.3); }
        .shape-2 { bottom: -100px; right: -100px; width: 500px; height: 500px; background: rgba(99, 102, 241, 0.2); animation-delay: -5s; }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        /* Header / Navbar */
        header {
            width: 100%;
            padding: clamp(1rem, 2vw, 1.5rem) 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo span { color: var(--primary); }

        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links auto {}

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-block;
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }

        .btn-admin {
            background: rgba(255,255,255,0.5);
            color: var(--secondary);
            border: 1px solid var(--glass-border);
            padding: 0.5rem 1.2rem;
            font-size: 0.9rem;
            border-radius: 50px;
        }
        .btn-admin:hover {
            background: rgba(255,255,255,0.8);
            border-color: var(--primary);
        }

        .btn-block-fluid {
            width: 100%;
            padding: clamp(0.8rem, 2vw, 1.2rem);
            font-size: clamp(1rem, 2vw, 1.1rem);
            margin-top: 1rem;
        }

        main {
            flex: 1;
            padding: clamp(1.5rem, 5vw, 3rem) 5%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: clamp(1.5rem, 5vw, 2.5rem);
            width: 95%;
            max-width: 800px;
            box-shadow: var(--shadow);
            animation: slideUp 0.6s ease-out;
            margin: 0 auto;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            margin-bottom: clamp(1.5rem, 4vw, 2rem);
            text-align: center;
        }
        .card-header h1 {
            font-size: clamp(1.5rem, 5vw, 2.2rem);
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }
        .card-header p {
            color: var(--text-light);
            font-size: clamp(0.9rem, 1.5vw, 1rem);
        }

        .form-group {
            margin-bottom: clamp(1rem, 3vw, 1.5rem);
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--secondary);
            font-size: clamp(0.85rem, 1.5vw, 0.95rem);
        }
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            transition: all 0.3s;
            color: var(--text-main);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
            background: var(--glass-bg);
            backdrop-filter: blur(5px);
        }

        .mb-4 { margin-bottom: 2rem; }
        .d-flex-mobile-column {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .narrow-card {
            max-width: 500px;
        }

        .mobile-menu-btn {
            display: none;
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 10px;
            cursor: pointer;
            padding: 10px;
            z-index: 101;
            transition: all 0.3s;
            color: var(--primary);
            line-height: 0;
        }
        .mobile-menu-btn:active {
            transform: scale(0.9);
            background: rgba(37, 99, 235, 0.2);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn { display: flex; align-items: center; justify-content: center; }
            .d-flex-mobile-column {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .mt-mobile-2 { margin-top: 1rem; }
            header { padding: 1rem 5%; }
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(15px);
                flex-direction: column;
                padding: 1.5rem;
                gap: 12px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                border-top: 1px solid #e2e8f0;
            }
            .nav-links.active { display: flex; }
            main { padding: 1.5rem 10px; }
            .glass-card {
                padding: 1.5rem 1.2rem;
                width: 100%;
                max-width: 100% !important;
                border-radius: 16px;
            }
            .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <header>
        <a href="/" class="logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            HELPDESK<span>AI</span>
        </a>
        
        <button class="mobile-menu-btn" onclick="toggleMenu()" aria-label="Menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        </button>

        <div class="nav-links" id="navLinks">
            <a href="/" class="btn btn-primary">Lapor Masalah</a>
            <a href="/lacak" class="btn btn-outline">Lacak Tiket</a>
            <a href="{{ route('admin.login') }}" class="btn btn-admin">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Admin
            </a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} HELPDESK AI. Diberdayakan oleh Asisten Cerdas.</p>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>

    @yield('scripts')
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('/sw.js');
        });
      }
    </script>
</body>
</html>
