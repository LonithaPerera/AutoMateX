<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Service History — {{ $vehicle->make }} {{ $vehicle->model }}</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #1e293b;
        background: #ffffff;
        padding: 32px 36px;
    }

    /* ── Header ── */
    .header {
        display: table;
        width: 100%;
        margin-bottom: 28px;
        border-bottom: 2px solid #0066ff;
        padding-bottom: 16px;
    }
    .header-left  { display: table-cell; vertical-align: middle; width: 70%; }
    .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 30%; }

    .brand {
        font-size: 22px;
        font-weight: 700;
        color: #0066ff;
        letter-spacing: 1px;
    }
    .brand span { color: #00c8d4; }
    .report-title {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .generated {
        font-size: 9px;
        color: #94a3b8;
    }

    /* ── Vehicle info card ── */
    .vehicle-card {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 22px;
        display: table;
        width: 100%;
    }
    .vehicle-card-left  { display: table-cell; width: 65%; vertical-align: top; }
    .vehicle-card-right { display: table-cell; width: 35%; vertical-align: top; text-align: right; }

    .vehicle-name {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
    }
    .vehicle-sub {
        font-size: 10px;
        color: #475569;
        margin-top: 3px;
    }
    .vehicle-meta {
        font-size: 10px;
        color: #334155;
        margin-top: 8px;
        line-height: 1.7;
    }
    .vehicle-meta strong { color: #0f172a; }

    /* ── Summary stats ── */
    .stats-row {
        display: table;
        width: 100%;
        margin-bottom: 22px;
        border-collapse: separate;
        border-spacing: 8px 0;
    }
    .stat-cell {
        display: table-cell;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        text-align: center;
        width: 25%;
    }
    .stat-value {
        font-size: 18px;
        font-weight: 700;
        color: #0066ff;
    }
    .stat-label {
        font-size: 9px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    /* ── Section heading ── */
    .section-heading {
        font-size: 9px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #e2e8f0;
    }

    /* ── Table ── */
    table.records {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    table.records thead tr {
        background: #0066ff;
        color: #ffffff;
    }
    table.records thead th {
        padding: 8px 10px;
        text-align: left;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    table.records tbody tr {
        border-bottom: 1px solid #f1f5f9;
    }
    table.records tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    table.records tbody td {
        padding: 9px 10px;
        font-size: 10px;
        color: #334155;
        vertical-align: top;
    }
    .badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 20px;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .badge-maintenance { background: #dbeafe; color: #1d4ed8; }
    .badge-repair      { background: #fce7f3; color: #be185d; }
    .badge-inspection  { background: #dcfce7; color: #15803d; }

    /* ── Cost row ── */
    .total-row {
        display: table;
        width: 100%;
        margin-top: 4px;
    }
    .total-cell {
        display: table-cell;
        text-align: right;
        padding: 10px 10px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 6px;
        width: 100%;
    }
    .total-label { font-size: 10px; color: #475569; }
    .total-amount { font-size: 16px; font-weight: 700; color: #0066ff; margin-top: 2px; }

    /* ── Footer ── */
    .footer {
        margin-top: 32px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        font-size: 8px;
        color: #94a3b8;
    }
</style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="header-left">
            <div class="brand">Auto<span>MateX</span></div>
            <div class="report-title">Service History Report</div>
        </div>
        <div class="header-right">
            <div class="generated">Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }}</div>
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
                @if($vehicle->vin)
                    <strong>VIN:</strong> {{ $vehicle->vin }}<br>
                @endif
                <strong>Mileage:</strong> {{ number_format($vehicle->mileage) }} km
            </div>
        </div>
        <div class="vehicle-card-right">
            <div class="vehicle-meta" style="text-align:right;">
                <strong>Owner:</strong> {{ $vehicle->user->name }}<br>
                <strong>Records:</strong> {{ $serviceLogs->count() }}<br>
                <strong>Total Spent:</strong> LKR {{ number_format($serviceLogs->sum('cost')) }}
            </div>
        </div>
    </div>

    {{-- Summary stats --}}
    @php
        $maintenance = $serviceLogs->where('type','maintenance')->count();
        $repairs     = $serviceLogs->where('type','repair')->count();
        $inspections = $serviceLogs->where('type','inspection')->count();
        $avgCost     = $serviceLogs->count() > 0 ? $serviceLogs->avg('cost') : 0;
    @endphp
    <div class="stats-row">
        <div class="stat-cell">
            <div class="stat-value">{{ $serviceLogs->count() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
        <div class="stat-cell">
            <div class="stat-value">{{ $maintenance }}</div>
            <div class="stat-label">Maintenance</div>
        </div>
        <div class="stat-cell">
            <div class="stat-value">{{ $repairs }}</div>
            <div class="stat-label">Repairs</div>
        </div>
        <div class="stat-cell">
            <div class="stat-value">{{ number_format($avgCost) }}</div>
            <div class="stat-label">Avg Cost (LKR)</div>
        </div>
    </div>

    {{-- Service log table --}}
    <div class="section-heading">// Service Records</div>

    @if($serviceLogs->isEmpty())
        <p style="color:#64748b;font-size:11px;padding:12px 0;">No service records found for this vehicle.</p>
    @else
        <table class="records">
            <thead>
                <tr>
                    <th style="width:12%;">Date</th>
                    <th style="width:28%;">Service</th>
                    <th style="width:12%;">Mileage</th>
                    <th style="width:14%;">Cost (LKR)</th>
                    <th style="width:8%;">Type</th>
                    <th style="width:16%;">Garage</th>
                    <th style="width:10%;">Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($serviceLogs as $log)
                <tr>
                    <td>{{ $log->service_date->format('d M Y') }}</td>
                    <td style="font-weight:600;color:#0f172a;">{{ $log->service_type }}</td>
                    <td>{{ number_format($log->mileage_at_service) }} km</td>
                    <td style="font-weight:600;color:#0066ff;">{{ number_format($log->cost) }}</td>
                    <td>
                        <span class="badge badge-{{ $log->type }}">{{ $log->type }}</span>
                    </td>
                    <td>{{ $log->garage_name ?? '—' }}</td>
                    <td style="color:#64748b;font-size:9px;">{{ $log->notes ? \Illuminate\Support\Str::limit($log->notes, 40) : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total --}}
        <div class="total-row">
            <div class="total-cell">
                <div class="total-label">Total Maintenance Spend</div>
                <div class="total-amount">LKR {{ number_format($serviceLogs->sum('cost')) }}</div>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        AutoMateX — Smart Vehicle Management System &nbsp;·&nbsp;
        {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->year }}) &nbsp;·&nbsp;
        Report generated {{ \Carbon\Carbon::now()->format('d M Y') }}
    </div>

</body>
</html>
