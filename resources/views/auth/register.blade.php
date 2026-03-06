<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Register</title>
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
            padding: 24px 16px;
            overflow-x: hidden;
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
            position: fixed; bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0,245,255,0.08) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .card {
            position: relative; z-index: 10;
            background: rgba(13,20,33,0.85);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 36px 36px;
            width: 100%; max-width: 440px;
            box-shadow: 0 0 60px rgba(0,245,255,0.06), 0 24px 48px rgba(0,0,0,0.4);
            animation: fadeInUp 0.5s ease forwards;
        }
        .logo-wrap {
            display: flex; align-items: center; gap: 10px;
            justify-content: center; margin-bottom: 6px;
        }
        .logo-icon {
            width: 38px; height: 38px; border-radius: 11px;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 14px rgba(0,245,255,0.4);
        }
        .logo-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 24px; font-weight: 700; letter-spacing: 1px; color: white;
        }
        .logo-text span { color: var(--cyan); }
        .tagline {
            text-align: center;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.2em;
            color: rgba(0,245,255,0.4); text-transform: uppercase;
            margin-bottom: 28px;
        }
        .form-label {
            display: block;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(0,245,255,0.5); margin-bottom: 6px;
        }
        .form-input {
            width: 100%; padding: 11px 16px;
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
        .form-group { margin-bottom: 16px; }
        .role-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 10px; margin-top: 4px;
        }
        .role-option { position: relative; }
        .role-option input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .role-option label {
            display: flex; flex-direction: column; align-items: center;
            padding: 12px 8px; border-radius: 12px; cursor: pointer;
            border: 1px solid rgba(0,245,255,0.12);
            background: rgba(255,255,255,0.02);
            transition: all 0.2s;
            gap: 4px;
        }
        .role-option label:hover {
            border-color: rgba(0,245,255,0.3);
            background: rgba(0,245,255,0.05);
        }
        .role-option input:checked + label {
            border-color: rgba(0,245,255,0.5);
            background: rgba(0,245,255,0.1);
            box-shadow: 0 0 12px rgba(0,245,255,0.15);
        }
        .role-icon { font-size: 20px; }
        .role-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 13px; font-weight: 600;
            letter-spacing: 0.5px; color: #e2e8f0;
        }
        .role-desc {
            font-size: 10px; color: #64748b; text-align: center;
        }
        .btn-primary {
            width: 100%; padding: 13px; margin-top: 6px;
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
        .login-link {
            display: block; text-align: center;
            font-size: 13px; color: #64748b; margin-top: 20px;
        }
        .login-link a { color: var(--cyan); text-decoration: none; font-weight: 500; }
        .login-link a:hover { text-decoration: underline; }
        .error-msg {
            background: rgba(255,107,0,0.1);
            border: 1px solid rgba(255,107,0,0.3);
            border-radius: 10px; padding: 10px 14px;
            font-size: 13px; color: #ff6b00; margin-bottom: 16px;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <div class="card">
        <div class="logo-wrap">
            <div class="logo-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                    <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v5"/>
                    <circle cx="16" cy="19" r="2"/><circle cx="7" cy="19" r="2"/>
                    <path d="M13 19H9M16 17V9l-4-4H5v12"/>
                </svg>
            </div>
            <div class="logo-text">AUTO<span>MATEX</span></div>
        </div>
        <p class="tagline">// create your account</p>

        @if ($errors->any())
            <div class="error-msg">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">// full name</label>
                <input class="form-input" id="name" type="text" name="name"
                       value="{{ old('name') }}" required autofocus placeholder="Your full name">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">// email address</label>
                <input class="form-input" id="email" type="email" name="email"
                       value="{{ old('email') }}" required placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">// password</label>
                <input class="form-input" id="password" type="password" name="password"
                       required placeholder="min. 8 characters">
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">// confirm password</label>
                <input class="form-input" id="password_confirmation" type="password"
                       name="password_confirmation" required placeholder="repeat password">
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label class="form-label">// i am a</label>
                <div class="role-grid">
                    <div class="role-option">
                        <input type="radio" id="role_owner" name="role"
                               value="vehicle_owner" checked>
                        <label for="role_owner">
                            <span class="role-icon">🚗</span>
                            <span class="role-name">Owner</span>
                            <span class="role-desc">Vehicle owner</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" id="role_garage" name="role" value="garage">
                        <label for="role_garage">
                            <span class="role-icon">🔧</span>
                            <span class="role-name">Garage</span>
                            <span class="role-desc">Garage operator</span>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                CREATE ACCOUNT →
            </button>
        </form>

        <span class="login-link">
            Already registered?
            <a href="{{ route('login') }}">Sign in</a>
        </span>
    </div>
</body>
</html>