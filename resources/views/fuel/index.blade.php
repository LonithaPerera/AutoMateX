<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                ⛽ Fuel Tracker — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('vehicles.show', $vehicle) }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                    ← Back to Vehicle
                </a>
                <a href="{{ route('fuel.create', $vehicle) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add Fuel Log
                </a>
            </div>
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

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Avg Fuel Efficiency</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        {{ $avgKmPerLiter ? number_format($avgKmPerLiter, 1) . ' km/L' : 'N/A' }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Total Fuel Cost</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        LKR {{ number_format($totalCost, 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Total Liters Logged</p>
                    <p class="text-3xl font-bold text-orange-500 mt-1">
                        {{ number_format($totalLiters, 1) }} L
                    </p>
                </div>
            </div>

            {{-- Fuel Logs Table --}}
            @if($fuelLogs->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500 text-lg">No fuel logs yet.</p>
                    <a href="{{ route('fuel.create', $vehicle) }}"
                       class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Add Your First Fuel Log
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Odometer</th>
                                <th class="px-6 py-3 text-left">Liters</th>
                                <th class="px-6 py-3 text-left">Cost (LKR)</th>
                                <th class="px-6 py-3 text-left">Efficiency</th>
                                <th class="px-6 py-3 text-left">Station</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($fuelLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $log->date->format('d M Y') }}</td>
                                    <td class="px-6 py-4">{{ number_format($log->km_reading) }} km</td>
                                    <td class="px-6 py-4">{{ $log->liters }} L</td>
                                    <td class="px-6 py-4">{{ number_format($log->cost, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if($log->km_per_liter)
                                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">
                                                {{ $log->km_per_liter }} km/L
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">First entry</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $log->fuel_station ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('fuel.destroy', [$vehicle, $log]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this log?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 text-xs">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>