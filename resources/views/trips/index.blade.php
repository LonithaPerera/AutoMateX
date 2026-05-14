<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <nav class="flex items-center gap-1.5 text-xs mb-2" style="color:#64748b;">
                <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
                <span>›</span>
                <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
                <span>›</span>
                <span style="color:#94a3b8;">{{ __('app.trip_log_label') }}</span>
            </nav>
            <p class="section-label mb-1">{{ __('app.trip_log_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">{{ __('app.trip_history_title') }}</h1>
            <p class="text-xs mono mt-1" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
        </div>
        <div class="flex flex-col gap-2 items-end mt-1">
            <a href="{{ route('trips.create', $vehicle) }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 flex-shrink-0"
               style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 16px rgba(0,245,255,0.25);">
                <x-heroicon-o-plus class="w-3.5 h-3.5" />
                {{ __('app.log_trip_btn') }}
            </a>
            @if($tripCount > 0)
            <a href="{{ route('trips.pdf', $vehicle) }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />{{ __('app.download_pdf_btn') }}
            </a>
            @endif
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
        <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
        <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Stats --}}
    @if($tripCount > 0)
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="glass-bright rounded-2xl p-3 border text-center" style="border-color:rgba(0,245,255,0.12);">
            <p class="mono text-lg font-bold text-white">{{ number_format($totalKm) }}</p>
            <p class="section-label mt-0.5" style="font-size:9px;">{{ __('app.total_km_label') }}</p>
        </div>
        <div class="glass-bright rounded-2xl p-3 border text-center" style="border-color:rgba(74,222,128,0.15);">
            <p class="mono text-lg font-bold" style="color:#4ade80;">{{ number_format($businessKm) }}</p>
            <p class="section-label mt-0.5" style="font-size:9px;">{{ __('app.business_km_label') }}</p>
        </div>
        <div class="glass-bright rounded-2xl p-3 border text-center" style="border-color:rgba(168,85,247,0.15);">
            <p class="mono text-lg font-bold" style="color:#a855f7;">{{ number_format($personalKm) }}</p>
            <p class="section-label mt-0.5" style="font-size:9px;">{{ __('app.personal_km_label') }}</p>
        </div>
    </div>
    @endif

    {{-- Trip list --}}
    @forelse($tripLogs as $index => $trip)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+2,5) }}"
         style="border-color:rgba(0,245,255,0.1);">
        <div class="flex items-start justify-between mb-2">
            <div>
                <p class="heading font-bold text-white text-sm">{{ $trip->trip_date->format('d M Y') }}</p>
                <p class="mono text-xs mt-0.5" style="color:#64748b;">
                    {{ number_format($trip->start_km) }} → {{ number_format($trip->end_km) }} km
                </p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $pColor = $trip->purpose === 'business' ? '#4ade80' : ($trip->purpose === 'personal' ? '#a855f7' : '#94a3b8');
                    $pBg    = $trip->purpose === 'business' ? 'rgba(74,222,128,0.1)' : ($trip->purpose === 'personal' ? 'rgba(168,85,247,0.1)' : 'rgba(148,163,184,0.1)');
                @endphp
                <span class="tag text-xs px-2 py-0.5 rounded-lg font-semibold heading"
                      style="background:{{ $pBg }};color:{{ $pColor }};border:1px solid {{ $pColor }}33;">
                    {{ __('app.purpose_' . $trip->purpose) }}
                </span>
                <span class="mono font-bold text-sm" style="color:var(--cyan);">{{ number_format($trip->distance) }} km</span>
            </div>
        </div>
        @if($trip->notes)
        <p class="text-xs mb-2" style="color:#64748b;">{{ $trip->notes }}</p>
        @endif
        <div class="flex gap-2">
            <a href="{{ route('trips.edit', [$vehicle, $trip]) }}"
               class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs transition-all"
               style="background:rgba(255,107,0,0.06);border:1px solid rgba(255,107,0,0.2);color:#ff6b00;">
                <x-heroicon-o-pencil-square class="w-3 h-3" /> {{ __('app.edit_btn') }}
            </a>
            <form method="POST" action="{{ route('trips.destroy', [$vehicle, $trip]) }}"
                  onsubmit="return confirm('{{ __('app.delete_confirm') }}')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs transition-all"
                        style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                    <x-heroicon-o-trash class="w-3 h-3" /> {{ __('app.delete_btn') }}
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="glass-bright rounded-2xl p-8 text-center border fade-in" style="border-color:rgba(0,245,255,0.08);">
        <x-heroicon-o-map class="w-10 h-10 mx-auto mb-3" style="color:#1e293b;" />
        <p class="heading font-bold text-white mb-1">{{ __('app.no_trips_yet') }}</p>
        <p class="text-xs mb-4" style="color:#475569;">{{ __('app.no_trips_hint') }}</p>
        <a href="{{ route('trips.create', $vehicle) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;">
            <x-heroicon-o-plus class="w-4 h-4" /> {{ __('app.log_trip_btn') }}
        </a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($tripLogs->hasPages())
    <div class="mt-2 mb-4 flex items-center justify-center gap-2">
        @if($tripLogs->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs" style="background:rgba(255,255,255,0.03);color:#334155;border:1px solid rgba(255,255,255,0.06);">{{ __('app.pagination_prev') }}</span>
        @else
            <a href="{{ $tripLogs->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs transition-all" style="background:rgba(0,245,255,0.06);color:var(--cyan);border:1px solid rgba(0,245,255,0.2);">{{ __('app.pagination_prev') }}</a>
        @endif
        <span class="mono text-xs" style="color:#64748b;">{{ $tripLogs->currentPage() }} / {{ $tripLogs->lastPage() }}</span>
        @if($tripLogs->hasMorePages())
            <a href="{{ $tripLogs->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs transition-all" style="background:rgba(0,245,255,0.06);color:var(--cyan);border:1px solid rgba(0,245,255,0.2);">{{ __('app.pagination_next') }}</a>
        @else
            <span class="px-3 py-1.5 rounded-lg text-xs" style="background:rgba(255,255,255,0.03);color:#334155;border:1px solid rgba(255,255,255,0.06);">{{ __('app.pagination_next') }}</span>
        @endif
    </div>
    @endif

</div>
</x-app-layout>
