<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏪 Browse Garages
            </h2>
            <a href="{{ route('garages.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Register My Garage
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($garages->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500 text-lg">No garages registered yet.</p>
                    <a href="{{ route('garages.create') }}"
                       class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Register Your Garage
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($garages as $garage)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-bold text-gray-800">
                                {{ $garage->name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                📍 {{ $garage->address }}, {{ $garage->city }}
                            </p>
                            <p class="text-sm text-gray-500">
                                📞 {{ $garage->phone }}
                            </p>
                            @if($garage->specialization)
                                <p class="text-sm text-blue-600 mt-1">
                                    🔧 {{ $garage->specialization }}
                                </p>
                            @endif
                            @if($garage->description)
                                <p class="text-sm text-gray-400 mt-2">
                                    {{ Str::limit($garage->description, 80) }}
                                </p>
                            @endif
                            <div class="mt-4">
                                <a href="{{ route('bookings.create', $garage) }}"
                                   class="w-full block text-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                    📅 Book Appointment
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>