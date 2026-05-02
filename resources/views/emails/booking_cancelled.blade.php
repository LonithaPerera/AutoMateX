<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled — AutoMateX</title>
</head>
<body style="margin:0;padding:0;background:#e8edf5;font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#0f172a;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#e8edf5;padding:28px 16px 48px;">
<tr><td align="center">
<table width="100%" style="max-width:620px;" cellpadding="0" cellspacing="0">

    {{-- ═══ HEADER ═══ --}}
    <tr>
        <td style="background:#0b1120;border-radius:16px 16px 0 0;padding:28px 36px 24px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="vertical-align:middle;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="vertical-align:middle;padding-right:12px;">
                                    <img src="{{ $message->embed(public_path('images/logo.png')) }}"
                                         alt="AutoMateX" width="54" height="54"
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
                    <td style="vertical-align:middle;text-align:right;">
                        <div style="font-size:22px;font-weight:800;color:#ffffff;letter-spacing:0.06em;line-height:1;">
                            BOOKING&nbsp;<span style="color:#f87171;">CANCELLED</span>
                        </div>
                        <div style="font-family:'Courier New',monospace;font-size:12px;color:#94a3b8;margin-top:6px;">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- ── Red accent bar ──────────────────────────────────── --}}
    <tr>
        <td style="height:3px;background:linear-gradient(90deg,#ef4444,#f87171,#fca5a5);font-size:0;line-height:0;">&nbsp;</td>
    </tr>

    {{-- ── Alert strip ──────────────────────────────────────── --}}
    <tr>
        <td style="background:#1c0a0a;border-top:2px solid #ef4444;padding:16px 36px;text-align:center;">
            <div style="display:inline-block;background:#fee2e2;border:1px solid #fca5a5;border-radius:20px;padding:5px 16px;">
                <span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#ef4444;vertical-align:middle;margin-right:6px;"></span>
                <span style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#b91c1c;letter-spacing:0.08em;vertical-align:middle;">BOOKING CANCELLED BY CUSTOMER</span>
            </div>
            <div style="color:#94a3b8;font-size:13px;margin-top:8px;">
                A customer has cancelled their booking at <strong style="color:#f97316;">{{ $booking->garage->name }}</strong>. Details are shown below.
            </div>
        </td>
    </tr>

    {{-- ═══ BODY ═══ --}}
    <tr>
        <td style="background:#ffffff;padding:32px 36px;">

            {{-- GARAGE / CUSTOMER ──────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #f97316;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#f97316;margin-bottom:10px;">YOUR GARAGE</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">{{ $booking->garage->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->garage->address }}, {{ $booking->garage->city }}</div>
                        @if($booking->garage->phone)
                        <div style="font-size:12px;color:#64748b;">{{ $booking->garage->phone }}</div>
                        @endif
                    </td>
                    <td width="4%">&nbsp;</td>
                    <td width="48%" style="vertical-align:top;background:#f8fafc;border:1px solid #e2e8f0;border-top:3px solid #ef4444;border-radius:10px;padding:16px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:9px;font-weight:700;letter-spacing:0.16em;color:#ef4444;margin-bottom:10px;">CUSTOMER</div>
                        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">{{ $booking->vehicle->user->name }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->vehicle->user->email }}</div>
                        <div style="font-size:12px;color:#64748b;margin-bottom:3px;">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }} {{ $booking->vehicle->year }}</div>
                        <div style="font-size:12px;color:#64748b;">Plate: {{ $booking->vehicle->license_plate }}</div>
                    </td>
                </tr>
            </table>

            {{-- CANCELLED BOOKING DETAILS ────────────────────── --}}
            <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.14em;color:#64748b;padding-bottom:10px;border-bottom:1px solid #e2e8f0;margin-bottom:14px;border-left:3px solid #ef4444;padding-left:10px;">
                CANCELLED BOOKING DETAILS
            </div>

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
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Service</div>
                            <div style="font-family:'Courier New',monospace;font-size:11px;font-weight:700;color:#0f172a;">{{ Str::limit($booking->service_type, 20) }}</div>
                        </div>
                    </td>
                    <td width="25%" style="vertical-align:top;">
                        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px 14px;">
                            <div style="font-size:10px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:4px;">Status</div>
                            <div style="font-family:'Courier New',monospace;font-size:12px;font-weight:700;color:#b91c1c;">CANCELLED</div>
                        </div>
                    </td>
                </tr>
            </table>

            {{-- Cancellation reason ───────────────────────────── --}}
            @if($booking->cancel_reason)
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                <tr>
                    <td style="background:#fff1f2;border:1px solid #fecdd3;border-left:3px solid #ef4444;border-radius:10px;padding:14px 18px;">
                        <div style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;letter-spacing:0.12em;color:#b91c1c;margin-bottom:6px;">REASON FOR CANCELLATION</div>
                        <div style="font-size:13px;color:#334155;line-height:1.6;">{{ $booking->cancel_reason }}</div>
                    </td>
                </tr>
            </table>
            @endif

            {{-- Footer note ──────────────────────────────────── --}}
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="border-top:1px dashed #e2e8f0;padding-top:24px;text-align:center;">
                        <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:4px;">Your slot is now available.</div>
                        <div style="font-size:12px;color:#64748b;line-height:1.6;">
                            This time slot has been freed up. Log in to your dashboard<br>to manage your bookings and schedule.
                        </div>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    {{-- ═══ FOOTER ═══ --}}
    <tr>
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;border-radius:0 0 16px 16px;padding:16px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div style="font-family:'Courier New',monospace;font-size:11px;color:#94a3b8;">AutoMateX &middot; &copy; {{ date('Y') }}</div>
                        <div style="font-size:10px;color:#cbd5e1;margin-top:2px;font-style:italic;">This is an automated notification. Please do not reply to this email.</div>
                    </td>
                    <td style="text-align:right;vertical-align:middle;">
                        <div style="display:inline-block;background:#fee2e2;border:1px solid #fca5a5;border-radius:20px;padding:5px 14px;">
                            <span style="font-family:'Courier New',monospace;font-size:10px;font-weight:700;color:#b91c1c;letter-spacing:0.08em;">AUTOMATEX</span>
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
