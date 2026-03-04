<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔧 Add Service Record — {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">

                <form action="{{ route('service.store', $vehicle) }}" method="POST">
                    @csrf

                    {{-- Service Type --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Service Type *
                        </label>
                        <input type="text" name="service_type"
                               value="{{ old('service_type') }}"
                               placeholder="e.g. Engine Oil Change, Brake Pad Replacement"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('service_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Category *
                        </label>
                        <select name="type"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="maintenance" {{ old('type') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>
                            <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>
                                Repair
                            </option>
                            <option value="inspection" {{ old('type') == 'inspection' ? 'selected' : '' }}>
                                Inspection
                            </option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Service Date --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Service Date *
                        </label>
                        <input type="date" name="service_date"
                               value="{{ old('service_date', date('Y-m-d')) }}"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('service_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mileage at Service --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Mileage at Service (km) *
                        </label>
                        <input type="number" name="mileage_at_service"
                               value="{{ old('mileage_at_service', $vehicle->mileage) }}"
                               min="0"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('mileage_at_service')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cost --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Cost (LKR) *
                        </label>
                        <input type="number" name="cost"
                               value="{{ old('cost', 0) }}"
                               step="0.01" min="0"
                               placeholder="e.g. 4500.00"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('cost')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Garage Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Garage / Service Center
                        </label>
                        <input type="text" name="garage_name"
                               value="{{ old('garage_name') }}"
                               placeholder="e.g. AutoHub Lanka, Nugegoda"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('garage_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">
                            Notes
                        </label>
                        <textarea name="notes" rows="3"
                                  placeholder="Any additional details about this service..."
                                  class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Save Service Record
                        </button>
                        <a href="{{ route('service.index', $vehicle) }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>