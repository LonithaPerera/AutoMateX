<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Forgot Password</title>
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
            animation: fadeInUp 0.5s ease forwards;
        }
        .logo-wrap {
            display: flex; align-items: center; gap: 10px;
            justify-content: center; margin-bottom: 8px;
        }
        .logo-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 26px; font-weight: 700; letter-spacing: 1px;
            color: white; display: inline-flex; align-items: center;
        }
        .logo-text span { color: var(--cyan); }
        .tagline {
            text-align: center;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.2em;
            color: rgba(0,245,255,0.4);
            text-transform: uppercase;
            margin-bottom: 24px;
        }
        .hint-box {
            background: rgba(0,245,255,0.05);
            border: 1px solid rgba(0,245,255,0.12);
            border-radius: 12px; padding: 12px 14px;
            font-size: 13px; color: #94a3b8;
            line-height: 1.6; margin-bottom: 24px;
        }
        .form-label {
            display: block;
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(0,245,255,0.5); margin-bottom: 6px;
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
        .form-group { margin-bottom: 20px; }
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
        .back-link {
            display: block; text-align: center;
            font-size: 13px; color: #64748b; margin-top: 20px;
        }
        .back-link a { color: var(--cyan); text-decoration: none; font-weight: 500; }
        .back-link a:hover { text-decoration: underline; }
        .success-msg {
            background: rgba(0,245,255,0.08);
            border: 1px solid rgba(0,245,255,0.2);
            border-radius: 10px; padding: 10px 14px;
            font-size: 13px; color: var(--cyan);
            margin-bottom: 18px;
        }
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
    </style>
</head>
<body>
    <div class="orb-1"></div>
    <div class="orb-2"></div>

    <div class="card">
        <div class="logo-wrap">
            <img src="/images/logo.png" alt="AutoMateX" style="height:64px;width:auto;">
            <div class="logo-text">Auto<span>Mate</span><span style="color:#ff6b00;font-size:1.2em;line-height:1;">X</span></div>
        </div>
        <p class="tagline">{{ __('app.vms_tagline') }}</p>

        @if (session('status'))
            <div class="success-msg">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-msg">
                @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <div class="hint-box">{{ __('app.forgot_password_hint') }}</div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">{{ __('app.field_email') }}</label>
                <input class="form-input" id="email" type="email" name="email"
                       value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            </div>
            <button type="submit" class="btn-primary">{{ __('app.email_reset_link_btn') }}</button>
        </form>

        <span class="back-link">
            <a href="{{ route('login') }}">← {{ __('app.back_to_login') }}</a>
        </span>
    </div>
</body>
</html>
