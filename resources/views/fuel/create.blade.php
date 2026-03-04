<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ⛽ Add Fuel Log — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">

                <form action="{{ route('fuel.store', $vehicle) }}" method="POST">
                    @csrf

                    {{-- Date --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Date *</label>
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Odometer Reading --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Odometer Reading (km) *</label>
                        <input type="number" name="km_reading"
                               value="{{ old('km_reading', $vehicle->mileage) }}"
                               min="0"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('km_reading') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Liters --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Liters Filled *</label>
                        <input type="number" name="liters" value="{{ old('liters') }}"
                               step="0.01" min="0.1" placeholder="e.g. 35.50"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('liters') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Cost --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Total Cost (LKR) *</label>
                        <input type="number" name="cost" value="{{ old('cost') }}"
                               step="0.01" min="0" placeholder="e.g. 9500.00"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('cost') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fuel Station --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fuel Station</label>
                        <input type="text" name="fuel_station" value="{{ old('fuel_station') }}"
                               placeholder="e.g. Lanka IOC, Nugegoda"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('fuel_station') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" rows="2" placeholder="Any additional notes..."
                                  class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Save Fuel Log
                        </button>
                        <a href="{{ route('fuel.index', $vehicle) }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>