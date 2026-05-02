<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $booking->invoice_number ?? 'Invoice' }} — AutoMateX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@700&family=Share+Tech+Mono&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange : #f97316;
            --navy   : #0b1120;
            --navy2  : #0f172a;
            --green  : #16a34a;
            --green-l: #dcfce7;
            --green-b: #86efac;
            --slate  : #64748b;
            --border : #e2e8f0;
            --bg     : #f8fafc;
        }

        body {
            background: #e8edf5;
            font-family: 'Inter', sans-serif;
            color: var(--navy2);
            min-height: 100vh;
            padding: 28px 16px 60px;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Action bar (hidden on print) ──────────────── */
        .action-bar {
            max-width: 780px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .action-group { display: flex; gap: 10px; align-items: center; }

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
        .btn svg { flex-shrink: 0; }
        .btn:hover { transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }

        .btn-back {
            background: #fff;
            color: #475569;
            border-color: var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .btn-copy {
            background: #fff;
            color: #0066ff;
            border-color: #bfdbfe;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .btn-print {
            background: var(--orange);
            color: #fff;
            border-color: var(--orange);
            box-shadow: 0 4px 12px rgba(249,115,22,0.35);
        }
        .btn-print:hover { box-shadow: 0 6px 18px rgba(249,115,22,0.45); }

        .toast {
            display: none;
            background: var(--navy2);
            color: #4ade80;
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            padding: 7px 12px;
            border-radius: 8px;
            letter-spacing: 0.05em;
        }

        /* ── Invoice page ──────────────────────────────── */
        .invoice {
            max-width: 780px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        /* ── Header ────────────────────────────────────── */
        .inv-header {
            background: var(--navy);
            padding: 28px 40px 24px;
            position: relative;
            overflow: hidden;
        }

        /* subtle bg texture */
        .inv-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 80% at 5% 50%, rgba(249,115,22,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 90% 20%, rgba(0,102,255,0.08) 0%, transparent 60%);
            pointer-events: none;
        }

        .header-inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }

        /* Logo + text lockup */
        .header-brand {}

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

        /* Invoice title block */
        .header-title { text-align: right; }
        .header-title .inv-word {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.06em;
            line-height: 1;
        }
        .header-title .inv-word span { color: var(--orange); }
        .header-title .inv-num {
            font-family: 'Share Tech Mono', monospace;
            font-size: 14px;
            color: #94a3b8;
            margin-top: 8px;
            letter-spacing: 0.05em;
        }
        .header-title .inv-date {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /* PAID stamp */
        .paid-stamp {
            position: absolute;
            top: 22px;
            right: 220px;
            transform: rotate(-18deg);
            border: 2.5px solid rgba(74,222,128,0.55);
            border-radius: 6px;
            padding: 4px 12px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 20px;
            color: rgba(74,222,128,0.55);
            letter-spacing: 0.25em;
            pointer-events: none;
            z-index: 2;
            text-shadow: 0 0 10px rgba(74,222,128,0.25);
        }

        /* Orange accent bar */
        .header-accent {
            height: 3px;
            background: linear-gradient(90deg, var(--orange), #fb923c, #fbbf24);
        }

        /* ── Status strip ──────────────────────────────── */
        .status-strip {
            background: #f0fdf4;
            border-bottom: 1px solid #d1fae5;
            padding: 10px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }
        .strip-ref {
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            color: var(--slate);
            letter-spacing: 0.03em;
        }
        .strip-ref strong { color: var(--navy2); }

        .paid-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--green-l);
            border: 1px solid var(--green-b);
            color: var(--green);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-family: 'Share Tech Mono', monospace;
        }
        .paid-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #22c55e;
            display: inline-block;
            flex-shrink: 0;
        }

        /* ── Body ──────────────────────────────────────── */
        .inv-body { padding: 36px 40px; }

        /* FROM / TO */
        .parties {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 36px;
        }

        .party-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 20px;
            background: var(--bg);
            position: relative;
        }
        .party-box.from { border-top: 3px solid var(--orange); }
        .party-box.to   { border-top: 3px solid #0066ff; }

        .party-lbl {
            font-family: 'Share Tech Mono', monospace;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.16em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .party-box.from .party-lbl { color: var(--orange); }
        .party-box.to   .party-lbl { color: #0066ff; }

        .party-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy2);
            margin-bottom: 6px;
            line-height: 1.3;
        }
        .party-row {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            font-size: 12px;
            color: var(--slate);
            margin-bottom: 3px;
            line-height: 1.4;
        }
        .party-row svg { flex-shrink: 0; margin-top: 1px; }

        .spec-tag {
            display: inline-block;
            font-size: 10px;
            font-family: 'Share Tech Mono', monospace;
            background: #fff7ed;
            color: var(--orange);
            border: 1px solid #fed7aa;
            border-radius: 4px;
            padding: 2px 8px;
            margin-top: 8px;
        }

        /* ── Section title ─────────────────────────────── */
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
        }
        .section-hd::before {
            content: '';
            display: block;
            width: 3px; height: 14px;
            background: var(--orange);
            border-radius: 2px;
            flex-shrink: 0;
        }

        /* ── Appointment detail cells ──────────────────── */
        .appt-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 36px;
        }
        .appt-cell {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
        }
        .appt-cell .a-lbl {
            font-size: 10px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .appt-cell .a-val {
            font-family: 'Share Tech Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            color: var(--navy2);
        }

        /* ── Line items table ──────────────────────────── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .items-table thead tr {
            background: var(--navy);
        }
        .items-table th {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #94a3b8;
            padding: 11px 16px;
            text-align: left;
            border: none;
        }
        .items-table th:first-child { border-radius: 8px 0 0 8px; width: 46px; }
        .items-table th:last-child  { border-radius: 0 8px 8px 0; text-align: right; }
        .items-table th.center { text-align: center; width: 70px; }
        .items-table th.right  { text-align: right; }

        .items-table tbody tr { border-bottom: 1px solid var(--bg); }
        .items-table td {
            padding: 16px 16px;
            vertical-align: top;
            color: #334155;
            border: none;
        }
        .items-table td:first-child {
            font-family: 'Share Tech Mono', monospace;
            font-size: 12px;
            color: #94a3b8;
        }
        .items-table td.center { text-align: center; }
        .items-table td.right  { text-align: right; font-weight: 700; color: var(--navy2); font-family: 'Share Tech Mono', monospace; }

        .item-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy2);
            margin-bottom: 4px;
        }
        .item-notes {
            font-size: 12px;
            color: var(--slate);
            line-height: 1.5;
        }

        /* ── Totals ────────────────────────────────────── */
        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
            margin-bottom: 32px;
        }
        .totals-box {
            width: 280px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            border-bottom: 1px solid var(--border);
        }
        .totals-row:last-of-type { border-bottom: none; }
        .totals-row .tl { font-size: 12px; color: var(--slate); }
        .totals-row .tv {
            font-family: 'Share Tech Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            color: var(--navy2);
        }
        .totals-row .tv.muted { color: #94a3b8; }

        .total-final {
            background: var(--navy);
            border-radius: 10px;
            padding: 16px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }
        .total-final::after {
            content: 'PAID';
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-family: 'Share Tech Mono', monospace;
            font-size: 44px;
            font-weight: 800;
            color: rgba(74,222,128,0.05);
            letter-spacing: 0.1em;
            pointer-events: none;
            user-select: none;
        }
        .total-final .tf-label {
            font-family: 'Share Tech Mono', monospace;
            font-size: 11px;
            color: #64748b;
            letter-spacing: 0.1em;
        }
        .total-final .tf-amount {
            font-family: 'Share Tech Mono', monospace;
            font-size: 24px;
            font-weight: 700;
            color: #4ade80;
            letter-spacing: 0.02em;
        }
        .total-final .tf-currency {
            font-size: 13px;
            color: rgba(74,222,128,0.6);
            margin-right: 3px;
        }

        /* ── Customer notes ────────────────────────────── */
        .notes-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 3px solid #f59e0b;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 28px;
        }
        .notes-lbl {
            font-family: 'Share Tech Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: #92400e;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .notes-box p { font-size: 13px; color: #78350f; line-height: 1.6; }

        /* ── Thank you ─────────────────────────────────── */
        .thankyou {
            border-top: 1px dashed var(--border);
            padding: 24px 0 4px;
            text-align: center;
        }
        .thankyou-icon {
            width: 40px; height: 40px;
            background: var(--green-l);
            border: 1px solid var(--green-b);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: var(--green);
        }
        .thankyou h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--navy2);
            margin-bottom: 4px;
        }
        .thankyou p {
            font-size: 12px;
            color: var(--slate);
            line-height: 1.6;
        }
        .thankyou .garage-em {
            font-weight: 700;
            color: var(--orange);
        }

        /* ── Footer ────────────────────────────────────── */
        .inv-footer {
            background: var(--bg);
            border-top: 1px solid var(--border);
            padding: 16px 40px;
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
            background: var(--green-l);
            border: 1px solid var(--green-b);
            color: var(--green);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-family: 'Share Tech Mono', monospace;
        }

        /* ── Print ─────────────────────────────────────── */
        @media print {
            @page { size: A4; margin: 12mm 10mm; }
            body {
                background: #fff;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .action-bar { display: none !important; }
            .invoice {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
        }

        /* ── Mobile ────────────────────────────────────── */
        @media (max-width: 640px) {
            .inv-header { padding: 22px 24px 20px; }
            .inv-body   { padding: 24px; }
            .inv-footer { padding: 14px 24px; }
            .status-strip { padding: 10px 24px; }
            .parties    { grid-template-columns: 1fr; }
            .appt-grid  { grid-template-columns: 1fr 1fr; }
            .header-title .inv-word { font-size: 24px; }
            .paid-stamp { display: none; }
            .totals-box { width: 100%; }
        }
    </style>
</head>
<body>

{{-- ── Action bar (screen only) ──────────────────────────── --}}
<div class="action-bar">
    <a href="javascript:history.back()" class="btn btn-back">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        {{ __('app.back_btn') }}
    </a>
    <div class="action-group">
        <button onclick="copyLink()" class="btn btn-copy">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
            </svg>
            COPY LINK
        </button>
        <span class="toast" id="toast">LINK COPIED</span>
        <button onclick="window.print()" class="btn btn-print">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>
            </svg>
            {{ __('app.print_invoice_btn') }}
        </button>
    </div>
</div>

{{-- ── Invoice ─────────────────────────────────────────────── --}}
<div class="invoice">

    {{-- Header --}}
    <div class="inv-header">
        <div class="paid-stamp">PAID</div>
        <div class="header-inner">
            <div class="header-brand">
                <div class="brand-lockup">
                    <img src="/images/logo.png" alt="AutoMateX logo">
                    <span class="brand-text">
                        <span class="bt-auto">Auto</span><span class="bt-mate">Mate</span><span class="bt-x">X</span>
                    </span>
                </div>
                <div class="header-tagline">// VEHICLE MANAGEMENT SYSTEM</div>
            </div>
            <div class="header-title">
                <div class="inv-word">TAX&nbsp;<span>INVOICE</span></div>
                <div class="inv-num">{{ $booking->invoice_number ?? '——' }}</div>
                <div class="inv-date">
                    Issued:
                    {{ $booking->invoice_date
                        ? \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y')
                        : \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>
    <div class="header-accent"></div>

    {{-- Status strip --}}
    <div class="status-strip">
        <span class="strip-ref">
            Ref: <strong>{{ $booking->invoice_number ?? '—' }}</strong>
            &nbsp;&middot;&nbsp;
            Service date: <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong>
            &nbsp;&middot;&nbsp;
            Time: <strong>{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</strong>
        </span>
        <span class="paid-chip">
            <span class="paid-dot"></span>
            COMPLETED &amp; PAID
        </span>
    </div>

    {{-- Body --}}
    <div class="inv-body">

        {{-- FROM / TO --}}
        <div class="parties">

            {{-- FROM: Garage --}}
            <div class="party-box from">
                <div class="party-lbl">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                    </svg>
                    FROM — SERVICE CENTRE
                </div>
                <div class="party-name">{{ $booking->garage->name }}</div>
                <div class="party-row">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                    {{ $booking->garage->address }}, {{ $booking->garage->city }}, Sri Lanka
                </div>
                @if($booking->garage->phone)
                <div class="party-row">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 6.75z"/>
                    </svg>
                    {{ $booking->garage->phone }}
                </div>
                @endif
                @if($booking->garage->specialization)
                <span class="spec-tag">{{ $booking->garage->specialization }}</span>
                @endif
            </div>

            {{-- TO: Customer --}}
            <div class="party-box to">
                <div class="party-lbl">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    BILL TO — CUSTOMER
                </div>
                <div class="party-name">{{ $booking->vehicle->user->name }}</div>
                <div class="party-row">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                    {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} — {{ $booking->vehicle->year }}
                </div>
                <div class="party-row">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/>
                    </svg>
                    Plate: {{ $booking->vehicle->license_plate }}
                </div>
                @if($booking->vehicle->vin)
                <div class="party-row" style="font-size:11px;color:#94a3b8;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                    </svg>
                    VIN: {{ $booking->vehicle->vin }}
                </div>
                @endif
            </div>
        </div>

        {{-- Appointment details --}}
        <p class="section-hd">APPOINTMENT DETAILS</p>
        <div class="appt-grid">
            <div class="appt-cell">
                <div class="a-lbl">Service Date</div>
                <div class="a-val">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
            </div>
            <div class="appt-cell">
                <div class="a-lbl">Arrival Time</div>
                <div class="a-val">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</div>
            </div>
            <div class="appt-cell">
                <div class="a-lbl">Vehicle</div>
                <div class="a-val" style="font-size:12px;">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
            </div>
            <div class="appt-cell">
                <div class="a-lbl">Invoice Date</div>
                <div class="a-val">
                    {{ $booking->invoice_date
                        ? \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y')
                        : '—' }}
                </div>
            </div>
        </div>

        {{-- Service line items --}}
        <p class="section-hd" style="margin-bottom:10px;">SERVICE DETAILS</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>DESCRIPTION</th>
                    <th class="center">QTY</th>
                    <th class="right" style="width:140px;">RATE (LKR)</th>
                    <th class="right" style="width:140px;">AMOUNT (LKR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>
                        <div class="item-name">{{ $booking->service_type }}</div>
                        @if($booking->invoice_notes)
                        <div class="item-notes">{{ $booking->invoice_notes }}</div>
                        @endif
                    </td>
                    <td class="center">1</td>
                    <td class="right">{{ number_format($booking->invoice_amount, 2) }}</td>
                    <td class="right">{{ number_format($booking->invoice_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="totals-wrapper">
            <div class="totals-box">
                <div class="totals-row">
                    <span class="tl">Subtotal</span>
                    <span class="tv">LKR {{ number_format($booking->invoice_amount, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span class="tl">Discount</span>
                    <span class="tv muted">LKR 0.00</span>
                </div>
                <div class="totals-row">
                    <span class="tl">Tax / VAT</span>
                    <span class="tv muted">LKR 0.00</span>
                </div>
                <div class="total-final">
                    <div class="tf-label">TOTAL DUE</div>
                    <div class="tf-amount">
                        <span class="tf-currency">LKR</span>{{ number_format($booking->invoice_amount, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer notes --}}
        @if($booking->notes)
        <div class="notes-box">
            <div class="notes-lbl">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                CUSTOMER NOTES
            </div>
            <p>{{ $booking->notes }}</p>
        </div>
        @endif

        {{-- Thank you --}}
        <div class="thankyou">
            <div class="thankyou-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3>Thank you for your business!</h3>
            <p>
                We appreciate your trust in <span class="garage-em">{{ $booking->garage->name }}</span>.<br>
                We look forward to serving you again.
            </p>
        </div>

    </div>{{-- /inv-body --}}

    {{-- Footer --}}
    <div class="inv-footer">
        <div class="footer-left">
            <div class="fl-brand">AutoMateX &middot; &copy; {{ date('Y') }}</div>
            <div class="fl-note">This is a computer-generated invoice. No signature is required.</div>
        </div>
        <div class="footer-badge">
            <span class="paid-dot"></span>
            VERIFIED &amp; PAID
        </div>
    </div>

</div>{{-- /invoice --}}

<script>
function copyLink() {
    const url = window.location.href;
    const toast = document.getElementById('toast');
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(showToast);
    } else {
        const el = document.createElement('textarea');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showToast();
    }
}
function showToast() {
    const t = document.getElementById('toast');
    t.style.display = 'inline-block';
    setTimeout(() => { t.style.display = 'none'; }, 2200);
}
</script>

</body>
</html>
