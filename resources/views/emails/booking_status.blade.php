<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Update — AutoMateX</title>
</head>
<body style="margin:0;padding:0;background:#e8edf5;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#0f172a;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#e8edf5;padding:28px 16px 48px;">
<tr><td align="center">
<table width="100%" style="max-width:620px;" cellpadding="0" cellspacing="0">

    {{-- ═══════════════════════════════════════════════════════════
         HEADER — dark navy, brand lockup, booking type title
    ═══════════════════════════════════════════════════════════ --}}
    <tr>
        <td style="background:#0b1120;border-radius:16px 16px 0 0;padding:28px 36px 24px;position:relative;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    {{-- Brand lockup --}}
                    <td style="vertical-align:middle;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align:middle;padding-right:12px;">
                                    <img src="{{ $message->embed(public_path('images/logo.png')) }}"
                                         alt="AutoMateX"
                                         width="54" height="54"
                                         style="display:block;border-radius:50%;">
                                </td>
                                <td style="vertical-align:middle;">
                                    <div style="font-size:30px;font-weight:900;letter-spacing:1px;line-height:1;">
                                        <span style="color:#ffffff;">Auto</span><span style="color:#00f5ff;">Mate</span><span style="color:#ff6b00;font-size:1.2em;line-height:1;">X</span>
                                    </div>
                                    <div style="font-family:'Courier New',monospace;font-size:9px;color:#3a5070;letter-spacing:0.14em;margin-top:4px;">
                                        // VEHICLE MANAGEMENT SYSTEM
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    {{-- Booking type title --}}
                    <td style="vertical-align:middle;text-align:right;">
                        <div style="font-size:22px;font-weight:800;color:#ffffff;letter-spacing:0.06em;line-height:1;">
                            BOOKING&nbsp;<span style="color:#f97316;">
                                @if($booking->status === 'confirmed')CONFIRMED
                                @else COMPLETED @endif
                            </span>
                        </div>
                        <div style="font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;margin-top:6px;">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── Orange accent bar ─────────────────────────────────── --}}
    <tr>
        <td style="height:3px;background:linear-gradient(90deg,#f97316,#fb923c,#fbbf24);font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    {{-- ── Status strip ──────────────────────────────────────── --}}
    @if($booking->status === 'confirmed')
    <tr>
        <td style="background:#0a2a3d;border-top:2px solid #00f5ff;padding:16px 36px;text-align:center;">
            <div style="display:inline-block;background:#dcfce7;border:1px solid #86efac;border-radius:20px;padding:5px 16px;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#22c55e;vertical-align:middle;margin-right:6px;"></span>
                <span style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#16a34a;letter-spacing:0.08em;vertical-align:middle;">APPOINTMENT CONFIRMED</span>
            </div>
            <div style="color:#94a3b8;font-size:13px;margin-top:8px;">
                Your appointment has been confirmed. We look forward to seeing you!
            </div>
        </td>
    </tr>
    @else
    <tr>
        <td style="background:#0a2a1a;border-top:2px solid #4ade80;padding:16px 36px;text-align:center;">
            <div style="display:inline-block;background:#dcfce7;border:1px solid #86efac;border-radius:20px;padding:5px 16px;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#22c55e;vertical-align:middle;margin-right:6px;"></span>
                <span style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#16a34a;letter-spacing:0.08em;vertical-align:middle;">COMPLETED &amp; PAID</span>
            </div>
            <div style="color:#94a3b8;font-size:13px;margin-top:8px;">
                Your vehicle service has been completed. Thank you for choosing <strong style="color:#f97316;">{{ $booking->garage->name }}</strong>.
            </div>
        </td>
    </tr>
    @endif

    {{-- ═══════════════════════════════════════════════════════════
         BODY — white background
    ═══════════════════════════════════════════════════════════ --}}
    <tr>
        <td style="background:#ffffff;padding:32px 36px;">

            {{-- FROM / BILL TO ─────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    {{-- FROM: Garage --}}
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #f97316;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#f97316;margin-bottom:10px;">
                            FROM — SERVICE CENTRE
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">{{ $booking->garage->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->garage->address }}, {{ $booking->garage->city }}</div>
                        @if($booking->garage->phone)
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->garage->phone }}</div>
                        @endif
                        @if($booking->garage->specialization)
                        <div style="display:inline-block;margin-top:6px;font-family:'Courier New',monospace;font-size:10px;background:#fff7ed;color:#f97316;border:1px solid #fed7aa;border-radius:4px;padding:2px 8px;">
                            {{ $booking->garage->specialization }}
                        </div>
                        @endif
                    </td>
                    <td width="4%">&nbsp;</td>
                    {{-- BILL TO: Customer --}}
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #0066ff;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#0066ff;margin-bottom:10px;">
                            BILL TO — CUSTOMER
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">{{ $booking->vehicle->user->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->vehicle->user->email }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">
                            {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} {{ $booking->vehicle->year }}
                        </div>
                        <div style="font-size:12px;color:#64748b;">Plate: {{ $booking->vehicle->license_plate }}</div>
                    </td>
                </tr>
            </table>

            {{-- APPOINTMENT DETAILS header ──────────────────── --}}
            <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.14em;color:#64748b;padding-bottom:10px;border-bottom:1px solid #e2e8f0;margin-bottom:14px;border-left:3px solid #f97316;padding-left:10px;">
                APPOINTMENT DETAILS
            </div>

            {{-- 4-cell appointment grid ──────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    <td width="25%" style="padding-right:8px;vertical-align:top;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;">
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Service Date</div>
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#0f172a;">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</div>
                        </div>
                    </td>
                    <td width="25%" style="padding-right:8px;vertical-align:top;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;">
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Arrival Time</div>
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#0f172a;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</div>
                        </div>
                    </td>
                    <td width="25%" style="padding-right:8px;vertical-align:top;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;">
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Vehicle</div>
                            <div style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#0f172a;">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</div>
                        </div>
                    </td>
                    <td width="25%" style="vertical-align:top;">
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;">
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Status</div>
                            @if($booking->status === 'confirmed')
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#00f5ff;">CONFIRMED</div>
                            @else
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#4ade80;">COMPLETED</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            {{-- SERVICE DETAILS header ──────────────────────── --}}
            <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.14em;color:#64748b;padding-bottom:10px;border-bottom:1px solid #e2e8f0;margin-bottom:0;border-left:3px solid #f97316;padding-left:10px;">
                SERVICE DETAILS
            </div>

            {{-- Service line items table ────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0;border-collapse:collapse;">
                <thead>
                    <tr style="background:#0b1120;">
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:left;letter-spacing:0.1em;border-radius:0;width:36px;">#</th>
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:left;letter-spacing:0.1em;">DESCRIPTION</th>
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:center;letter-spacing:0.1em;width:50px;">QTY</th>
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:right;letter-spacing:0.1em;width:130px;">AMOUNT (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px;font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;vertical-align:top;">01</td>
                        <td style="padding:14px;vertical-align:top;">
                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">{{ $booking->service_type }}</div>
                            @if($booking->invoice_notes)
                            <div style="font-size:12px;color:#64748b;line-height:1.5;">{{ $booking->invoice_notes }}</div>
                            @endif
                        </td>
                        <td style="padding:14px;text-align:center;color:#64748b;vertical-align:top;">1</td>
                        <td style="padding:14px;text-align:right;font-family:'Courier New',monospace;font-size:13px;font-weight:700;color:#0f172a;vertical-align:top;">
                            @if($booking->invoice_amount)
                            {{ number_format($booking->invoice_amount, 2) }}
                            @else —
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Total block ──────────────────────────────────── --}}
            @if($booking->invoice_amount)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:8px;margin-bottom:28px;">
                <tr>
                    <td align="right">
                        <table cellpadding="0" cellspacing="0" style="width:260px;">
                            <tr>
                                <td style="padding:6px 0;border-bottom:1px solid #e2e8f0;">
                                    <table width="100%"><tr>
                                        <td style="font-size:12px;color:#64748b;">Subtotal</td>
                                        <td style="text-align:right;font-family:'Courier New',monospace;font-size:12px;font-weight:600;color:#0f172a;">LKR {{ number_format($booking->invoice_amount, 2) }}</td>
                                    </tr></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;border-bottom:1px solid #e2e8f0;">
                                    <table width="100%"><tr>
                                        <td style="font-size:12px;color:#64748b;">Discount</td>
                                        <td style="text-align:right;font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;">LKR 0.00</td>
                                    </tr></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:6px 0;border-bottom:1px solid #e2e8f0;">
                                    <table width="100%"><tr>
                                        <td style="font-size:12px;color:#64748b;">Tax / VAT</td>
                                        <td style="text-align:right;font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;">LKR 0.00</td>
                                    </tr></table>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top:10px;">
                                    <table width="100%" style="background:#0b1120;border-radius:10px;overflow:hidden;">
                                        <tr>
                                            <td style="padding:16px 18px;">
                                                <div style="font-family:'Courier New',monospace;font-size:10px;color:#64748b;letter-spacing:0.1em;margin-bottom:2px;">TOTAL DUE</div>
                                                <div style="font-family:'Courier New',monospace;font-size:22px;font-weight:700;color:#4ade80;">
                                                    <span style="font-size:13px;color:rgba(74,222,128,0.6);margin-right:2px;">LKR</span>{{ number_format($booking->invoice_amount, 2) }}
                                                </div>
                                            </td>
                                            <td style="padding:16px 18px;text-align:right;">
                                                <div style="font-family:'Courier New',monospace;font-size:20px;font-weight:800;color:rgba(74,222,128,0.08);letter-spacing:0.12em;">PAID</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @else
            <div style="height:28px;"></div>
            @endif

            {{-- Garage note ──────────────────────────────────── --}}
            @if($booking->garage_notes)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                    <td style="background:#f0f9ff;border:1px solid #bae6fd;border-left:3px solid #0066ff;border-radius:10px;padding:14px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.12em;color:#0369a1;margin-bottom:6px;">NOTE FROM GARAGE</div>
                        <div style="font-size:13px;color:#334155;line-height:1.6;">{{ $booking->garage_notes }}</div>
                    </td>
                </tr>
            </table>
            @endif

            {{-- Customer notes ───────────────────────────────── --}}
            @if($booking->notes)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                    <td style="background:#fffbeb;border:1px solid #fde68a;border-left:3px solid #f59e0b;border-radius:10px;padding:14px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.12em;color:#92400e;margin-bottom:6px;">CUSTOMER NOTES</div>
                        <div style="font-size:13px;color:#78350f;line-height:1.6;">{{ $booking->notes }}</div>
                    </td>
                </tr>
            </table>
            @endif

            {{-- Thank you ────────────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-top:1px dashed #e2e8f0;padding-top:24px;text-align:center;">
                        <div style="width:40px;height:40px;background:#dcfce7;border:1px solid #86efac;border-radius:50%;margin:0 auto 12px;line-height:40px;text-align:center;font-size:20px;">
                            &#10003;
                        </div>
                        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:4px;">Thank you for your business!</div>
                        <div style="font-size:12px;color:#64748b;line-height:1.6;">
                            We appreciate your trust in <strong style="color:#f97316;">{{ $booking->garage->name }}</strong>.<br>
                            We look forward to serving you again.
                        </div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    {{-- ═══════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════ --}}
    <tr>
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;border-radius:0 0 16px 16px;padding:16px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div style="font-family:'Courier New',monospace;font-size:11px;color:#94a3b8;">AutoMateX &middot; &copy; {{ date('Y') }}</div>
                        <div style="font-size:10px;color:#cbd5e1;margin-top:2px;font-style:italic;">This is an automated notification. Please do not reply to this email.</div>
                    </td>
                    <td style="text-align:right;vertical-align:middle;">
                        <div style="display:inline-block;background:#dcfce7;border:1px solid #86efac;border-radius:20px;padding:5px 14px;">
                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#22c55e;vertical-align:middle;margin-right:5px;"></span>
                            <span style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;color:#16a34a;letter-spacing:0.08em;vertical-align:middle;">VERIFIED</span>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
</td></tr>
</table>

</body>
</html>
