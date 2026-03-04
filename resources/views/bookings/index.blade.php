<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📅 My Bookings
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($bookings->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500 text-lg">No bookings yet.</p>
                    <a href="{{ route('garages.index') }}"
                       class="mt-4 inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                        Browse Garages
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex justify-between items-start flex-wrap gap-4">
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">
                                        {{ $booking->service_type }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        🏪 {{ $booking->garage->name }},
                                        {{ $booking->garage->city }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        📅 {{ $booking->booking_date->format('d M Y') }}
                                        at {{ $booking->booking_time }}
                                    </p>
                                </div>

                                {{-- Status --}}
                                <div>
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

                            {{-- Invoice (if completed) --}}
                            @if($booking->invoice_amount)
                                <div class="mt-4 pt-4 border-t border-gray-100 bg-green-50 rounded p-3">
                                    <p class="text-sm font-bold text-green-800">
                                        🧾 Invoice: LKR {{ number_format($booking->invoice_amount, 2) }}
                                    </p>
                                    @if($booking->invoice_notes)
                                        <p class="text-xs text-green-600 mt-1">
                                            {{ $booking->invoice_notes }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>