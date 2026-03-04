<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <a href="{{ route('vehicles.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                ← Back to My Vehicles
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8">

                <h3 class="text-lg font-bold text-gray-700 mb-4">Vehicle Details</h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Make</p>
                        <p class="font-medium">{{ $vehicle->make }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Model</p>
                        <p class="font-medium">{{ $vehicle->model }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Year</p>
                        <p class="font-medium">{{ $vehicle->year }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Mileage</p>
                        <p class="font-medium">{{ number_format($vehicle->mileage) }} km</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Fuel Type</p>
                        <p class="font-medium">{{ ucfirst($vehicle->fuel_type) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Color</p>
                        <p class="font-medium">{{ $vehicle->color ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">License Plate</p>
                        <p class="font-medium">{{ $vehicle->license_plate ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">VIN</p>
                        <p class="font-medium">{{ $vehicle->vin ?? 'Not specified' }}</p>
                    </div>
                </div>

            {{-- Action Buttons --}}
                {{-- Action Buttons --}}
                <div class="mt-6 flex gap-3 flex-wrap">
                    <a href="{{ route('service.index', $vehicle) }}"
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        🔧 Service History
                    </a>
                    <a href="{{ route('fuel.index', $vehicle) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        ⛽ Fuel Logs
                    </a>
                    <a href="{{ route('vehicles.index') }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                        ← My Vehicles
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>