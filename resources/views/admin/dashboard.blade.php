<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            👨‍💼 Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-4xl font-bold text-blue-600 mt-1">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-green-500">
                    <p class="text-gray-500 text-sm">Total Vehicles</p>
                    <p class="text-4xl font-bold text-green-600 mt-1">{{ $stats['total_vehicles'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-purple-500">
                    <p class="text-gray-500 text-sm">Service Records</p>
                    <p class="text-4xl font-bold text-purple-600 mt-1">{{ $stats['total_service_logs'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-orange-500">
                    <p class="text-gray-500 text-sm">Fuel Logs</p>
                    <p class="text-4xl font-bold text-orange-500 mt-1">{{ $stats['total_fuel_logs'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Total Bookings</p>
                    <p class="text-4xl font-bold text-yellow-600 mt-1">{{ $stats['total_bookings'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-red-500">
                    <p class="text-gray-500 text-sm">Pending Bookings</p>
                    <p class="text-4xl font-bold text-red-500 mt-1">{{ $stats['pending_bookings'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-teal-500">
                    <p class="text-gray-500 text-sm">Completed Bookings</p>
                    <p class="text-4xl font-bold text-teal-600 mt-1">{{ $stats['completed_bookings'] }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center border-t-4 border-gray-500">
                    <p class="text-gray-500 text-sm">Registered Garages</p>
                    <p class="text-4xl font-bold text-gray-600 mt-1">{{ $stats['total_garages'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Recent Users --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-700">👥 Recent Users</h3>
                        <a href="{{ route('admin.users') }}"
                           class="text-blue-600 text-sm hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($recentUsers as $user)
                            <div class="px-6 py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-sm text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($user->role === 'admin') bg-purple-100 text-purple-700
                                    @elseif($user->role === 'garage') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Recent Bookings --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="font-bold text-gray-700">📅 Recent Bookings</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentBookings as $booking)
                            <div class="px-6 py-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-sm text-gray-800">
                                            {{ $booking->service_type }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $booking->vehicle->user->name }} →
                                            {{ $booking->garage->name }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            {{ $booking->booking_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-700
                                        @elseif($booking->status === 'completed') bg-gray-100 text-gray-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-4 text-gray-400 text-sm">No bookings yet.</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>