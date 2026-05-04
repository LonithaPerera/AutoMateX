<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.archived_vehicles_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">{{ __('app.archived_vehicles_title') }}</h1>
        </div>
        <a href="{{ route('vehicles.index') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border mt-1"
           style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);color:#00f5ff;">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('app.nav_vehicles') }}
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl fade-in" style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);">
            <p class="text-xs flex items-center gap-1" style="color:#4ade80;"><x-heroicon-o-check class="w-3 h-3 flex-shrink-0" /> {{ session('success') }}</p>
        </div>
    @endif

    @forelse($vehicles as $index => $vehicle)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+2, 5) }}"
         style="border-color:rgba(248,113,113,0.12);opacity:0.85;">

        <div class="flex items-center gap-3 mb-3">
            {{-- Photo or placeholder --}}
            <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0"
                 style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                @if($vehicle->image)
                    <img src="{{ asset('storage/' . $vehicle->image) }}"
                         alt="{{ $vehicle->make }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-heroicon-o-truck class="w-6 h-6" style="color:#334155;" />
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="tag" style="background:rgba(248,113,113,0.1);color:#f87171;border:1px solid rgba(248,113,113,0.2);">ARCHIVED</span>
                    <span class="tag" style="background:rgba(255,255,255,0.05);color:#64748b;">{{ strtoupper($vehicle->fuel_type) }}</span>
                </div>
                <h3 class="heading font-bold text-white">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                <p class="mono text-xs mt-0.5" style="color:#64748b;">
                    {{ $vehicle->year }} · {{ $vehicle->license_plate ?? '—' }} · {{ number_format($vehicle->mileage) }} km
                </p>
            </div>
        </div>

        <p class="text-xs mb-3" style="color:#475569;">
            Archived {{ $vehicle->deleted_at->diffForHumans() }}
        </p>

        {{-- Restore --}}
        <form method="POST" action="{{ route('vehicles.restore', $vehicle->id) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="flex items-center gap-2 w-full justify-center py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);color:#4ade80;">
                <x-heroicon-o-arrow-path class="w-3.5 h-3.5" />
                {{ __('app.restore_vehicle_btn') }}
            </button>
        </form>
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border fade-in fade-in-2" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-archive-box class="w-12 h-12 mx-auto mb-4" style="color:#334155;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_archived_vehicles') }}</p>
            <p class="text-sm" style="color:#64748b;">All your vehicles are active.</p>
        </div>
    @endforelse

</div>
</x-app-layout>
