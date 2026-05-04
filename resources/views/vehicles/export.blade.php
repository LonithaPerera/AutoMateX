<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $vehicle->make }} {{ $vehicle->model }} — Vehicle History Report</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@700;800&family=Share+Tech+Mono&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --orange : #f97316;
        --navy   : #0b1120;
        --navy2  : #0f172a;
        --cyan   : #00f5ff;
        --blue   : #0066ff;
        --slate  : #64748b;
        --border : #e2e8f0;
        --bg     : #f8fafc;
    }

    body {
        background: #e8edf5;
        font-family: 'Inter', Arial, sans-serif;
        color: var(--navy2);
        min-height: 100vh;
        padding: 28px 16px 60px;
        font-size: 14px;
        line-height: 1.5;
    }

    /* ── Action bar (screen only) ─────────────────────────── */
    .action-bar {
        max-width: 780px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        font-family: 'Share Tech Mono', monospace;
        letter-spacing: 0.06em;
        cursor: pointer;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.18s;
        white-space: nowrap;
    }
    .btn:hover { transform: translateY(-1px); }
    .btn-back  { background:#fff; color:#475569; border-color:var(--border); box-shadow:0 1px 3px rgba(0,0,0,0.08); }
    .btn-print { background:var(--orange); color:#fff; border-color:var(--orange); box-shadow:0 4px 12px rgba(249,115,22,0.35); }
    .btn-print:hover { box-shadow:0 6px 18px rgba(249,115,22,0.45); }

    /* ── Report card ──────────────────────────────────────── */
    .report {
        max-width: 780px;
        margin: 0 auto;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.12);
        overflow: hidden;
    }

    /* ── Header ───────────────────────────────────────────── */
    .rpt-header {
        background: var(--navy);
        padding: 28px 40px 24px;
        position: relative;
        overflow: hidden;
    }
    .rpt-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 5% 50%, rgba(249,115,22,0.07) 0%, transparent 60%),
            radial-gradient(ellipse 40% 60% at 90% 20%, rgba(0,245,255,0.08) 0%, transparent 60%);
        pointer-events: none;
    }
    .header-inner {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        z-index: 1;
    }

    /* Brand lockup */
    .brand-lockup {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .brand-lockup img {
        height: 56px;
        width: auto;
        flex-shrink: 0;
    }
    .brand-text {
        font-family: 'Rajdhani', 'Inter', sans-serif;
        font-size: 34px;
        font-weight: 800;
        letter-spacing: 0.5px;
        line-height: 1;
    }
    .bt-auto { color: #ffffff; }
    .bt-mate { color: #00f5ff; }
    .bt-x    { color: #ff6b00; font-size: 1.2em; line-height: 1; }

    .header-tagline {
        font-family: 'Share Tech Mono', monospace;
        font-size: 10px;
        color: #3a5070;
        letter-spacing: 0.12em;
    }

    /* Title block */
    .header-title { text-align: right; }
    .header-title .rpt-word {
        font-size: 26px;
        font-weight: 800;
        color: #fff;
        letter-spacing: 0.06em;
        line-height: 1;
    }
    .header-title .rpt-word span { color: var(--cyan); }
    .header-title .rpt-sub {
        font-family: 'Share Tech Mono', monospace;
        font-size: 13px;
        color: #94a3b8;
        margin-top: 8px;
    }
    .header-title .rpt-meta {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
    }

    /* Orange accent bar */
    .header-accent {
        height: 3px;
        background: linear-gradient(90deg, var(--orange), #fb923c, #fbbf24);
    }

    /* ── Summary strip ────────────────────────────────────── */
    .summary-strip {
        background: #f0f9ff;
        border-bottom: 1px solid #bae6fd;
        padding: 0;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
    }
    .sum-cell {
        padding: 14px 20px;
        border-right: 1px solid #bae6fd;
    }
    .sum-cell:last-child { border-right: none; }
    .sum-lbl {
        font-family: 'Share Tech Mono', monospace;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.12em;
        color: #64748b;
        margin-bottom: 4px;
    }
    .sum-val {
        font-family: 'Share Tech Mono', monospace;
        font-size: 18px;
        font-weight: 700;
        color: #0284c7;
        line-height: 1;
    }

    /* ── Body ─────────────────────────────────────────────── */
    .rpt-body { padding: 32px 40px; }

    /* Section headings */
    .section-hd {
        font-family: 'Share Tech Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.14em;
        color: var(--slate);
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 28px;
    }
    .section-hd:first-child { margin-top: 0; }
    .section-hd::before {
        content: '';
        display: block;
        width: 3px; height: 14px;
        background: var(--orange);
        border-radius: 2px;
        flex-shrink: 0;
    }

    /* ── Specs grid ───────────────────────────────────────── */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 4px;
    }
    .spec-cell {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px 14px;
    }
    .spec-lbl {
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }
    .spec-val {
        font-family: 'Share Tech Mono', monospace;
        font-size: 13px;
        font-weight: 700;
        color: var(--navy2);
    }

    /* ── Notes box ────────────────────────────────────────── */
    .notes-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-left: 3px solid #f59e0b;
        border-radius: 10px;
        padding: 12px 16px;
        margin-top: 12px;
        font-size: 13px;
        color: #78350f;
        line-height: 1.6;
    }
    .notes-lbl {
        font-family: 'Share Tech Mono', monospace;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.12em;
        color: #92400e;
        margin-bottom: 5px;
    }

    /* ── Data tables ──────────────────────────────────────── */
    table.data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    table.data-table thead tr { background: var(--navy); }
    table.data-table thead th {
        font-family: 'Share Tech Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        color: #94a3b8;
        padding: 10px 14px;
        text-align: left;
        border: none;
    }
    table.data-table thead th:first-child { border-radius: 8px 0 0 8px; }
    table.data-table thead th:last-child  { border-radius: 0 8px 8px 0; }
    table.data-table tbody tr { border-bottom: 1px solid var(--bg); }
    table.data-table tbody td {
        padding: 11px 14px;
        color: #334155;
        border: none;
        vertical-align: middle;
    }
    table.data-table tbody tr:nth-child(even) td { background: var(--bg); }

    .tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .tag-maintenance { background: #dbeafe; color: #1d4ed8; }
    .tag-repair      { background: #fee2e2; color: #dc2626; }
    .tag-inspection  { background: #d1fae5; color: #065f46; }

    .empty-row td {
        padding: 20px 14px;
        color: #94a3b8;
        font-family: 'Share Tech Mono', monospace;
        font-size: 11px;
        text-align: center;
    }

    /* ── Footer ───────────────────────────────────────────── */
    .rpt-footer {
        background: var(--bg);
        border-top: 1px solid var(--border);
        border-radius: 0 0 16px 16px;
        padding: 14px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .footer-left .fl-brand {
        font-family: 'Share Tech Mono', monospace;
        font-size: 11px;
        color: #94a3b8;
        letter-spacing: 0.05em;
    }
    .footer-left .fl-note {
        font-size: 10px;
        color: #cbd5e1;
        margin-top: 2px;
        font-style: italic;
    }
    .footer-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        color: #0284c7;
        border-radius: 20px;
        padding: 5px 14px;
        font-family: 'Share Tech Mono', monospace;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.08em;
    }
    .footer-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #0284c7;
        display: inline-block;
    }

    /* ── Print ────────────────────────────────────────────── */
    @media print {
        @page { size: A4; margin: 10mm; }
        body {
            background: #fff;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .action-bar { display: none !important; }
        .report {
            box-shadow: none;
            border-radius: 0;
            max-width: 100%;
        }
    }

    /* ── Mobile ───────────────────────────────────────────── */
    @media (max-width: 640px) {
        .rpt-header   { padding: 22px 24px 20px; }
        .rpt-body     { padding: 24px; }
        .rpt-footer   { padding: 14px 24px; }
        .summary-strip { grid-template-columns: repeat(2,1fr); }
        .specs-grid   { grid-template-columns: repeat(2,1fr); }
        .header-title .rpt-word { font-size: 18px; }
    }
</style>
</head>
<body>

{{-- ── Action bar (screen only) ───────────────────────────── --}}
<div class="action-bar">
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        BACK TO VEHICLE
    </a>
    <button onclick="window.print()" class="btn btn-print">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
        </svg>
        SAVE AS PDF
    </button>
</div>

@php
    $totalSvcCost  = $serviceLogs->sum('cost');
    $totalFuelCost = $fuelLogs->sum('cost');
    $totalLiters   = $fuelLogs->sum('liters');
    $avgKml        = $fuelLogs->whereNotNull('km_per_liter')->avg('km_per_liter');
@endphp

{{-- ── Report ───────────────────────────────────────────────── --}}
<div class="report">

    {{-- Header --}}
    <div class="rpt-header">
        <div class="header-inner">
            <div>
                <div class="brand-lockup">
                    <img src="/images/logo.png" alt="AutoMateX">
                    <span class="brand-text">
                        <span class="bt-auto">Auto</span><span class="bt-mate">Mate</span><span class="bt-x">X</span>
                    </span>
                </div>
                <div class="header-tagline">// VEHICLE MANAGEMENT SYSTEM</div>
            </div>
            <div class="header-title">
                <div class="rpt-word">VEHICLE&nbsp;<span>HISTORY</span></div>
                <div class="rpt-sub">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                <div class="rpt-meta">Generated: {{ now()->format('d M Y, h:i A') }}</div>
            </div>
        </div>
    </div>
    <div class="header-accent"></div>

    {{-- Summary strip --}}
    <div class="summary-strip">
        <div class="sum-cell">
            <div class="sum-lbl">Service Records</div>
            <div class="sum-val">{{ $serviceLogs->count() }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-lbl">Fuel Fill-ups</div>
            <div class="sum-val">{{ $fuelLogs->count() }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-lbl">Total Spend</div>
            <div class="sum-val" style="font-size:13px;">LKR {{ number_format($totalSvcCost + $totalFuelCost) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-lbl">Avg Efficiency</div>
            <div class="sum-val">{{ $avgKml ? number_format($avgKml, 1).' km/L' : '—' }}</div>
        </div>
    </div>

    {{-- Body --}}
    <div class="rpt-body">

        {{-- Vehicle Specifications --}}
        <p class="section-hd">VEHICLE SPECIFICATIONS</p>
        <div class="specs-grid">
            <div class="spec-cell"><div class="spec-lbl">Make</div><div class="spec-val">{{ $vehicle->make }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">Model</div><div class="spec-val">{{ $vehicle->model }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">Year</div><div class="spec-val">{{ $vehicle->year }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">Odometer</div><div class="spec-val">{{ number_format($vehicle->mileage) }} km</div></div>
            <div class="spec-cell"><div class="spec-lbl">Fuel Type</div><div class="spec-val">{{ ucfirst($vehicle->fuel_type) }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">Colour</div><div class="spec-val">{{ $vehicle->color ?? '—' }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">License Plate</div><div class="spec-val">{{ $vehicle->license_plate ?? '—' }}</div></div>
            <div class="spec-cell"><div class="spec-lbl">VIN</div><div class="spec-val" style="font-size:11px;">{{ $vehicle->vin ?? '—' }}</div></div>
        </div>

        @if($vehicle->notes)
        <div class="notes-box">
            <div class="notes-lbl">VEHICLE NOTES</div>
            {{ $vehicle->notes }}
        </div>
        @endif

        {{-- Service History --}}
        <p class="section-hd" style="margin-top:28px;">
            SERVICE HISTORY
            <span style="color:#94a3b8;font-weight:400;">{{ $serviceLogs->count() }} records · LKR {{ number_format($totalSvcCost) }}</span>
        </p>
        @if($serviceLogs->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Service Type</th>
                    <th>Category</th>
                    <th>Mileage</th>
                    <th>Cost (LKR)</th>
                    <th>Garage</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceLogs as $s)
                <tr>
                    <td>{{ $s->service_date->format('d M Y') }}</td>
                    <td style="font-weight:600;color:var(--navy2);">{{ $s->service_type }}</td>
                    <td><span class="tag tag-{{ $s->type }}">{{ $s->type }}</span></td>
                    <td>{{ number_format($s->mileage_at_service) }} km</td>
                    <td style="font-family:'Share Tech Mono',monospace;font-weight:700;">{{ number_format($s->cost) }}</td>
                    <td>{{ $s->garage_name ?? '—' }}</td>
                    <td style="color:#94a3b8;font-size:10px;max-width:100px;">{{ Str::limit($s->notes, 40) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <table class="data-table"><tbody><tr class="empty-row"><td colspan="7">No service records found.</td></tr></tbody></table>
        @endif

        {{-- Fuel History --}}
        <p class="section-hd" style="margin-top:28px;">
            FUEL HISTORY
            <span style="color:#94a3b8;font-weight:400;">{{ $fuelLogs->count() }} fill-ups · {{ number_format($totalLiters, 1) }} L · LKR {{ number_format($totalFuelCost) }}</span>
        </p>
        @if($fuelLogs->count())
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Odometer</th>
                    <th>Litres</th>
                    <th>Cost (LKR)</th>
                    <th>km/L</th>
                    <th>Fuel Station</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuelLogs as $f)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($f->date)->format('d M Y') }}</td>
                    <td>{{ number_format($f->km_reading) }} km</td>
                    <td>{{ $f->liters }} L</td>
                    <td style="font-family:'Share Tech Mono',monospace;font-weight:700;">{{ number_format($f->cost) }}</td>
                    <td>{{ $f->km_per_liter ? number_format($f->km_per_liter, 1) : '—' }}</td>
                    <td>{{ $f->fuel_station ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <table class="data-table"><tbody><tr class="empty-row"><td colspan="6">No fuel records found.</td></tr></tbody></table>
        @endif

    </div>{{-- /rpt-body --}}

    {{-- Footer --}}
    <div class="rpt-footer">
        <div class="footer-left">
            <div class="fl-brand">AutoMateX · Vehicle History Report · © {{ date('Y') }}</div>
            <div class="fl-note">This is a computer-generated report. All figures are based on user-entered data.</div>
        </div>
        <div class="footer-badge">
            <span class="footer-dot"></span>
            {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->license_plate ?? $vehicle->vin ?? 'N/A' }}
        </div>
    </div>

</div>{{-- /report --}}

<script>
window.addEventListener('load', function () {
    setTimeout(function () { window.print(); }, 500);
});
</script>
</body>
</html>
