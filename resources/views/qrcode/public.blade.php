<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $vehicle->make }} {{ $vehicle->model }} — AutoMateX History Pass</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:wght@300;400;500&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root { --cyan:#00f5ff; --blue:#0066ff; --orange:#ff6b00; --bg:#080c14; --card:#0d1421; --green:#4ade80; }
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

        /* ── Logo ── */
        .logo-wrap {
            display:flex; align-items:center; gap:10px;
            justify-content:center; margin-bottom:6px; padding-top:8px;
        }
        .logo-text {
            font-family:'Rajdhani',sans-serif; font-size:26px; font-weight:700;
            color:white; letter-spacing:1px; display:inline-flex; align-items:center;
        }
        .tagline {
            text-align:center; font-family:'Share Tech Mono',monospace;
            font-size:10px; letter-spacing:0.2em; color:rgba(0,245,255,0.4);
            text-transform:uppercase; margin-bottom:20px;
        }

        /* ── Public badge strip ── */
        .badge-strip {
            display:flex; align-items:center; justify-content:center; gap:6px;
            padding:8px 14px; border-radius:30px; margin-bottom:20px;
            background:rgba(0,245,255,0.06); border:1px solid rgba(0,245,255,0.2);
            font-size:11px; color:rgba(0,245,255,0.8);
            font-family:'Share Tech Mono',monospace; letter-spacing:0.08em;
            width:fit-content; margin-left:auto; margin-right:auto;
        }
        .badge-dot {
            width:6px; height:6px; border-radius:50%;
            background:var(--cyan); flex-shrink:0;
            box-shadow:0 0 6px var(--cyan);
            animation:blink 2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

        /* ── Card ── */
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

        /* ── Vehicle hero ── */
        .vehicle-photo {
            width:100%; height:180px; object-fit:cover;
            border-radius:14px; margin-bottom:16px;
            border:1px solid rgba(0,245,255,0.1);
        }
        .vehicle-photo-placeholder {
            width:100%; height:140px; border-radius:14px; margin-bottom:16px;
            background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06);
            display:flex; align-items:center; justify-content:center;
        }

        /* ── Stats grid ── */
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        .stat-box {
            background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07);
            border-radius:12px; padding:10px; text-align:center;
        }
        .stat-val { font-family:'Rajdhani',sans-serif; font-size:20px; font-weight:700; }
        .stat-lbl { font-size:11px; color:#64748b; margin-top:2px; }

        /* ── Info rows ── */
        .info-row {
            display:flex; align-items:center; justify-content:space-between;
            padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.04);
            font-size:13px;
        }
        .info-row:last-child { border-bottom:none; padding-bottom:0; }
        .info-key { color:#64748b; }
        .info-val { font-family:'Share Tech Mono',monospace; color:#e2e8f0; font-size:12px; }

        /* ── Fuel badge ── */
        .fuel-badge {
            display:inline-block; padding:3px 10px; border-radius:20px;
            font-family:'Share Tech Mono',monospace; font-size:10px;
            letter-spacing:0.1em; text-transform:uppercase;
        }

        /* ── Service log entries ── */
        .log-entry {
            background:rgba(255,255,255,0.02); border:1px solid rgba(0,245,255,0.08);
            border-radius:14px; padding:14px; margin-bottom:10px;
        }
        .log-entry:last-child { margin-bottom:0; }
        .log-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px; }
        .log-title { font-family:'Rajdhani',sans-serif; font-weight:700; font-size:16px; color:white; }
        .log-sub { font-size:12px; color:#64748b; margin-top:2px; }
        .log-stats { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
        .log-stat { background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.05); border-radius:10px; padding:8px; }
        .log-stat-lbl { font-size:11px; color:#64748b; margin-bottom:2px; }
        .log-stat-val { font-family:'Share Tech Mono',monospace; font-size:13px; font-weight:700; color:white; }
        .type-tag {
            display:inline-block; padding:3px 10px; border-radius:20px;
            font-family:'Share Tech Mono',monospace; font-size:10px;
            letter-spacing:0.1em; text-transform:uppercase; flex-shrink:0;
        }

        /* ── Summary bar ── */
        .summary-bar {
            display:grid; grid-template-columns:1fr 1fr; gap:10px;
            padding:14px; border-radius:14px; margin-bottom:16px;
            background:rgba(0,245,255,0.04); border:1px solid rgba(0,245,255,0.12);
        }
        .summary-item { text-align:center; }
        .summary-val { font-family:'Rajdhani',sans-serif; font-size:22px; font-weight:700; }
        .summary-lbl { font-size:11px; color:#64748b; margin-top:2px; }

        /* ── Empty state ── */
        .empty { text-align:center; padding:32px 20px; color:#64748b; }

        /* ── Footer ── */
        .footer {
            text-align:center; padding:16px;
            border:1px solid rgba(255,255,255,0.06); border-radius:14px;
            background:rgba(255,255,255,0.02);
        }
        .footer-brand { font-family:'Share Tech Mono',monospace; font-size:10px; color:#334155; letter-spacing:0.1em; margin-bottom:4px; }
        .footer-note { font-size:11px; color:#1e293b; font-family:'Share Tech Mono',monospace; letter-spacing:0.06em; }
    </style>
</head>
<body>
<div class="wrap">

    {{-- Logo --}}
    <div class="logo-wrap">
        <img src="/images/logo.png" alt="AutoMateX" style="height:64px;width:auto;flex-shrink:0;">
        <span class="logo-text">Auto<span style="color:var(--cyan);">Mate</span><span style="color:var(--orange);font-size:1.2em;line-height:1;">X</span></span>
    </div>
    <p class="tagline">{{ __('app.pub_vehicle_history') }}</p>

    {{-- Public badge strip --}}
    <div class="badge-strip">
        <span class="badge-dot"></span>
        PUBLIC RECORD &nbsp;·&nbsp; NO LOGIN REQUIRED &nbsp;·&nbsp; VERIFIED
    </div>

    {{-- Vehicle card --}}
    <div class="card">
        <p class="section-label">{{ __('app.pub_vehicle_details') }}</p>

        {{-- Vehicle photo --}}
        @if($vehicle->image)
            <img src="{{ asset('storage/' . $vehicle->image) }}"
                 alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                 class="vehicle-photo">
        @else
            <div class="vehicle-photo-placeholder">
                <svg style="width:48px;height:48px;color:#1e293b;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
            </div>
        @endif

        {{-- Name + year/plate/colour --}}
        <h1 style="font-family:'Rajdhani',sans-serif;font-size:28px;font-weight:700;color:white;margin-bottom:4px;">
            {{ $vehicle->make }} {{ $vehicle->model }}
        </h1>
        <p style="color:#64748b;font-size:13px;margin-bottom:16px;">
            {{ $vehicle->year }}
            @if($vehicle->license_plate) &nbsp;·&nbsp; {{ $vehicle->license_plate }} @endif
            @if($vehicle->color) &nbsp;·&nbsp; {{ $vehicle->color }} @endif
        </p>

        {{-- Stats --}}
        <div class="grid-3" style="margin-bottom:16px;">
            <div class="stat-box">
                <div class="stat-val" style="color:var(--cyan);">{{ number_format($vehicle->mileage) }}</div>
                <div class="stat-lbl">{{ __('app.pub_current_km') }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-val" style="color:var(--green);">{{ $serviceLogs->count() }}</div>
                <div class="stat-lbl">{{ __('app.services') }}</div>
            </div>
            <div class="stat-box">
                @php
                    $fuelColors = ['petrol'=>'#6699ff','diesel'=>'#fb923c','hybrid'=>'#4ade80','electric'=>'#a78bfa'];
                    $fuelColor  = $fuelColors[strtolower($vehicle->fuel_type ?? 'petrol')] ?? '#94a3b8';
                @endphp
                <div class="stat-val" style="color:{{ $fuelColor }};font-size:15px;">{{ strtoupper($vehicle->fuel_type ?? '—') }}</div>
                <div class="stat-lbl">{{ __('app.pub_fuel_type') }}</div>
            </div>
        </div>

        {{-- Extra info rows --}}
        <div>
            @if($vehicle->vin)
            <div class="info-row">
                <span class="info-key">VIN</span>
                <span class="info-val" style="color:rgba(0,245,255,0.7);">{{ $vehicle->vin }}</span>
            </div>
            @endif
            @if($serviceLogs->count() > 0)
            <div class="info-row">
                <span class="info-key">Last Serviced</span>
                <span class="info-val">{{ $serviceLogs->first()->service_date->format('d M Y') }}</span>
            </div>
            @endif
            @if($serviceLogs->sum('cost') > 0)
            <div class="info-row">
                <span class="info-key">Total Service Spend</span>
                <span class="info-val" style="color:var(--green);">LKR {{ number_format($serviceLogs->sum('cost')) }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Service history --}}
    <div class="card">
        <p class="section-label">{{ __('app.pub_svc_history_label', ['count' => $serviceLogs->count()]) }}</p>

        @forelse($serviceLogs as $log)
        <div class="log-entry">
            <div class="log-top">
                <div style="flex:1;min-width:0;margin-right:8px;">
                    <div class="log-title">{{ $log->service_type }}</div>
                    <div class="log-sub">
                        {{ $log->service_date->format('d M Y') }}
                        @if($log->garage_name) &nbsp;·&nbsp; {{ $log->garage_name }} @endif
                    </div>
                </div>
                @if($log->type)
                <span class="type-tag" style="background:rgba(0,245,255,0.08);color:rgba(0,245,255,0.7);border:1px solid rgba(0,245,255,0.15);">
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
                    <div class="log-stat-val" style="color:var(--green);">LKR {{ number_format($log->cost) }}</div>
                </div>
            </div>
            @if($log->notes)
            <div style="margin-top:8px;padding:8px;background:rgba(255,255,255,0.02);border-radius:8px;">
                <p style="font-size:12px;color:#64748b;line-height:1.5;">{{ $log->notes }}</p>
            </div>
            @endif
        </div>
        @empty
        <div class="empty">
            <svg style="width:40px;height:40px;color:#334155;display:block;margin:0 auto 12px;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
            </svg>
            <p style="font-size:13px;">{{ __('app.pub_no_records') }}</p>
        </div>
        @endforelse
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p class="footer-brand">AUTOMATEX &nbsp;·&nbsp; VEHICLE MANAGEMENT SYSTEM</p>
        <p class="footer-note">{{ __('app.pub_verified_footer', ['date' => now()->format('d M Y')]) }}</p>
    </div>

</div>
</body>
</html>
