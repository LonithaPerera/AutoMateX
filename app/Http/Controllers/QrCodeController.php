<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QROutputInterface;

class QrCodeController extends Controller
{
    public function show(Vehicle $vehicle)
    {
        $publicUrl = route('public.vehicle', $vehicle->qr_token);

        // Use SVG — no GD extension needed!
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QROutputInterface::MARKUP_SVG,
            'imageBase64' => false,
        ]);

        $qrCode = (new QRCode($options))->render($publicUrl);

        $serviceLogs = $vehicle->serviceLogs()
                               ->orderBy('service_date', 'desc')
                               ->get();

        return view('qrcode.show', compact('vehicle', 'qrCode', 'publicUrl', 'serviceLogs'));
    }

    public function publicView(string $token)
    {
        $vehicle = Vehicle::where('qr_token', $token)->firstOrFail();

        $serviceLogs = $vehicle->serviceLogs()
                               ->orderBy('service_date', 'desc')
                               ->get();

        return view('qrcode.public', compact('vehicle', 'serviceLogs'));
    }
}