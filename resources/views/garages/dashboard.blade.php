<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🏪 Garage Dashboard — {{ $garage->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Total Bookings</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $bookings->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">
                        {{ $bookings->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Confirmed</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        {{ $bookings->where('status', 'confirmed')->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Completed</p>
                    <p class="text-3xl font-bold text-gray-600 mt-1">
                        {{ $bookings->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>

            {{-- Bookings List --}}
            @if($bookings->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500">No bookings yet.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start flex-wrap gap-4">
                                <div>
                                    <h3 class="font-bold text-gray-800">
                                        {{ $booking->service_type }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        🚗 {{ $booking->vehicle->year }}
                                        {{ $booking->vehicle->make }}
                                        {{ $booking->vehicle->model }}
                                        ({{ $booking->vehicle->license_plate ?? 'No plate' }})
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        👤 Owner: {{ $booking->vehicle->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        📅 {{ $booking->booking_date->format('d M Y') }}
                                        at {{ $booking->booking_time }}
                                    </p>
                                    @if($booking->notes)
                                        <p class="text-sm text-gray-400 mt-1">
                                            💬 {{ $booking->notes }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Status Badge --}}
                                <div class="text-right">
                                    @if($booking->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">
                                            ⏳ Pending
                                        </span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                                            ✅ Confirmed
                                        </span>
                                    @elseif($booking->status === 'completed')
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-bold">
                                            🏁 Completed
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">
                                            ❌ Cancelled
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Update Form --}}
                            <form action="{{ route('garage.updateBooking', $booking) }}"
                                  method="POST"
                                  class="mt-4 pt-4 border-t border-gray-100">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Update Status</label>
                                        <select name="status"
                                                class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Invoice Amount (LKR)</label>
                                        <input type="number" name="invoice_amount" step="0.01"
                                               value="{{ $booking->invoice_amount }}"
                                               placeholder="e.g. 5000.00"
                                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">Invoice Notes</label>
                                        <input type="text" name="invoice_notes"
                                               value="{{ $booking->invoice_notes }}"
                                               placeholder="e.g. Oil + filter changed"
                                               class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <button type="submit"
                                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                                    Update Booking
                                </button>
                            </form>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>