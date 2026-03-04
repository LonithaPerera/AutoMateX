<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📱 QR Code — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <a href="{{ route('vehicles.show', $vehicle) }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                ← Back to Vehicle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8 text-center">

                <h3 class="text-lg font-bold text-gray-700 mb-2">
                    Vehicle Service History QR Code
                </h3>
                <p class="text-sm text-gray-500 mb-6">
                    Anyone who scans this QR code can view this vehicle's
                    complete service history — no login required.
                    Perfect for resale transparency!
                </p>

                {{-- QR Code Image --}}
                <div class="flex justify-center mb-6">
                    <div class="p-4 border-2 border-gray-200 rounded-lg inline-block">
                        {!! $qrCode !!}
                    </div>
                </div>

                {{-- Public Link --}}
                <div class="bg-gray-50 rounded p-3 mb-6">
                    <p class="text-xs text-gray-500 mb-1">Public Link:</p>
                    <a href="{{ $publicUrl }}" target="_blank"
                       class="text-blue-600 text-sm break-all hover:underline">
                        {{ $publicUrl }}
                    </a>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-3 justify-center">
                    <a href="{{ $publicUrl }}" target="_blank"
                       class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        👁️ Preview Public Page
                    </a>
                </div>

                <p class="text-xs text-gray-400 mt-6">
                    🔒 Only service history is visible publicly.
                    Personal details remain private.
                </p>

            </div>
        </div>
    </div>
</x-app-layout>