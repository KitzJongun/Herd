<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Control Center')</title>
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #010409; /* Deep space dark */
            color: #e5e5e5;
        }
        .font-display {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        /* Neon Blue Accents */
        .neon-red-text {
            color: #38bdf8; /* Vibrant blue */
            text-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
        }
        .bg-neon-red {
            background-color: #38bdf8;
        }
        .neon-red-glow {
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4), 0 0 30px rgba(56, 189, 248, 0.2);
        }
        .neon-red-glow-subtle {
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.15);
        }
        
        /* Glassmorphism containers */
        .glass-panel {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Custom scrollbar for dark mode */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f0f0f;
        }
        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #38bdf8;
        }
    </style>
</head>
<body class="antialiased min-h-screen selection:bg-[#38bdf8]/30 selection:text-white flex flex-col items-center">
    
    <!-- Background atmospheric glow effects -->
    <div class="fixed top-0 left-0 w-full h-100vh z-[-1] overflow-hidden flex items-center justify-center bg-[#010409]" style="background: radial-gradient(circle at 50% 60%, #0c1a35 0%, #010409 60%); width: 100vw; height: 100vh;">
        <div class="absolute rounded-full border border-[rgba(125,211,252,0.02)]" style="width: 100vw; height: 100vw;"></div>
        <div class="absolute rounded-full border border-[rgba(125,211,252,0.05)] shadow-[0_0_150px_rgba(125,211,252,0.1),inset_0_0_100px_rgba(125,211,252,0.05)]" style="width: 70vw; height: 70vw;"></div>
        <div class="absolute rounded-full border border-[rgba(125,211,252,0.05)] shadow-[0_0_120px_rgba(56,189,248,0.15),inset_0_0_120px_rgba(56,189,248,0.1)]" style="width: 40vw; height: 40vw;"></div>
        <div class="absolute w-[20vw] h-[20vw] bg-sky-500/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- Floating Navbar Admin -->
    <div class="w-full max-w-7xl mx-auto px-4 pt-6 fixed top-0 z-50 flex justify-center">
        <header class="glass-panel w-full px-6 py-3 rounded-full flex items-center justify-between shadow-[0_20px_40px_rgba(0,0,0,0.4)]">
            <!-- Logo -->
            <div class="flex items-center space-x-2 cursor-pointer">
                <i class='bx bxl-sketch text-[#38bdf8] text-2xl'></i>
                <h1 class="font-bold text-[15px] tracking-wide text-white">HELPDESK<span class="text-[#38bdf8]">AI</span></h1>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-[13px] font-medium text-slate-400">
                <a href="#" class="text-white flex items-center"><i class='bx bxs-dashboard mr-1'></i> Dashboard</a>
                <a href="#" class="hover:text-white transition-colors flex items-center">
                    <i class='bx bx-message-square-error mr-1'></i> Inbox 
                    <span class="ml-1 px-1.5 py-0.5 rounded text-[9px] bg-[#38bdf8]/20 text-[#38bdf8]">12</span>
                </a>
            </nav>

            <!-- User Area -->
            <div class="flex items-center space-x-6">
                <div class="hidden md:flex relative">
                    <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500'></i>
                    <input type="text" placeholder="Search ticket..." class="bg-[#111] border border-[#333] rounded-full pl-9 pr-4 py-1.5 text-xs text-white focus:outline-none focus:border-[#38bdf8]/50 transition w-48">
                </div>
                
                <div class="flex items-center space-x-3 border-l border-[#333] pl-4">
                    <p class="text-[11px] font-bold text-white hidden md:block">{{ Auth::user()->name ?? 'Admin Utama' }}</p>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-[10px] text-gray-500 hover:text-rose-400 font-bold tracking-widest uppercase transition-colors">Keluar</button>
                    </form>
                </div>
            </div>
        </header>
    </div>

    <!-- Main Content -->
    <main class="w-full max-w-7xl relative z-10 pt-32 pb-20 px-4">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
