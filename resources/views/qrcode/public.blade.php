<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service History — AutoMateX</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Header --}}
    <div class="bg-blue-700 text-white py-6 px-4 text-center">
        <h1 class="text-2xl font-bold">🚗 AutoMateX</h1>
        <p class="text-sm text-blue-200 mt-1">Vehicle Service History Report</p>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-8">

        {{-- Vehicle Info --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Fuel Type</p>
                    <p class="font-medium">{{ ucfirst($vehicle->fuel_type) }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Current Mileage</p>
                    <p class="font-medium">{{ number_format($vehicle->mileage) }} km</p>
                </div>
                @if($vehicle->color)
                <div>
                    <p class="text-gray-400">Color</p>
                    <p class="font-medium">{{ $vehicle->color }}</p>
                </div>
                @endif
                @if($vehicle->license_plate)
                <div>
                    <p class="text-gray-400">License Plate</p>
                    <p class="font-medium">{{ $vehicle->license_plate }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Service History --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="font-bold text-gray-700">
                    🔧 Service History ({{ $serviceLogs->count() }} records)
                </h3>
            </div>

            @if($serviceLogs->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    No service records found for this vehicle.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($serviceLogs as $log)
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-800">
                                        {{ $log->service_type }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        📅 {{ $log->service_date->format('d M Y') }}
                                        &nbsp;|&nbsp;
                                        🛣️ {{ number_format($log->mileage_at_service) }} km
                                        @if($log->garage_name)
                                            &nbsp;|&nbsp;
                                            🏪 {{ $log->garage_name }}
                                        @endif
                                    </p>
                                    @if($log->notes)
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $log->notes }}
                                        </p>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    @if($log->type === 'maintenance')
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                            Maintenance
                                        </span>
                                    @elseif($log->type === 'repair')
                                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">
                                            Repair
                                        </span>
                                    @else
                                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded">
                                            Inspection
                                        </span>
                                    @endif
                                    <p class="text-sm font-medium text-gray-700 mt-1">
                                        LKR {{ number_format($log->cost, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 text-xs text-gray-400">
            <p>This report was generated by <strong>AutoMateX</strong></p>
            <p class="mt-1">A Web-Based Vehicle Service & Maintenance Management System</p>
        </div>

    </div>
</body>
</html>