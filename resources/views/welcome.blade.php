<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Smart Vehicle Maintenance</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=DM+Sans:wght@400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #080c14;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .heading { font-family: 'Rajdhani', sans-serif; }
        .mono { font-family: 'Share Tech Mono', monospace; }

        /* Animated background grid */
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

        /* Glow orbs */
        .orb1 {
            position: fixed;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0,102,255,0.12), transparent 70%);
            top: -100px; right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }
        .orb2 {
            position: fixed;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(0,245,255,0.08), transparent 70%);
            bottom: 100px; left: -80px;
            border-radius: 50%;
            pointer-events: none;
        }

        .container {
            max-width: 440px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        /* Nav */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
        }
        .logo {
            font-family: 'Rajdhani', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
        }
        .logo span { color: #00f5ff; }
        .nav-links { display: flex; gap: 10px; }
        .btn-outline {
            padding: 8px 18px;
            border-radius: 10px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            border: 1px solid rgba(0,245,255,0.25);
            color: #00f5ff;
            background: rgba(0,245,255,0.05);
            transition: all 0.2s;
        }
        .btn-outline:hover { background: rgba(0,245,255,0.12); }
        .btn-primary {
            padding: 8px 18px;
            border-radius: 10px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-decoration: none;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            color: #080c14;
            transition: all 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; transform: scale(0.98); }

        /* Hero */
        .hero {
            padding: 48px 0 32px;
            text-align: center;
        }
        .hero-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            color: rgba(0,245,255,0.6);
            letter-spacing: 3px;
            margin-bottom: 16px;
        }
        .hero h1 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 48px;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .hero h1 span { color: #00f5ff; }
        .hero p {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .hero-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .hero-btn-main {
            padding: 14px 28px;
            border-radius: 14px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;
            text-decoration: none;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            color: #080c14;
            box-shadow: 0 0 30px rgba(0,245,255,0.3);
            transition: all 0.2s;
        }
        .hero-btn-main:hover { transform: translateY(-2px); box-shadow: 0 0 40px rgba(0,245,255,0.4); }
        .hero-btn-sec {
            padding: 14px 28px;
            border-radius: 14px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 2px;
            text-decoration: none;
            border: 1px solid rgba(0,245,255,0.2);
            color: #00f5ff;
            background: rgba(0,245,255,0.05);
            transition: all 0.2s;
        }
        .hero-btn-sec:hover { background: rgba(0,245,255,0.1); }

        /* Stats bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 32px 0;
        }
        .stat {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(0,245,255,0.1);
            border-radius: 16px;
            padding: 16px 12px;
            text-align: center;
        }
        .stat-num {
            font-family: 'Rajdhani', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #00f5ff;
        }
        .stat-label {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Features */
        .section-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: rgba(0,245,255,0.5);
            letter-spacing: 3px;
            margin-bottom: 12px;
        }
        .features { margin-bottom: 32px; }
        .feature-card {
            background: rgba(13,20,33,0.8);
            border: 1px solid rgba(0,245,255,0.08);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            transition: border-color 0.2s;
        }
        .feature-card:hover { border-color: rgba(0,245,255,0.2); }
        .feature-icon {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: rgba(0,245,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .feature-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 3px;
        }
        .feature-desc {
            font-size: 13px;
            color: #64748b;
            line-height: 1.5;
        }

        /* CTA */
        .cta {
            background: linear-gradient(135deg, rgba(0,102,255,0.1), rgba(0,245,255,0.05));
            border: 1px solid rgba(0,245,255,0.15);
            border-radius: 24px;
            padding: 32px 24px;
            text-align: center;
            margin-bottom: 40px;
        }
        .cta h2 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }
        .cta h2 span { color: #00f5ff; }
        .cta p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px 0 32px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        footer p {
            font-size: 12px;
            color: #475569;
        }
        footer span { color: rgba(0,245,255,0.5); }
    </style>
</head>
<body>
    <div class="orb1"></div>
    <div class="orb2"></div>

    <div class="container">

        {{-- Nav --}}
        <nav>
            <div class="logo">AUTO<span>MATE</span>X</div>
            @if(Route::has('login'))
            <div class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">DASHBOARD</a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline">LOGIN</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-primary">GET STARTED</a>
                    @endif
                @endauth
            </div>
            @endif
        </nav>

        {{-- Hero --}}
        <div class="hero">
            <p class="hero-label">// SMART VEHICLE MANAGEMENT SYSTEM</p>
            <h1>Your Vehicle.<br><span>Fully Managed.</span></h1>
            <p>Track service history, monitor fuel consumption, verify parts, and book garage appointments — all in one futuristic platform.</p>
            <div class="hero-btns">
                @auth
                    <a href="{{ url('/dashboard') }}" class="hero-btn-main">OPEN DASHBOARD →</a>
                @else
                    <a href="{{ route('register') }}" class="hero-btn-main">GET STARTED FREE →</a>
                    <a href="{{ route('login') }}" class="hero-btn-sec">SIGN IN</a>
                @endauth
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-bar">
            <div class="stat">
                <div class="stat-num">5+</div>
                <div class="stat-label">Core Modules</div>
            </div>
            <div class="stat">
                <div class="stat-num">PWA</div>
                <div class="stat-label">Works Offline</div>
            </div>
            <div class="stat">
                <div class="stat-num">QR</div>
                <div class="stat-label">Smart Access</div>
            </div>
        </div>

        {{-- Features --}}
        <div class="features">
            <p class="section-label">// KEY FEATURES</p>

            <div class="feature-card">
                <div class="feature-icon">🚗</div>
                <div>
                    <div class="feature-title">Vehicle Management</div>
                    <div class="feature-desc">Add and manage all your vehicles with full specification tracking and health monitoring.</div>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔧</div>
                <div>
                    <div class="feature-title">Service History</div>
                    <div class="feature-desc">Log every service event and get AI-powered maintenance suggestions based on mileage.</div>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⛽</div>
                <div>
                    <div class="feature-title">Fuel Tracking</div>
                    <div class="feature-desc">Automatically calculate fuel efficiency and track consumption trends over time.</div>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔍</div>
                <div>
                    <div class="feature-title">Parts Verification</div>
                    <div class="feature-desc">Verify OEM parts compatibility across 22 components for 5 vehicle models.</div>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <div>
                    <div class="feature-title">Garage Booking</div>
                    <div class="feature-desc">Browse registered garages, book appointments, and receive digital invoices.</div>
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <div>
                    <div class="feature-title">QR Code Access</div>
                    <div class="feature-desc">Generate QR codes for instant public vehicle history access — no login required.</div>
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="cta">
            <h2>Ready to <span>AutoMate</span> Your Vehicle?</h2>
            <p>Join vehicle owners who manage their maintenance smarter.</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="hero-btn-main">GO TO DASHBOARD →</a>
            @else
                <a href="{{ route('register') }}" class="hero-btn-main">CREATE FREE ACCOUNT →</a>
            @endauth
        </div>

        {{-- Footer --}}
        <footer>
            <p>© {{ date('Y') }} <span>AutoMateX</span> · Built with Laravel · PUSL3190</p>
        </footer>

    </div>
</body>
</html>