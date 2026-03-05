<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AutoMateX') }}</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#080c14">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="AutoMateX">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;500;600;700&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --cyan: #00f5ff;
            --blue: #0066ff;
            --orange: #ff6b00;
            --bg: #080c14;
            --surface: #0d1421;
            --card: #111827;
            --border: rgba(0,245,255,0.12);
            --glow-cyan: 0 0 20px rgba(0,245,255,0.3);
            --glow-orange: 0 0 20px rgba(255,107,0,0.4);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: #e2e8f0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        h1,h2,h3,.heading { font-family: 'Rajdhani', sans-serif; }
        .mono { font-family: 'Share Tech Mono', monospace; }

        /* Animated grid background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,245,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,245,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            position: fixed; top: -120px; right: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0,102,255,0.15) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .orb-2 {
            position: fixed; bottom: 60px; left: -100px;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(255,107,0,0.1) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        .glass {
            background: rgba(13,20,33,0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border);
        }

        .glass-bright {
            background: rgba(17,24,39,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0,245,255,0.2);
        }

        .text-cyan { color: var(--cyan); }
        .text-orange { color: var(--orange); }
        .section-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.2em;
            text-transform: uppercase; color: rgba(0,245,255,0.5);
        }
        .tag {
            font-size: 10px; letter-spacing: 0.08em; font-weight: 600;
            padding: 2px 8px; border-radius: 4px; text-transform: uppercase;
            font-family: 'Share Tech Mono', monospace;
        }

        @keyframes borderGlow {
            0%,100% { border-color: rgba(0,245,255,0.2); box-shadow: 0 0 10px rgba(0,245,255,0.1); }
            50% { border-color: rgba(0,245,255,0.5); box-shadow: 0 0 25px rgba(0,245,255,0.25); }
        }
        .animate-glow { animation: borderGlow 3s ease-in-out infinite; }

        @keyframes borderGlowOrange {
            0%,100% { border-color: rgba(255,107,0,0.3); box-shadow: 0 0 12px rgba(255,107,0,0.2); }
            50% { border-color: rgba(255,107,0,0.7); box-shadow: 0 0 30px rgba(255,107,0,0.4); }
        }
        .animate-glow-orange { animation: borderGlowOrange 2.5s ease-in-out infinite; }

        @keyframes pulseDot {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }
        .pulse-dot { animation: pulseDot 1.5s ease-in-out infinite; }

        @keyframes scanLine {
            0% { top: 0%; }
            100% { top: 100%; }
        }
        .scan-line {
            position: absolute; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            animation: scanLine 2s linear infinite;
            box-shadow: 0 0 8px var(--cyan);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeInUp 0.5s ease forwards; }
        .fade-in-1 { animation-delay: 0.1s; opacity: 0; }
        .fade-in-2 { animation-delay: 0.2s; opacity: 0; }
        .fade-in-3 { animation-delay: 0.3s; opacity: 0; }
        .fade-in-4 { animation-delay: 0.4s; opacity: 0; }
        .fade-in-5 { animation-delay: 0.5s; opacity: 0; }

        .vehicle-card { transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer; }
        .vehicle-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,245,255,0.15); }

        .speed-ring {
            width: 56px; height: 56px; border-radius: 50%;
            background: conic-gradient(var(--cyan) 0% 68%, rgba(255,255,255,0.05) 68% 100%);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 15px rgba(0,245,255,0.3);
        }
        .speed-ring-orange {
            background: conic-gradient(var(--orange) 0% 42%, rgba(255,255,255,0.05) 42% 100%);
            box-shadow: 0 0 15px rgba(255,107,0,0.3);
        }
        .speed-ring-inner {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--card);
            display: flex; align-items: center; justify-content: center;
        }

        /* Bottom nav */
        .bottom-nav {
            background: rgba(8,12,20,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0,245,255,0.1);
        }
        .main-content { padding-bottom: 90px; }

        /* Page content padding for sticky header */
        .page-wrapper { position: relative; z-index: 10; }
    </style>
</head>
<body>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <!-- TOP HEADER -->
    <header class="glass sticky top-0 z-50" style="border-left:none;border-right:none;border-top:none;">
        <div class="max-w-lg mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#0066ff,#00f5ff);box-shadow:0 0 12px rgba(0,245,255,0.4);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                        <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v5"/>
                        <circle cx="16" cy="19" r="2"/><circle cx="7" cy="19" r="2"/>
                        <path d="M13 19H9M16 17V9l-4-4H5v12"/>
                    </svg>
                </div>
                <span class="heading font-bold text-xl tracking-wide text-white">AUTO<span class="text-cyan">MATEX</span></span>
            </a>

            <!-- Right -->
            <div class="flex items-center gap-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative w-9 h-9 rounded-xl glass flex items-center justify-center border border-purple-500/30 hover:border-purple-400/50 transition-colors">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </a>
                @endif
                <div class="w-9 h-9 rounded-xl overflow-hidden border-2" style="border-color:rgba(0,245,255,0.3);">
                    <div class="w-full h-full flex items-center justify-center text-sm font-bold heading"
                         style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:white;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <div class="page-wrapper main-content">
        {{ $slot }}
    </div>

    <!-- BOTTOM NAVIGATION -->
    <nav class="bottom-nav fixed bottom-0 left-0 right-0 z-50 py-2 px-2">
        <div class="max-w-lg mx-auto flex items-center justify-around">

            <!-- Home -->
            <a href="{{ route('dashboard') }}"
               class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('dashboard') ? '' : 'hover:bg-white/5' }}"
               style="{{ request()->routeIs('dashboard') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"
                     style="color:{{ request()->routeIs('dashboard') ? 'var(--cyan)' : '#64748b' }}">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                      style="color:{{ request()->routeIs('dashboard') ? 'var(--cyan)' : '#64748b' }}">HOME</span>
                @if(request()->routeIs('dashboard'))
                    <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                @endif
            </a>

            <!-- Vehicles -->
            <a href="{{ route('vehicles.index') }}"
               class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('vehicles.*') ? '' : 'hover:bg-white/5' }}"
               style="{{ request()->routeIs('vehicles.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     style="color:{{ request()->routeIs('vehicles.*') ? 'var(--cyan)' : '#64748b' }}">
                    <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v5"/>
                    <circle cx="16" cy="19" r="2"/><circle cx="7" cy="19" r="2"/>
                    <path d="M13 19H9M16 17V9l-4-4H5v12"/>
                </svg>
                <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                      style="color:{{ request()->routeIs('vehicles.*') ? 'var(--cyan)' : '#64748b' }}">VEHICLES</span>
                @if(request()->routeIs('vehicles.*'))
                    <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                @endif
            </a>

            <!-- Parts (center) -->
            <a href="{{ route('parts.index') }}" class="flex flex-col items-center -mt-5">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all active:scale-95"
                     style="background:linear-gradient(135deg,#0066ff,#00f5ff);box-shadow:0 0 20px rgba(0,245,255,0.4),0 4px 16px rgba(0,102,255,0.5);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                        <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold heading tracking-wide mt-1.5 text-cyan">PARTS</span>
            </a>

            <!-- Bookings -->
            <a href="{{ route('bookings.index') }}"
               class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('bookings.*') ? '' : 'hover:bg-white/5' }}"
               style="{{ request()->routeIs('bookings.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     style="color:{{ request()->routeIs('bookings.*') ? 'var(--cyan)' : '#64748b' }}">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                      style="color:{{ request()->routeIs('bookings.*') ? 'var(--cyan)' : '#64748b' }}">BOOKINGS</span>
                @if(request()->routeIs('bookings.*'))
                    <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                @endif
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.edit') }}"
               class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('profile.*') ? '' : 'hover:bg-white/5' }}"
               style="{{ request()->routeIs('profile.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }}">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                      style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }}">PROFILE</span>
                @if(request()->routeIs('profile.*'))
                    <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                @endif
            </a>

        </div>
    </nav>

    <!-- PWA Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('AutoMateX SW registered:', reg.scope))
                    .catch(err => console.log('SW error:', err));
            });
        }
    </script>
</body>
</html>