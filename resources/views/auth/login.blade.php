<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --cyan: #00f5ff; --blue: #0066ff; --orange: #ff6b00;
            --bg: #080c14; --card: #0d1421; --border: rgba(0,245,255,0.12);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background-color: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(rgba(0,245,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,245,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none; z-index: 0;
        }
        .orb-1 {
            position: fixed; top: -150px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,102,255,0.18) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .orb-2 {
            position: fixed; bottom: -150px; left: -100px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(0,245,255,0.1) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 10;
            background: rgba(13,20,33,0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px 36px;
            width: 100%; max-width: 420px;
            box-shadow: 0 0 60px rgba(0,245,255,0.06), 0 24px 48px rgba(0,0,0,0.4);
        }
        .logo-wrap {
            display: flex; align-items: center; gap: 10px;
            justify-content: center; margin-bottom: 8px;
        }
        .logo-icon {
            width: 40px; height: 40px; border-radius: 12px;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 16px rgba(0,245,255,0.4);
        }
        .logo-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 26px; font-weight: 700; letter-spacing: 1px;
            color: white;
        }
        .logo-text span { color: var(--cyan); }
        .tagline {
            text-align: center;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.2em;
            color: rgba(0,245,255,0.4);
            text-transform: uppercase;
            margin-bottom: 32px;
        }
        .form-label {
            display: block;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(0,245,255,0.5);
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%; padding: 12px 16px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(0,245,255,0.15);
            border-radius: 12px;
            color: #e2e8f0; font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: rgba(0,245,255,0.4);
            box-shadow: 0 0 0 3px rgba(0,245,255,0.08);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.2); }
        .form-group { margin-bottom: 18px; }
        .remember-row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: #94a3b8; cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--cyan);
        }
        .forgot-link {
            font-size: 12px; color: rgba(0,245,255,0.6);
            text-decoration: none;
            font-family: 'Share Tech Mono', monospace;
            letter-spacing: 0.05em;
        }
        .forgot-link:hover { color: var(--cyan); }
        .btn-primary {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            border: none; border-radius: 12px;
            color: #080c14; font-size: 14px; font-weight: 700;
            font-family: 'Rajdhani', sans-serif;
            letter-spacing: 2px; text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 0 24px rgba(0,245,255,0.3);
            transition: all 0.2s;
        }
        .btn-primary:hover {
            box-shadow: 0 0 36px rgba(0,245,255,0.5);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }
        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 24px 0;
        }
        .divider-line {
            flex: 1; height: 1px;
            background: rgba(0,245,255,0.1);
        }
        .divider-text {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em;
            color: rgba(255,255,255,0.2); text-transform: uppercase;
        }
        .register-link {
            display: block; text-align: center;
            font-size: 13px; color: #64748b;
        }
        .register-link a {
            color: var(--cyan); text-decoration: none; font-weight: 500;
        }
        .register-link a:hover { text-decoration: underline; }
        .error-msg {
            background: rgba(255,107,0,0.1);
            border: 1px solid rgba(255,107,0,0.3);
            border-radius: 10px; padding: 10px 14px;
            font-size: 13px; color: #ff6b00;
            margin-bottom: 18px;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card { animation: fadeInUp 0.5s ease forwards; }
    </style>
</head>
<body>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <div class="card">
        <!-- Logo -->
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v5"/>
                    <circle cx="16" cy="19" r="2"/><circle cx="7" cy="19" r="2"/>
                    <path d="M13 19H9M16 17V9l-4-4H5v12"/>
                </svg>
            </div>
            <div class="logo-text">AUTO<span>MATEX</span></div>
        </div>
        <p class="tagline">// vehicle maintenance system</p>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="error-msg">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Session Status -->
        @if (session('status'))
            <div style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);border-radius:10px;padding:10px 14px;font-size:13px;color:var(--cyan);margin-bottom:18px;">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">// email address</label>
                <input class="form-input" id="email" type="email" name="email"
                       value="{{ old('email') }}" required autofocus
                       placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">// password</label>
                <input class="form-input" id="password" type="password" name="password"
                       required placeholder="••••••••••">
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        forgot?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-primary">
                SIGN IN →
            </button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-text">new here?</div>
            <div class="divider-line"></div>
        </div>

        <span class="register-link">
            Don't have an account?
            <a href="{{ route('register') }}">Create one</a>
        </span>
    </div>
</body>
</html>