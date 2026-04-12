<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AutoMateX — Vehicle History Pass</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root { --cyan:#00f5ff; --blue:#0066ff; --orange:#ff6b00; --bg:#080c14; --card:#0d1421; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            background:var(--bg); font-family:'DM Sans',sans-serif;
            color:#e2e8f0; min-height:100vh; padding:24px 16px 48px;
        }
        body::before {
            content:''; position:fixed; inset:0;
            background-image:
                linear-gradient(rgba(0,245,255,0.03) 1px,transparent 1px),
                linear-gradient(90deg,rgba(0,245,255,0.03) 1px,transparent 1px);
            background-size:40px 40px; pointer-events:none; z-index:0;
        }
        .wrap { max-width:480px; margin:0 auto; position:relative; z-index:1; }
        .logo-wrap {
            display:flex; align-items:center; gap:10px;
            justify-content:center; margin-bottom:6px; padding-top:8px;
        }
        .logo-icon {
            width:36px; height:36px; border-radius:10px;
            background:linear-gradient(135deg,#0066ff,#00f5ff);
            display:flex; align-items:center; justify-content:center;
            box-shadow:0 0 14px rgba(0,245,255,0.4);
        }
        .logo-text { font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700; color:white; letter-spacing:1px; display:inline-flex; align-items:center; }
        .logo-text span { color:var(--cyan); }
        .tagline {
            text-align:center; font-family:'Share Tech Mono',monospace;
            font-size:10px; letter-spacing:0.2em; color:rgba(0,245,255,0.4);
            text-transform:uppercase; margin-bottom:24px;
        }
        .card {
            background:rgba(13,20,33,0.85); backdrop-filter:blur(20px);
            border:1px solid rgba(0,245,255,0.12); border-radius:20px;
            padding:20px; margin-bottom:16px;
        }
        .section-label {
            font-family:'Share Tech Mono',monospace; font-size:10px;
            letter-spacing:0.15em; text-transform:uppercase;
            color:rgba(0,245,255,0.4); margin-bottom:12px;
        }
        .heading { font-family:'Rajdhani',sans-serif; }
        .mono { font-family:'Share Tech Mono',monospace; }
        .tag {
            display:inline-block; padding:3px 10px; border-radius:20px;
            font-family:'Share Tech Mono',monospace; font-size:10px;
            letter-spacing:0.1em; text-transform:uppercase;
        }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
        .stat-box {
            background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
            border-radius:12px; padding:10px; text-align:center;
        }
        .stat-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; }
        .stat-lbl { font-size:11px; color:#64748b; margin-top:2px; }
        .log-entry {
            background:rgba(255,255,255,0.02); border:1px solid rgba(0,245,255,0.08);
            border-radius:14px; padding:14px; margin-bottom:10px;
        }
        .log-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
        .log-title { font-family:'Rajdhani',sans-serif; font-weight:700; font-size:16px; color:white; }
        .log-sub { font-size:12px; color:#64748b; margin-top:2px; }
        .log-stats { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
        .log-stat { background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:8px; }
        .log-stat-lbl { font-size:11px; color:#64748b; margin-bottom:2px; }
        .log-stat-val { font-family:'Share Tech Mono',monospace; font-size:13px; font-weight:700; color:white; }
        .badge-public {
            display:flex; align-items:center; justify-content:center; gap:8px;
            padding:10px; border-radius:12px; margin-bottom:16px;
            background:rgba(0,245,255,0.06); border:1px solid rgba(0,245,255,0.15);
            font-size:12px; color:rgba(0,245,255,0.7);
            font-family:'Share Tech Mono',monospace; letter-spacing:0.05em;
        }
        .empty { text-align:center; padding:32px 20px; color:#64748b; }
        .empty-icon { font-size:40px; margin-bottom:12px; }
    </style>
</head>
<body>
<div class="wrap">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="/images/logo.png" alt="AutoMateX" style="height:64px;width:auto;">
        <div class="logo-text">Auto<span>Mate</span><span style="color:#ff6b00;font-size:1.2em;line-height:1;">X</span></div>
    </div>
    <p class="tagline">{{ __('app.pub_vehicle_history') }}</p>

    {{-- Public badge --}}
    <div class="badge-public">
        <x-heroicon-o-lock-open class="w-4 h-4" style="color:rgba(0,245,255,0.7);" /> {{ __('app.pub_badge_text') }}
    </div>

    {{-- Vehicle info --}}
    <div class="card">
        <p class="section-label">{{ __('app.pub_vehicle_details') }}</p>
        <h1 class="heading" style="font-size:28px;font-weight:700;color:white;margin-bottom:4px;">
            {{ $vehicle->make }} {{ $vehicle->model }}
        </h1>
        <p style="color:#64748b;font-size:13px;margin-bottom:16px;">
            {{ $vehicle->year }}
            @if($vehicle->license_plate) · {{ $vehicle->license_plate }} @endif
            @if($vehicle->color) · {{ $vehicle->color }} @endif
        </p>

        <div class="grid-3">
            <div class="stat-box">
                <div class="stat-val" style="color:var(--cyan);">{{ number_format($vehicle->mileage) }}</div>
                <div class="stat-lbl">{{ __('app.pub_current_km') }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color:#4ade80;">{{ $serviceLogs->count() }}</div>
                <div class="stat-lbl">{{ __('app.services') }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color:#6699ff;">{{ strtoupper($vehicle->fuel_type ?? '—') }}</div>
                <div class="stat-lbl">{{ __('app.pub_fuel_type') }}</div>
            </div>
        </div>
    </div>

    {{-- Service history --}}
    <div class="card">
        <p class="section-label">{{ __('app.pub_svc_history_label', ['count' => $serviceLogs->count()]) }}</p>

        @forelse($serviceLogs as $log)
        <div class="log-entry">
            <div class="log-top">
                <div>
                    <div class="log-title">{{ $log->service_type }}</div>
                    <div class="log-sub">
                        {{ $log->service_date->format('d M Y') }}
                        @if($log->garage_name) · {{ $log->garage_name }} @endif
                    </div>
                </div>
                @if($log->type)
                <span class="tag" style="background:rgba(0,245,255,0.08);color:rgba(0,245,255,0.7);border:1px solid rgba(0,245,255,0.15);">
                    {{ strtoupper($log->type) }}
                </span>
                @endif
            </div>
            <div class="log-stats">
                <div class="log-stat">
                    <div class="log-stat-lbl">{{ __('app.mileage') }}</div>
                    <div class="log-stat-val">{{ number_format($log->mileage_at_service) }} km</div>
                </div>
                <div class="log-stat">
                    <div class="log-stat-lbl">{{ __('app.cost') }}</div>
                    <div class="log-stat-val" style="color:#4ade80;">LKR {{ number_format($log->cost) }}</div>
                </div>
            </div>
            @if($log->notes)
            <div style="margin-top:8px;padding:8px;background:rgba(255,255,255,0.02);border-radius:8px;">
                <p style="font-size:12px;color:#64748b;">{{ $log->notes }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="empty">
            <x-heroicon-o-wrench-screwdriver class="w-10 h-10 mx-auto mb-3" style="color:#64748b;" />
            <p>{{ __('app.pub_no_records') }}</p>
        </div>
        @endforelse
    </div>

    {{-- Footer --}}
    <p style="text-align:center;font-size:11px;color:#334155;font-family:'Share Tech Mono',monospace;letter-spacing:0.1em;">
        {{ __('app.pub_verified_footer', ['date' => now()->format('d M Y')]) }}
    </p>

</div>
</body>
</html>