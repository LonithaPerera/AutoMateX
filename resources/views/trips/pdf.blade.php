<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Trip Log — {{ $vehicle->make }} {{ $vehicle->model }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #1e293b;
        background: #ffffff;
        padding: 32px 36px;
    }
    .header {
        display: table;
        width: 100%;
        margin-bottom: 28px;
        border-bottom: 2px solid #0066ff;
        padding-bottom: 16px;
    }
    .header-left  { display: table-cell; vertical-align: middle; width: 70%; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 30%; }
    .brand { font-size: 22px; font-weight: 700; color: #0066ff; letter-spacing: 1px; }
    .brand span { color: #00c8d4; }
    .report-title { font-size: 11px; color: #64748b; margin-top: 2px; letter-spacing: 0.5px; text-transform: uppercase; }
    .generated { font-size: 9px; color: #94a3b8; }

    .vehicle-card {
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 22px;
        display: table;
        width: 100%;
    }
    .vehicle-card-left  { display: table-cell; width: 65%; vertical-align: top; }
    .vehicle-card-right { display: table-cell; width: 35%; vertical-align: top; text-align: right; }
    .vehicle-name { font-size: 18px; font-weight: 700; color: #0f172a; }
    .vehicle-sub  { font-size: 10px; color: #475569; margin-top: 3px; }
    .vehicle-meta { font-size: 10px; color: #334155; margin-top: 8px; line-height: 1.7; }
    .vehicle-meta strong { color: #0f172a; }

    .stats-row { display: table; width: 100%; margin-bottom: 22px; border-spacing: 8px; }
    .stat-cell {
        display: table-cell;
        width: 25%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px 12px;
        text-align: center;
    }
    .stat-val { font-size: 16px; font-weight: 700; color: #7c3aed; }
    .stat-lbl { font-size: 8px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px; }

    .section-title {
        font-size: 9px;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e2e8f0;
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        background: #7c3aed;
        color: #ffffff;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 7px 8px;
        text-align: left;
    }
    td {
        padding: 7px 8px;
        font-size: 10px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }
    tr:nth-child(even) td { background: #faf5ff; }
    .mono { font-family: DejaVu Sans Mono, monospace; }
    .purple { color: #7c3aed; font-weight: 700; }
    .green  { color: #16a34a; }
    .badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-business { background: #dbeafe; color: #1d4ed8; }
    .badge-personal { background: #dcfce7; color: #15803d; }
    .badge-other    { background: #f3f4f6; color: #6b7280; }
    .total-row td { background: #f5f3ff; font-weight: 700; border-top: 2px solid #7c3aed; color: #0f172a; }

    .footer {
        margin-top: 28px;
        padding-top: 12px;
        border-top: 1px solid #e2e8f0;
        display: table;
        width: 100%;
    }
    .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; }
    .footer-right { display: table-cell; text-align: right; font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <div class="header-left">
        <div class="brand">Auto<span>MateX</span></div>
        <div class="report-title">Trip Log Report</div>
    </div>
    <div class="header-right">
        <div class="generated">Generated {{ now()->format('d M Y') }}</div>
    </div>
</div>

{{-- Vehicle info --}}
<div class="vehicle-card">
    <div class="vehicle-card-left">
        <div class="vehicle-name">{{ $vehicle->make }} {{ $vehicle->model }}</div>
        <div class="vehicle-sub">{{ $vehicle->year }} &nbsp;·&nbsp; {{ strtoupper(__('app.fuel_' . $vehicle->fuel_type)) }}</div>
        <div class="vehicle-meta">
            @if($vehicle->license_plate)
                <strong>Plate:</strong> {{ $vehicle->license_plate }}<br>
            @endif
            <strong>Current Mileage:</strong> {{ number_format($vehicle->mileage) }} km
        </div>
    </div>
    <div class="vehicle-card-right">
        <div class="vehicle-meta" style="text-align:right;">
            <strong>Owner:</strong><br>
            {{ $vehicle->user->name }}<br>
            <span style="color:#94a3b8;">{{ $vehicle->user->email }}</span>
        </div>
    </div>
</div>

{{-- Summary stats --}}
<div class="stats-row">
    <div class="stat-cell">
        <div class="stat-val">{{ $tripLogs->count() }}</div>
        <div class="stat-lbl">Trips</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val">{{ number_format($totalKm) }} km</div>
        <div class="stat-lbl">Total Distance</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val" style="color:#1d4ed8;">{{ number_format($businessKm) }} km</div>
        <div class="stat-lbl">Business km</div>
    </div>
    <div class="stat-cell">
        <div class="stat-val" style="color:#15803d;">{{ number_format($personalKm) }} km</div>
        <div class="stat-lbl">Personal km</div>
    </div>
</div>

{{-- Trip table --}}
<div class="section-title">// TRIP RECORDS</div>

@if($tripLogs->isEmpty())
    <p style="color:#94a3b8;font-size:10px;text-align:center;padding:16px 0;">No trip records found.</p>
@else
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Start km</th>
            <th>End km</th>
            <th>Distance</th>
            <th>Purpose</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tripLogs as $trip)
        <tr>
            <td class="mono">{{ $trip->trip_date->format('d M Y') }}</td>
            <td class="mono">{{ number_format($trip->start_km) }}</td>
            <td class="mono">{{ number_format($trip->end_km) }}</td>
            <td class="mono purple">{{ number_format($trip->distance) }} km</td>
            <td>
                @php $p = $trip->purpose; @endphp
                <span class="badge badge-{{ $p }}">{{ ucfirst($p) }}</span>
            </td>
            <td style="color:#94a3b8;">{{ $trip->notes ?? '—' }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3">Total</td>
            <td class="mono" style="color:#7c3aed;">{{ number_format($totalKm) }} km</td>
            <td colspan="2" style="color:#64748b;font-weight:400;">
                Business: {{ number_format($businessKm) }} km &nbsp;·&nbsp; Personal: {{ number_format($personalKm) }} km
            </td>
        </tr>
    </tbody>
</table>
@endif

{{-- Footer --}}
<div class="footer">
    <div class="footer-left">{{ $vehicle->make }} {{ $vehicle->model }} {{ $vehicle->year }} · Trip Log</div>
    <div class="footer-right">VERIFIED BY AUTOMATEX · {{ now()->format('d M Y') }}</div>
</div>

</body>
</html>
