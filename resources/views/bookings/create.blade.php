<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📅 Book Appointment — {{ $garage->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow">

                {{-- Garage Info --}}
                <div class="bg-blue-50 rounded p-4 mb-6">
                    <p class="font-bold text-blue-800">{{ $garage->name }}</p>
                    <p class="text-sm text-blue-600">
                        📍 {{ $garage->address }}, {{ $garage->city }}
                        &nbsp;|&nbsp; 📞 {{ $garage->phone }}
                    </p>
                </div>

                <form action="{{ route('bookings.store', $garage) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Vehicle *</label>
                        <select name="vehicle_id"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Choose your vehicle --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
                                    ({{ $vehicle->license_plate ?? 'No plate' }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Service Required *</label>
                        <input type="text" name="service_type" value="{{ old('service_type') }}"
                               placeholder="e.g. Engine Oil Change, Brake Service"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('service_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Preferred Date *</label>
                        <input type="date" name="booking_date"
                               value="{{ old('booking_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('booking_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Preferred Time *</label>
                        <select name="booking_time"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Select time --</option>
                            <option value="08:00">8:00 AM</option>
                            <option value="09:00">9:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="13:00">1:00 PM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="15:00">3:00 PM</option>
                            <option value="16:00">4:00 PM</option>
                        </select>
                        @error('booking_time') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
                        <textarea name="notes" rows="3"
                                  placeholder="Describe the issue or any special requests..."
                                  class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                        @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                            Confirm Booking
                        </button>
                        <a href="{{ route('garages.index') }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>