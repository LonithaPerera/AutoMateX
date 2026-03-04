<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🚗 My Vehicles
            </h2>
            <a href="{{ route('vehicles.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                + Add Vehicle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if($vehicles->isEmpty())
                <div class="bg-white p-12 rounded-2xl shadow text-center">
                    <div class="text-6xl mb-4">🚗</div>
                    <p class="text-gray-500 text-lg font-medium">No vehicles added yet.</p>
                    <p class="text-gray-400 text-sm mt-1">Add your first vehicle to start tracking maintenance.</p>
                    <a href="{{ route('vehicles.create') }}"
                       class="mt-6 inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-medium">
                        + Add Your First Vehicle
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all p-6">

                            {{-- Vehicle Header --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">
                                        {{ $vehicle->make }} {{ $vehicle->model }}
                                    </h3>
                                    <p class="text-gray-400 text-sm">{{ $vehicle->year }}</p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-sm font-bold px-3 py-1 rounded-full">
                                    {{ number_format($vehicle->mileage) }} km
                                </span>
                            </div>

                            {{-- Vehicle Details --}}
                            <div class="space-y-1 text-sm text-gray-500 mb-4">
                                @if($vehicle->license_plate)
                                    <p>🔖 {{ $vehicle->license_plate }}</p>
                                @endif
                                <p>⛽ {{ ucfirst($vehicle->fuel_type) }}</p>
                                @if($vehicle->color)
                                    <p>🎨 {{ $vehicle->color }}</p>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <a href="{{ route('suggestions.index', $vehicle) }}"
                                   class="bg-purple-50 text-purple-700 px-3 py-2 rounded-lg text-xs font-medium text-center hover:bg-purple-100">
                                    🧠 Suggestions
                                </a>
                                <a href="{{ route('service.index', $vehicle) }}"
                                   class="bg-green-50 text-green-700 px-3 py-2 rounded-lg text-xs font-medium text-center hover:bg-green-100">
                                    🔧 Service
                                </a>
                                <a href="{{ route('fuel.index', $vehicle) }}"
                                   class="bg-orange-50 text-orange-700 px-3 py-2 rounded-lg text-xs font-medium text-center hover:bg-orange-100">
                                    ⛽ Fuel Logs
                                </a>
                                <a href="{{ route('qrcode.show', $vehicle) }}"
                                   class="bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-xs font-medium text-center hover:bg-gray-100">
                                    📱 QR Code
                                </a>
                            </div>

                            {{-- View / Delete --}}
                            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                                <a href="{{ route('vehicles.show', $vehicle) }}"
                                   class="flex-1 text-center bg-blue-600 text-white px-3 py-2 rounded-lg text-xs font-medium hover:bg-blue-700">
                                    View Details
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this vehicle?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-50 text-red-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-red-100">
                                        Remove
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>