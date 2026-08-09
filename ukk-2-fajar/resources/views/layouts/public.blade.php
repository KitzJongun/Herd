<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'NEXUS IT Support')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #010409; /* Deep space dark */
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* Abstract Glow Rings Background */
        .bg-rings {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100vh;
            z-index: -1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 50% 60%, #0c1a35 0%, #010409 60%);
        }

        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(125, 211, 252, 0.05); /* very subtle sky blue */
        }
        
        .ring-1 { width: 40vw; height: 40vw; box-shadow: 0 0 120px rgba(56, 189, 248, 0.15), inset 0 0 120px rgba(56, 189, 248, 0.1); }
        .ring-2 { width: 70vw; height: 70vw; box-shadow: 0 0 150px rgba(125, 211, 252, 0.1), inset 0 0 100px rgba(125, 211, 252, 0.05); }
        .ring-3 { width: 100vw; height: 100vw; border-color: rgba(125, 211, 252, 0.02); }

        /* Floating Capsule Navbar */
        .nav-capsule {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* Gradient Text */
        .text-gradient {
            background: linear-gradient(180deg, #ffffff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Primary Light Blue Button */
        .btn-light-blue {
            background-color: #bae6fd; /* sky-200 */
            color: #020617; /* slate-950 */
            transition: all 0.2s ease;
            box-shadow: 0 0 20px rgba(186, 230, 253, 0.3);
        }
        .btn-light-blue:hover {
            background-color: #e0f2fe; /* sky-100 */
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(186, 230, 253, 0.5);
        }
        
        .btn-dark {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
        }
        .btn-dark:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Glass Form */
        .form-glass {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .input-glass {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #f8fafc;
        }
        .input-glass:focus {
            border-color: #bae6fd;
            outline: none;
            box-shadow: 0 0 0 1px rgba(186, 230, 253, 0.3);
        }

        .icon-circle {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
</head>
<body class="antialiased min-h-screen selection:bg-sky-500/30 selection:text-sky-100 flex flex-col items-center">

    <!-- Atmospheric Deep Space Rings -->
    <div class="bg-rings">
        <div class="ring ring-3"></div>
        <div class="ring ring-2"></div>
        <div class="ring ring-1"></div>
        <!-- Center pure glow -->
        <div class="absolute w-[20vw] h-[20vw] bg-sky-500/10 rounded-full blur-[100px]"></div>
    </div>









    <main class="w-full relative z-10 pt-32 pb-20">
        @yield('content')
    </main>

    <!-- Mobile Nav Anchor -->
    <nav class="md:hidden fixed bottom-0 w-full nav-capsule border-t border-slate-800 flex justify-around z-50 rounded-t-3xl rounded-b-none py-2 px-2 pb-6">
        <a href="#form-laporan" class="flex flex-col items-center py-2 w-full text-slate-300">
            <i class='bx bx-file text-xl mb-1'></i>
            <span class="text-[10px] font-medium">Lapor</span>
        </a>
        <a href="#lacak-tiket" class="flex flex-col items-center py-2 w-full text-slate-300">
            <i class='bx bx-radar text-xl mb-1'></i>
            <span class="text-[10px] font-medium">Lacak</span>
        </a>
    </nav>

</body>
</html>
