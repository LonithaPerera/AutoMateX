<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🧠 Maintenance Suggestions — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <a href="{{ route('vehicles.show', $vehicle) }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                ← Back to Vehicle
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Vehicle Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Current Mileage</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        {{ number_format($currentMileage) }} km
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Avg Daily Driving</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        {{ $avgDailyKm ? $avgDailyKm . ' km/day' : 'N/A' }}
                    </p>
                    @if(!$avgDailyKm)
                        <p class="text-xs text-gray-400 mt-1">
                            Add 2+ fuel logs to calculate
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Overdue Services</p>
                    <p class="text-3xl font-bold text-red-500 mt-1">
                        {{ collect($suggestions)->where('status', 'overdue')->count() }}
                    </p>
                </div>
            </div>

            {{-- Suggestions List --}}
            <div class="space-y-4">
                @foreach($suggestions as $s)
                    <div class="bg-white rounded-lg shadow p-6
                        @if($s['status'] === 'overdue') border-l-4 border-red-500
                        @elseif($s['status'] === 'due_soon') border-l-4 border-yellow-400
                        @else border-l-4 border-green-400
                        @endif">

                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-bold text-gray-800">
                                        {{ $s['service_name'] }}
                                    </h3>

                                    {{-- Status Badge --}}
                                    @if($s['status'] === 'overdue')
                                        <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full">
                                            ⚠️ OVERDUE
                                        </span>
                                    @elseif($s['status'] === 'due_soon')
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">
                                            🔔 DUE SOON
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                                            ✅ OK
                                        </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $s['description'] }}
                                </p>

                                <div class="mt-3 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-400 text-xs">Service Interval</p>
                                        <p class="font-medium">Every {{ number_format($s['interval_km']) }} km</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs">Next Due At</p>
                                        <p class="font-medium">{{ number_format($s['next_due_km']) }} km</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs">KM Remaining</p>
                                        <p class="font-medium
                                            @if($s['km_left'] <= 0) text-red-600
                                            @elseif($s['km_left'] <= 500) text-yellow-600
                                            @else text-green-600
                                            @endif">
                                            @if($s['km_left'] <= 0)
                                                {{ number_format(abs($s['km_left'])) }} km overdue
                                            @else
                                                {{ number_format($s['km_left']) }} km
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-xs">Est. Days Left</p>
                                        <p class="font-medium">
                                            @if($s['days_left'] !== null)
                                                @if($s['days_left'] <= 0)
                                                    <span class="text-red-600">Overdue</span>
                                                @else
                                                    ~{{ $s['days_left'] }} days
                                                @endif
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($s['last_done_date'])
                                    <p class="text-xs text-gray-400 mt-2">
                                        Last done: {{ $s['last_done_date'] }}
                                        at {{ number_format($s['last_done_km']) }} km
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 mt-2">
                                        No record of this service yet
                                    </p>
                                @endif
                            </div>

                            {{-- Log Service Button --}}
                            <a href="{{ route('service.create', $vehicle) }}"
                               class="ml-4 bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">
                                + Log Service
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>