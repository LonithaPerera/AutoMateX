<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔩 Parts Verification Database
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Info Banner --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800 text-sm">
                    🛡️ <strong>Verify your spare parts before buying.</strong>
                    Search by vehicle make/model or part name to find the correct
                    OEM part number and avoid counterfeit parts.
                </p>
            </div>

            {{-- Search Form --}}
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <form method="GET" action="{{ route('parts.index') }}">

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

                        {{-- Make --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Vehicle Make
                            </label>
                            <select name="make"
                                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Makes</option>
                                @foreach($makes as $make)
                                    <option value="{{ $make }}"
                                        {{ request('make') == $make ? 'selected' : '' }}>
                                        {{ $make }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Model --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Vehicle Model
                            </label>
                            <select name="model"
                                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Models</option>
                                @foreach($models as $model)
                                    <option value="{{ $model }}"
                                        {{ request('model') == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Part Category
                            </label>
                            <select name="category"
                                    class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}"
                                        {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Search by Part / OEM Number
                            </label>
                            <input type="text" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="e.g. Oil Filter, 90915-YZZD2"
                                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            🔍 Search Parts
                        </button>
                        <a href="{{ route('parts.index') }}"
                           class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Clear
                        </a>
                    </div>

                </form>
            </div>

            {{-- Results --}}
            @if(request()->filled('make') || request()->filled('search'))
                <div class="mb-4 flex justify-between items-center">
                    <p class="text-gray-600 text-sm">
                        Found <strong>{{ $results->count() }}</strong> part(s)
                    </p>
                </div>

                @if($results->isEmpty())
                    <div class="bg-white rounded-lg shadow p-8 text-center">
                        <p class="text-gray-500">No parts found for your search.</p>
                        <p class="text-gray-400 text-sm mt-1">
                            Try a different make, model, or part name.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($results as $part)
                            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">

                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-bold text-gray-800 text-lg">
                                            {{ $part->part_name }}
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $part->vehicle_make }} {{ $part->vehicle_model }}
                                            ({{ $part->vehicle_year_from }}–{{ $part->vehicle_year_to }})
                                        </p>
                                    </div>
                                    <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">
                                        {{ $part->part_category }}
                                    </span>
                                </div>

                                <div class="space-y-2 text-sm">
                                    {{-- OEM Part Number --}}
                                    <div class="bg-green-50 border border-green-200 rounded p-3">
                                        <p class="text-xs text-green-600 font-medium uppercase">
                                            ✅ OEM Part Number
                                        </p>
                                        <p class="font-bold text-green-800 text-base mt-1 font-mono">
                                            {{ $part->oem_part_number }}
                                        </p>
                                        <p class="text-xs text-green-600 mt-1">
                                            Brand: {{ $part->brand }}
                                        </p>
                                    </div>

                                    {{-- Alternative --}}
                                    @if($part->alternative_part_number)
                                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3">
                                            <p class="text-xs text-yellow-600 font-medium uppercase">
                                                🔄 Verified Alternative
                                            </p>
                                            <p class="font-bold text-yellow-800 text-base mt-1 font-mono">
                                                {{ $part->alternative_part_number }}
                                            </p>
                                        </div>
                                    @endif

                                    {{-- Description --}}
                                    @if($part->description)
                                        <p class="text-gray-500 text-xs mt-2">
                                            ℹ️ {{ $part->description }}
                                        </p>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                {{-- Default state - show all makes --}}
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <p class="text-4xl mb-4">🔩</p>
                    <p class="text-gray-700 font-medium text-lg">
                        Select a vehicle make or search for a part to get started
                    </p>
                    <p class="text-gray-400 text-sm mt-2">
                        Currently covers: Toyota Vitz, Toyota Premio, Toyota Aqua,
                        Suzuki Alto, Honda Fit
                    </p>
                    <div class="flex flex-wrap justify-center gap-2 mt-4">
                        @foreach($makes as $make)
                            <a href="{{ route('parts.index') }}?make={{ $make }}"
                               class="bg-blue-100 text-blue-700 px-4 py-2 rounded hover:bg-blue-200">
                                {{ $make }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>