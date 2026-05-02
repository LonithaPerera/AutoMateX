<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking — AutoMateX</title>
</head>
<body style="margin:0;padding:0;background:#e8edf5;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#0f172a;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#e8edf5;padding:28px 16px 48px;">
<tr><td align="center">
<table width="100%" style="max-width:620px;" cellpadding="0" cellspacing="0">

    {{-- ═══════════════════════════════════════════════════════════
         HEADER — dark navy, brand lockup
    ═══════════════════════════════════════════════════════════ --}}
    <tr>
        <td style="background:#0b1120;border-radius:16px 16px 0 0;padding:28px 36px 24px;">
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
                            NEW&nbsp;<span style="color:#f97316;">BOOKING</span>
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

    {{-- ── Alert strip ──────────────────────────────────────── --}}
    <tr>
        <td style="background:#0a1f3d;border-top:2px solid #0066ff;padding:16px 36px;text-align:center;">
            <div style="display:inline-block;background:#dbeafe;border:1px solid #93c5fd;border-radius:20px;padding:5px 16px;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#3b82f6;vertical-align:middle;margin-right:6px;"></span>
                <span style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#1d4ed8;letter-spacing:0.08em;vertical-align:middle;">NEW BOOKING RECEIVED</span>
            </div>
            <div style="color:#94a3b8;font-size:13px;margin-top:8px;">
                A customer has booked an appointment at <strong style="color:#f97316;">{{ $booking->garage->name }}</strong>. Please review the details below.
            </div>
        </td>
    </tr>

    {{-- ═══════════════════════════════════════════════════════════
         BODY — white background
    ═══════════════════════════════════════════════════════════ --}}
    <tr>
        <td style="background:#ffffff;padding:32px 36px;">

            {{-- FROM / CUSTOMER ──────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    {{-- BOOKED AT: Garage --}}
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #f97316;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#f97316;margin-bottom:10px;">
                            YOUR GARAGE
                        </div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">{{ $booking->garage->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->garage->address }}, {{ $booking->garage->city }}</div>
                        @if($booking->garage->phone)
                        <div style="font-size:12px;color:#64748b;">{{ $booking->garage->phone }}</div>
                        @endif
                    </td>
                    <td width="4%">&nbsp;</td>
                    {{-- FROM: Customer --}}
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #0066ff;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#0066ff;margin-bottom:10px;">
                            CUSTOMER
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
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#fbbf24;">PENDING</div>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- SERVICE DETAILS header ──────────────────────── --}}
            <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.14em;color:#64748b;padding-bottom:10px;border-bottom:1px solid #e2e8f0;margin-bottom:0;border-left:3px solid #f97316;padding-left:10px;">
                SERVICE REQUESTED
            </div>

            {{-- Service line item ────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;border-collapse:collapse;">
                <thead>
                    <tr style="background:#0b1120;">
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:left;letter-spacing:0.1em;width:36px;">#</th>
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:left;letter-spacing:0.1em;">DESCRIPTION</th>
                        <th style="font-family:'Courier New',monospace;font-size:10px;color:#94a3b8;padding:10px 14px;text-align:right;letter-spacing:0.1em;width:130px;">PLATE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:14px;font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;vertical-align:top;">01</td>
                        <td style="padding:14px;vertical-align:top;">
                            <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">{{ $booking->service_type }}</div>
                            @if($booking->notes)
                            <div style="font-size:12px;color:#64748b;line-height:1.5;">{{ $booking->notes }}</div>
                            @endif
                        </td>
                        <td style="padding:14px;text-align:right;font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#0f172a;vertical-align:top;">
                            {{ $booking->vehicle->license_plate }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Action prompt ────────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    <td style="background:#f0f9ff;border:1px solid #bae6fd;border-left:3px solid #0066ff;border-radius:10px;padding:14px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.12em;color:#0369a1;margin-bottom:6px;">ACTION REQUIRED</div>
                        <div style="font-size:13px;color:#334155;line-height:1.6;">
                            Log in to your AutoMateX dashboard to <strong>confirm</strong> or <strong>reschedule</strong> this booking. The customer will be notified once you update the status.
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Thank you ────────────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-top:1px dashed #e2e8f0;padding-top:24px;text-align:center;">
                        <div style="width:40px;height:40px;background:#dbeafe;border:1px solid #93c5fd;border-radius:50%;margin:0 auto 12px;line-height:40px;text-align:center;font-size:20px;color:#1d4ed8;">
                            &#9993;
                        </div>
                        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:4px;">New booking alert!</div>
                        <div style="font-size:12px;color:#64748b;line-height:1.6;">
                            Please respond promptly to keep your customers informed.<br>
                            Visit your dashboard to manage this booking.
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
                        <div style="display:inline-block;background:#dbeafe;border:1px solid #93c5fd;border-radius:20px;padding:5px 14px;">
                            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#3b82f6;vertical-align:middle;margin-right:5px;"></span>
                            <span style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;color:#1d4ed8;letter-spacing:0.08em;vertical-align:middle;">AUTOMATEX</span>
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
