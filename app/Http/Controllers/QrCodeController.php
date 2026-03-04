<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    // Generate and display QR code for a vehicle
    public function show(Vehicle $vehicle)
    {
        // Build the public URL this QR code points to
        $publicUrl = route('public.vehicle', $vehicle->qr_token);

        // Generate QR code as a data URI (inline image)
        $options = new QROptions([
            'outputType' => \chillerlan\QRCode\Output\QROutputInterface::MARKUP_SVG,
            'imageBase64' => false,
        ]);

        $qrCode = (new QRCode($options))->render($publicUrl);

        return view('qrcode.show', compact('vehicle', 'qrCode', 'publicUrl'));
    }

    // Public page — no login required
    public function publicView(string $token)
    {
        $vehicle = Vehicle::where('qr_token', $token)->firstOrFail();

        $serviceLogs = $vehicle->serviceLogs()
                               ->orderBy('service_date', 'desc')
                               ->get();

        return view('qrcode.public', compact('vehicle', 'serviceLogs'));
    }
}