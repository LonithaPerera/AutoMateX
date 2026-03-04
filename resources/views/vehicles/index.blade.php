<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Vehicles
            </h2>
            <a href="{{ route('vehicles.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Vehicle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- No Vehicles Message --}}
            @if($vehicles->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500 text-lg">You have no vehicles yet.</p>
                    <a href="{{ route('vehicles.create') }}"
                       class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Add Your First Vehicle
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($vehicles as $vehicle)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
                            </h3>
                            <div class="mt-2 text-sm text-gray-600 space-y-1">
                                <p>🛣️ Mileage: {{ number_format($vehicle->mileage) }} km</p>
                                <p>⛽ Fuel: {{ ucfirst($vehicle->fuel_type) }}</p>
                                @if($vehicle->license_plate)
                                    <p>🔖 Plate: {{ $vehicle->license_plate }}</p>
                                @endif
                                @if($vehicle->vin)
                                    <p>🔑 VIN: {{ $vehicle->vin }}</p>
                                @endif
                            </div>
                            <div class="mt-4 flex gap-2">
                                <a href="{{ route('vehicles.show', $vehicle) }}"
                                   class="bg-gray-100 text-gray-700 px-3 py-1 rounded text-sm hover:bg-gray-200">
                                    View Details
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200">
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