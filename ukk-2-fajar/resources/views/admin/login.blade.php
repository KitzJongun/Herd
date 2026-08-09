<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HELPDESK AI</title>
    
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Vite Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #010409;
            color: #e5e5e5;
        }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .neon-red-text {
            color: #38bdf8;
            text-shadow: 0 0 10px rgba(56, 189, 248, 0.5);
        }
        .bg-neon-red { background-color: #38bdf8; }
        .neon-red-glow {
            box-shadow: 0 0 15px rgba(56, 189, 248, 0.4), 0 0 30px rgba(56, 189, 248, 0.2);
        }
        .glass-panel {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center relative overflow-hidden selection:bg-[#38bdf8] selection:text-white">
    
    <!-- Abstract Background Elements -->
    <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] bg-[#38bdf8]/10 rounded-full blur-[120px] pointer-events-none mix-blend-screen"></div>
    <div class="absolute bottom-[-10%] right-[-20%] w-[60%] h-[60%] bg-sky-900/10 rounded-full blur-[150px] pointer-events-none mix-blend-screen"></div>
    
    <div class="relative z-10 w-full max-w-md px-6">
        
        <!-- Logo Area -->
        <div class="text-center mb-10">
            <div class="w-16 h-16 mx-auto bg-neon-red rounded-2xl flex items-center justify-center neon-red-glow mb-6 rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class='bx bxl-sketch text-white text-4xl'></i>
            </div>
            <h1 class="font-display font-bold text-3xl tracking-tight text-white uppercase mb-2">HELPDESK<span class="text-[#38bdf8]">AI</span></h1>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Administrator Portal</p>
        </div>

        <!-- Login Form Panel -->
        <div class="glass-panel border border-[#38bdf8]/20 rounded-3xl p-8 shadow-[0_0_50px_rgba(56,189,248,0.05)] relative overflow-hidden group">
            
            <!-- Glow Accent Top Border -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#38bdf8] to-transparent opacity-50 group-hover:opacity-100 transition-opacity"></div>
            
            @if ($errors->any())
                <div class="bg-[#38bdf8]/10 border border-[#38bdf8]/30 text-[#38bdf8] px-4 py-3 rounded-xl mb-6 flex items-start">
                    <i class='bx bxs-error-circle text-lg mr-2 mt-0.5'></i>
                    <ul class="text-xs font-semibold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-500 mb-2">Identifier (Email)</label>
                    <div class="relative">
                        <i class='bx bx-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-[#111] border border-[#222] focus:border-[#38bdf8] text-white text-sm rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-1 focus:ring-[#38bdf8]/50 transition-all shadow-inner" placeholder="admin@localhost">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-500">Security Key (Password)</label>
                        {{-- <a href="#" tabindex="-1" class="text-[10px] font-bold text-[#38bdf8] hover:text-white transition">Forgot Key?</a> (Requirement said no reset password functional requirement, just layout if any but skipped for focus) --}}
                    </div>
                    <div class="relative">
                        <i class='bx bx-lock-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        <input type="password" name="password" required class="w-full bg-[#111] border border-[#222] focus:border-[#38bdf8] text-white text-sm rounded-xl pl-11 pr-4 py-3.5 focus:outline-none focus:ring-1 focus:ring-[#38bdf8]/50 transition-all shadow-inner" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-[#111] border focus:ring-0 text-[#38bdf8]">
                    <label for="remember" class="ml-2 text-xs font-medium text-gray-400">Keep session active</label>
                </div>

                <button type="submit" class="w-full bg-neon-red hover:bg-sky-600 neon-red-glow text-white font-bold py-3.5 rounded-xl transition-all flex items-center justify-center uppercase tracking-widest text-xs relative overflow-hidden group/btn">
                    <span class="relative z-10 flex items-center">
                        <i class='bx bx-log-in-circle mr-2 text-lg'></i> Initialize Session
                    </span>
                    <!-- Hover shine effect -->
                    <div class="absolute inset-0 h-full w-full scale-[2.0] blur-xl bg-white/20 opacity-0 group-hover/btn:opacity-100 transition-opacity"></div>
                </button>
                
            </form>
            
            <div class="mt-8 text-center border-t border-[#222] pt-6">
                 <a href="{{ route('public.index') }}" class="text-xs text-gray-500 font-medium hover:text-[#38bdf8] transition flex items-center justify-center">
                     <i class='bx bx-left-arrow-alt mr-1'></i> Return to Public Gateway
                 </a>
            </div>
            
        </div>
        
        <!-- Minimal Footer -->
        <p class="text-center text-[10px] text-gray-600 mt-8 uppercase tracking-widest">
            &copy; 2026 HELPDESK AI System
        </p>

    </div>

</body>
</html>
