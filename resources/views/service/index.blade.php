<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🔧 Service History — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('vehicles.show', $vehicle) }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                    ← Back to Vehicle
                </a>
                <a href="{{ route('service.create', $vehicle) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Add Service
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

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Total Services</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">
                        {{ $serviceLogs->count() }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Total Money Spent</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">
                        LKR {{ number_format($totalCost, 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-500 text-sm">Current Mileage</p>
                    <p class="text-3xl font-bold text-orange-500 mt-1">
                        {{ number_format($vehicle->mileage) }} km
                    </p>
                </div>
            </div>

            {{-- Service Log Timeline --}}
            @if($serviceLogs->isEmpty())
                <div class="bg-white p-8 rounded shadow text-center">
                    <p class="text-gray-500 text-lg">No service records yet.</p>
                    <a href="{{ route('service.create', $vehicle) }}"
                       class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Add First Service Record
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">Date</th>
                                <th class="px-6 py-3 text-left">Service Type</th>
                                <th class="px-6 py-3 text-left">Category</th>
                                <th class="px-6 py-3 text-left">Mileage</th>
                                <th class="px-6 py-3 text-left">Cost (LKR)</th>
                                <th class="px-6 py-3 text-left">Garage</th>
                                <th class="px-6 py-3 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($serviceLogs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $log->service_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium">
                                        {{ $log->service_type }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->type === 'maintenance')
                                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                                Maintenance
                                            </span>
                                        @elseif($log->type === 'repair')
                                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                                Repair
                                            </span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                                                Inspection
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ number_format($log->mileage_at_service) }} km
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ number_format($log->cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $log->garage_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('service.destroy', [$vehicle, $log]) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this record?')">
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