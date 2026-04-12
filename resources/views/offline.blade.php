<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Offline</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=DM+Sans:wght@400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #080c14;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,245,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,245,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
        .card {
            background: rgba(13,20,33,0.9);
            border: 1px solid rgba(0,245,255,0.15);
            border-radius: 28px;
            padding: 48px 32px;
            text-align: center;
            max-width: 380px;
            width: 100%;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 60px rgba(0,245,255,0.06);
        }
        .icon {
            font-size: 64px;
            margin-bottom: 20px;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: rgba(255,107,0,0.6);
            letter-spacing: 3px;
            margin-bottom: 12px;
        }
        h1 {
            font-family: 'Rajdhani', sans-serif;
            font-size: 36px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 12px;
        }
        h1 span { color: #ff6b00; }
        p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 24px 0;
        }
        .cached-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            color: rgba(0,245,255,0.5);
            letter-spacing: 2px;
            margin-bottom: 12px;
        }
        .cached-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 24px;
        }
        .cached-item {
            background: rgba(0,245,255,0.04);
            border: 1px solid rgba(0,245,255,0.1);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13px;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .cached-item span { color: #00f5ff; font-size: 15px; }
        .retry-btn {
            display: block;
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;
            text-align: center;
            background: linear-gradient(135deg, #0066ff, #00f5ff);
            color: #080c14;
            border: none;
            cursor: pointer;
            box-shadow: 0 0 24px rgba(0,245,255,0.25);
            transition: all 0.2s;
            text-decoration: none;
        }
        .retry-btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .logo {
            font-family: 'Rajdhani', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            margin-top: 0;
            display: inline-flex;
            align-items: center;
        }
        .logo span { color: #00f5ff; }
    </style>
</head>
<body>
    <div class="card">
        <x-heroicon-o-signal-slash class="icon w-16 h-16 mx-auto" style="color:var(--cyan);margin-bottom:20px;" />
        <p class="label">{{ __('app.connection_lost') }}</p>
        <h1>{{ __('app.youre_offline') }}</h1>
        <p>{{ __('app.offline_desc') }}</p>

        <div class="divider"></div>

        <p class="cached-label">{{ __('app.available_offline') }}</p>
        <div class="cached-list">
            <div class="cached-item"><x-heroicon-o-truck class="w-4 h-4 mr-2 inline-block" style="color:var(--cyan);" /> {{ __('app.offline_vehicle_data') }}</div>
            <div class="cached-item"><x-heroicon-o-wrench-screwdriver class="w-4 h-4 mr-2 inline-block" style="color:var(--cyan);" /> {{ __('app.offline_svc_history') }}</div>
            <div class="cached-item"><x-heroicon-o-beaker class="w-4 h-4 mr-2 inline-block" style="color:var(--cyan);" /> {{ __('app.offline_fuel_logs') }}</div>
            <div class="cached-item"><x-heroicon-o-light-bulb class="w-4 h-4 mr-2 inline-block" style="color:var(--cyan);" /> {{ __('app.offline_suggestions') }}</div>
        </div>

        <button class="retry-btn" onclick="window.location.reload()">
            <x-heroicon-o-arrow-path class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.retry_connection') }}
        </button>

        <div style="display:flex;align-items:center;justify-content:center;gap:10px;margin-top:20px;">
            <img src="/images/logo.png" alt="AutoMateX" style="height:64px;width:auto;">
            <p class="logo">Auto<span>Mate</span><span style="color:#ff6b00;font-size:1.2em;line-height:1;">X</span></p>
        </div>
    </div>
</body>
</html>