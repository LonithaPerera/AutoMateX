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

        /* Lang switcher in header */
        .lang-switcher-header {
            display: flex;
            gap: 3px;
        }
        .lang-btn-header {
            padding: 3px 7px;
            border-radius: 5px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            text-decoration: none;
            border: 1px solid rgba(0,245,255,0.12);
            color: #475569;
            background: transparent;
            transition: all 0.2s;
            letter-spacing: 0.5px;
        }
        .lang-btn-header:hover { border-color: rgba(0,245,255,0.3); color: #00f5ff; }
        .lang-btn-header.active { border-color: rgba(0,245,255,0.35); color: #00f5ff; background: rgba(0,245,255,0.07); }
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
                <img src="/images/logo.png" alt="AutoMateX" style="height:64px;width:auto;">
                <span class="heading font-bold text-white" style="display:inline-flex;align-items:center;font-size:26px;letter-spacing:1px;">Auto<span class="text-cyan">Mate</span><span style="color:#ff6b00;font-size:1.2em;line-height:1;">X</span></span>
            </a>

            <!-- Right -->
            <div class="flex items-center gap-2">
                <!-- Language Switcher -->
                <div class="lang-switcher-header">
                    <a href="{{ route('locale.switch', 'en') }}" class="lang-btn-header {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('locale.switch', 'si') }}" class="lang-btn-header {{ app()->getLocale() === 'si' ? 'active' : '' }}">SI</a>
                    <a href="{{ route('locale.switch', 'ta') }}" class="lang-btn-header {{ app()->getLocale() === 'ta' ? 'active' : '' }}">TA</a>
                </div>

                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="relative w-9 h-9 rounded-xl glass flex items-center justify-center border border-purple-500/30 hover:border-purple-400/50 transition-colors">
                        <x-heroicon-o-shield-check class="w-4 h-4" style="color:#a855f7;" />
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}"
                   class="w-16 h-16 rounded-xl overflow-hidden border-2 flex-shrink-0 block"
                   style="border-color:rgba(0,245,255,0.3);">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                             alt="{{ Auth::user()->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-lg font-bold heading"
                             style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:white;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                </a>
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

            @php $role = Auth::user()->role; @endphp

            @if($role === 'garage')
                {{-- ── GARAGE: Home · Parts · Profile ── --}}
                @php
                    $garagePending = Auth::user()->garage
                        ? Auth::user()->garage->bookings()->where('status','pending')->count()
                        : 0;
                @endphp

                <!-- Home → garage dashboard -->
                <a href="{{ route('garage.dashboard') }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('garage.dashboard') ? '' : 'hover:bg-white/5' }}"
                   style="{{ request()->routeIs('garage.dashboard') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <div class="relative">
                        <x-heroicon-o-home class="w-5 h-5" style="color:{{ request()->routeIs('garage.dashboard') ? 'var(--cyan)' : '#64748b' }};" />
                        @if($garagePending > 0)
                            <span class="absolute -top-1 -right-1.5 flex items-center justify-center rounded-full text-white font-bold"
                                  style="background:#f87171;font-size:9px;min-width:15px;height:15px;padding:0 3px;font-family:'Share Tech Mono',monospace;">
                                {{ $garagePending > 9 ? '9+' : $garagePending }}
                            </span>
                        @endif
                    </div>
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ request()->routeIs('garage.dashboard') ? 'var(--cyan)' : '#64748b' }}">{{ __('app.home') }}</span>
                    @if(request()->routeIs('garage.dashboard'))
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

                <!-- Parts (center) -->
                <a href="{{ route('parts.index') }}" class="flex flex-col items-center -mt-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all active:scale-95"
                         style="background:linear-gradient(135deg,#0066ff,#00f5ff);box-shadow:0 0 20px rgba(0,245,255,0.4),0 4px 16px rgba(0,102,255,0.5);">
                        <x-heroicon-o-wrench-screwdriver class="w-6 h-6" style="color:white;" />
                    </div>
                    <span class="text-xs font-semibold heading tracking-wide mt-1.5 text-cyan">{{ __('app.parts') }}</span>
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('profile.*') ? '' : 'hover:bg-white/5' }}"
                   style="{{ request()->routeIs('profile.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <x-heroicon-o-user-circle class="w-5 h-5" style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }};" />
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }}">{{ __('app.profile') }}</span>
                    @if(request()->routeIs('profile.*'))
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

            @else
                {{-- ── VEHICLE OWNER & ADMIN: Home · Vehicles · Parts · Bookings · Profile ── --}}

                <!-- Home -->
                @php
                    $homeRoute = $role === 'admin' ? route('admin.dashboard') : route('dashboard');
                    $homeActive = $role === 'admin' ? request()->routeIs('admin.dashboard') : request()->routeIs('dashboard');
                @endphp
                <a href="{{ $homeRoute }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ $homeActive ? '' : 'hover:bg-white/5' }}"
                   style="{{ $homeActive ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <x-heroicon-o-home class="w-5 h-5" style="color:{{ $homeActive ? 'var(--cyan)' : '#64748b' }};" />
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ $homeActive ? 'var(--cyan)' : '#64748b' }}">{{ __('app.home') }}</span>
                    @if($homeActive)
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

                <!-- Vehicles -->
                <a href="{{ route('vehicles.index') }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('vehicles.*') ? '' : 'hover:bg-white/5' }}"
                   style="{{ request()->routeIs('vehicles.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <x-heroicon-o-truck class="w-5 h-5" style="color:{{ request()->routeIs('vehicles.*') ? 'var(--cyan)' : '#64748b' }};" />
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ request()->routeIs('vehicles.*') ? 'var(--cyan)' : '#64748b' }}">{{ __('app.vehicles') }}</span>
                    @if(request()->routeIs('vehicles.*'))
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

                <!-- Parts (center) -->
                <a href="{{ route('parts.index') }}" class="flex flex-col items-center -mt-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all active:scale-95"
                         style="background:linear-gradient(135deg,#0066ff,#00f5ff);box-shadow:0 0 20px rgba(0,245,255,0.4),0 4px 16px rgba(0,102,255,0.5);">
                        <x-heroicon-o-wrench-screwdriver class="w-6 h-6" style="color:white;" />
                    </div>
                    <span class="text-xs font-semibold heading tracking-wide mt-1.5 text-cyan">{{ __('app.parts') }}</span>
                </a>

                <!-- Bookings -->
                <a href="{{ route('bookings.index') }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('bookings.*') ? '' : 'hover:bg-white/5' }}"
                   style="{{ request()->routeIs('bookings.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <x-heroicon-o-calendar-days class="w-5 h-5" style="color:{{ request()->routeIs('bookings.*') ? 'var(--cyan)' : '#64748b' }};" />
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ request()->routeIs('bookings.*') ? 'var(--cyan)' : '#64748b' }}">{{ __('app.bookings') }}</span>
                    @if(request()->routeIs('bookings.*'))
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center py-2 px-4 rounded-xl transition-all {{ request()->routeIs('profile.*') ? '' : 'hover:bg-white/5' }}"
                   style="{{ request()->routeIs('profile.*') ? 'background:rgba(0,245,255,0.08);' : '' }}">
                    <x-heroicon-o-user-circle class="w-5 h-5" style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }};" />
                    <span class="text-xs font-semibold heading tracking-wide mt-0.5"
                          style="color:{{ request()->routeIs('profile.*') ? 'var(--cyan)' : '#64748b' }}">{{ __('app.profile') }}</span>
                    @if(request()->routeIs('profile.*'))
                        <div style="width:20px;height:2px;background:var(--cyan);box-shadow:0 0 8px var(--cyan);border-radius:1px;margin-top:2px;"></div>
                    @endif
                </a>

            @endif

        </div>
    </nav>

    <!-- Unsaved Changes Warning -->
    <script>
        (function () {
            let formDirty = false;

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form').forEach(function (form) {
                    // Skip forms with no real user inputs (delete/archive/logout forms)
                    if (!form.querySelector('input[type="text"], input[type="number"], input[type="email"], input[type="file"], textarea, select')) return;
                    // Skip forms explicitly opted out
                    if (form.hasAttribute('data-no-warn')) return;

                    form.querySelectorAll('input, textarea, select').forEach(function (el) {
                        if (el.type === 'hidden') return;
                        el.addEventListener('input',  function () { formDirty = true; });
                        el.addEventListener('change', function () { formDirty = true; });
                    });

                    // Clear flag when the form is intentionally submitted
                    form.addEventListener('submit', function () { formDirty = false; });
                });
            });

            window.addEventListener('beforeunload', function (e) {
                if (formDirty) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                    return e.returnValue;
                }
            });
        })();
    </script>

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
