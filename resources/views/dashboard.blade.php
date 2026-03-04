<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 rounded-2xl p-8 mb-8 text-white shadow-lg">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold">
                            Welcome back, {{ Auth::user()->name }}! 👋
                        </h1>
                        <p class="mt-2 text-blue-100">
                            Manage your vehicles, track services, and stay on top of maintenance.
                        </p>
                        <span class="inline-block mt-3 bg-white text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                            {{ str_replace('_', ' ', Auth::user()->role) }}
                        </span>
                    </div>
                    <div class="text-6xl">🚗</div>
                </div>
            </div>

            {{-- Stats Row --}}
            @php
                $vehicleCount     = Auth::user()->vehicles()->count();
                $serviceCount     = \App\Models\ServiceLog::whereIn('vehicle_id', Auth::user()->vehicles()->pluck('id'))->count();
                $fuelCount        = \App\Models\FuelLog::whereIn('vehicle_id', Auth::user()->vehicles()->pluck('id'))->count();
                $bookingCount     = Auth::user()->bookings()->count();
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow p-5 text-center border-t-4 border-blue-500">
                    <p class="text-3xl font-bold text-blue-600">{{ $vehicleCount }}</p>
                    <p class="text-gray-500 text-sm mt-1">My Vehicles</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center border-t-4 border-green-500">
                    <p class="text-3xl font-bold text-green-600">{{ $serviceCount }}</p>
                    <p class="text-gray-500 text-sm mt-1">Service Records</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center border-t-4 border-orange-500">
                    <p class="text-3xl font-bold text-orange-500">{{ $fuelCount }}</p>
                    <p class="text-gray-500 text-sm mt-1">Fuel Logs</p>
                </div>
                <div class="bg-white rounded-xl shadow p-5 text-center border-t-4 border-purple-500">
                    <p class="text-3xl font-bold text-purple-600">{{ $bookingCount }}</p>
                    <p class="text-gray-500 text-sm mt-1">Bookings</p>
                </div>
            </div>

            {{-- Quick Actions --}}
            <h2 class="text-lg font-bold text-gray-700 mb-4">⚡ Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <a href="{{ route('vehicles.index') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">🚗</div>
                    <p class="text-sm font-medium text-gray-700">My Vehicles</p>
                </a>
                <a href="{{ route('vehicles.create') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">➕</div>
                    <p class="text-sm font-medium text-gray-700">Add Vehicle</p>
                </a>
                <a href="{{ route('parts.index') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">🔩</div>
                    <p class="text-sm font-medium text-gray-700">Parts DB</p>
                </a>
                <a href="{{ route('garages.index') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">🏪</div>
                    <p class="text-sm font-medium text-gray-700">Find Garage</p>
                </a>
                <a href="{{ route('bookings.index') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">📅</div>
                    <p class="text-sm font-medium text-gray-700">My Bookings</p>
                </a>
                <a href="{{ route('garage.dashboard') }}"
                   class="bg-white rounded-xl shadow p-5 text-center hover:shadow-md hover:-translate-y-1 transition-all">
                    <div class="text-3xl mb-2">🏁</div>
                    <p class="text-sm font-medium text-gray-700">Garage Panel</p>
                </a>
            </div>

            {{-- My Vehicles Section --}}
            @if($vehicleCount > 0)
                <h2 class="text-lg font-bold text-gray-700 mb-4">🚗 My Vehicles</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach(Auth::user()->vehicles as $vehicle)
                        <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">
                                        {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
                                    </h3>
                                    <p class="text-sm text-gray-400">
                                        {{ $vehicle->license_plate ?? 'No plate' }}
                                        &nbsp;·&nbsp; {{ ucfirst($vehicle->fuel_type) }}
                                    </p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                                    {{ number_format($vehicle->mileage) }} km
                                </span>
                            </div>
                            <div class="flex gap-2 flex-wrap mt-4">
                                <a href="{{ route('suggestions.index', $vehicle) }}"
                                   class="bg-purple-100 text-purple-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-purple-200">
                                    🧠 Suggestions
                                </a>
                                <a href="{{ route('service.index', $vehicle) }}"
                                   class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-green-200">
                                    🔧 Service
                                </a>
                                <a href="{{ route('fuel.index', $vehicle) }}"
                                   class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-orange-200">
                                    ⛽ Fuel
                                </a>
                                <a href="{{ route('qrcode.show', $vehicle) }}"
                                   class="bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs font-medium hover:bg-gray-200">
                                    📱 QR
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Admin Quick Link --}}
            @if(Auth::user()->role === 'admin')
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-purple-800">👨‍💼 Admin Panel</h3>
                            <p class="text-sm text-purple-600 mt-1">
                                View system stats and manage all users.
                            </p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}"
                           class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            Open Admin
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>