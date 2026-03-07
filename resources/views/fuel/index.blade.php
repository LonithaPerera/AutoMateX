<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">// FUEL TRACKER</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ $vehicle->make }} <span class="text-cyan">{{ $vehicle->model }}</span>
            </h1>
            <p class="text-xs mono mt-0.5" style="color:#64748b;">
                {{ $vehicle->year }} · {{ number_format($vehicle->mileage) }} km
            </p>
        </div>
        <a href="{{ route('fuel.create', $vehicle) }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            + LOG
        </a>
    </div>

    {{-- Summary cards --}}
    @php
        $avgEff = $fuelLogs->whereNotNull('km_per_liter')->avg('km_per_liter');
        $totalCost = $fuelLogs->sum('cost');
        $totalLiters = $fuelLogs->sum('liters');
    @endphp
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">
                {{ $avgEff ? number_format($avgEff,1) : '—' }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">avg km/L</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">
                {{ number_format($totalLiters,1) }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">total liters</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,102,255,0.05);border-color:rgba(0,102,255,0.15);">
            <p class="heading text-xl font-bold" style="color:#6699ff;">
                {{ number_format($totalCost) }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">total LKR</p>
        </div>
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-1"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">✓ {{ session('success') }}</span>
        </div>
    @endif

    {{-- Fuel log list --}}
    <p class="section-label mb-3 fade-in fade-in-2">// FILL-UP HISTORY</p>

    @forelse($fuelLogs as $index => $log)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-start gap-3">
                <div class="rounded-xl p-2 mt-0.5" style="background:rgba(74,222,128,0.1);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2">
                        <path d="M3 22V8a2 2 0 012-2h6a2 2 0 012 2v14M3 22h10M13 22V11l4-4 4 4v11M13 22h8"/>
                        <path d="M7 22V15h2v7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $log->liters }} L filled
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}
                        @if($log->fuel_station) · {{ $log->fuel_station }} @endif
                    </p>
                </div>
            </div>
            @if($log->km_per_liter)
            <div class="text-right">
                <p class="heading font-bold text-lg" style="color:#4ade80;">{{ number_format($log->km_per_liter,1) }}</p>
                <p class="text-xs" style="color:#64748b;">km/L</p>
            </div>
            @endif
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">Odometer</p>
                <p class="mono text-sm font-bold text-white">{{ number_format($log->km_reading) }} km</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">Liters</p>
                <p class="mono text-sm font-bold text-cyan">{{ $log->liters }} L</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">Cost</p>
                <p class="mono text-sm font-bold" style="color:#4ade80;">LKR {{ number_format($log->cost) }}</p>
            </div>
        </div>

        {{-- Delete --}}
        <form method="POST" action="{{ route('fuel.destroy', [$vehicle, $log]) }}"
              onsubmit="return confirm('Delete this fuel log?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(255,60,60,0.06);border:1px solid rgba(255,60,60,0.15);color:#f87171;">
                🗑 DELETE
            </button>
        </form>
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border fade-in fade-in-3"
             style="border-color:rgba(255,255,255,0.06);">
            <div class="text-5xl mb-4">⛽</div>
            <p class="heading text-xl font-bold text-white mb-1">No Fuel Logs Yet</p>
            <p class="text-sm mb-5" style="color:#64748b;">Track your fuel to monitor efficiency</p>
            <a href="{{ route('fuel.create', $vehicle) }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                + LOG FUEL
            </a>
        </div>
    @endforelse

    {{-- Back --}}
    <div class="mt-2">
        <a href="{{ route('vehicles.index') }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            ← Back to Vehicles
        </a>
    </div>

</div>
</x-app-layout>