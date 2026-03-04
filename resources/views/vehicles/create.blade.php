<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Vehicle
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">

                <form action="{{ route('vehicles.store') }}" method="POST">
                    @csrf

                    {{-- Make --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Make *</label>
                        <input type="text" name="make" value="{{ old('make') }}"
                               placeholder="e.g. Toyota"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('make') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Model --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Model *</label>
                        <input type="text" name="model" value="{{ old('model') }}"
                               placeholder="e.g. Vitz"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('model') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Year --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Year *</label>
                        <input type="number" name="year" value="{{ old('year') }}"
                               placeholder="e.g. 2018" min="1990" max="2026"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Mileage --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Current Mileage (km) *</label>
                        <input type="number" name="mileage" value="{{ old('mileage', 0) }}"
                               min="0"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('mileage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fuel Type --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fuel Type *</label>
                        <select name="fuel_type"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="petrol">Petrol</option>
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('fuel_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- License Plate --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">License Plate</label>
                        <input type="text" name="license_plate" value="{{ old('license_plate') }}"
                               placeholder="e.g. CAB-1234"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('license_plate') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Color --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}"
                               placeholder="e.g. Silver"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('color') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- VIN --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">VIN Number</label>
                        <input type="text" name="vin" value="{{ old('vin') }}"
                               placeholder="e.g. JT2AE09W6H3456789" maxlength="17"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('vin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Save Vehicle
                        </button>
                        <a href="{{ route('vehicles.index') }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>