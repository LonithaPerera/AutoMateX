<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ __('app.fuel_tracker_label') }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.fuel_tracker_label') }}</p>
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
            {{ __('app.log_btn') }}
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
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.avg_kml') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">
                {{ number_format($totalLiters,1) }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.total_liters') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,102,255,0.05);border-color:rgba(0,102,255,0.15);">
            <p class="heading text-xl font-bold" style="color:#6699ff;">
                {{ number_format($totalCost) }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.total_lkr') }}</p>
        </div>
    </div>

    {{-- Efficiency Trend Chart --}}
    @if($fuelLogs->where('km_per_liter', '!=', null)->count() >= 2)
    @php
        $chartLogs = $fuelLogs->whereNotNull('km_per_liter')->sortBy('km_reading')->values();
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-5 border fade-in fade-in-2" style="border-color:rgba(74,222,128,0.15);">
        <p class="section-label mb-3">{{ __('app.efficiency_trend_label') }}</p>
        <canvas id="efficiencyChart" height="120"></canvas>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('efficiencyChart');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLogs->map(fn($l) => \Carbon\Carbon::parse($l->date)->format('d M'))->values()) !!},
                datasets: [{
                    label: 'km/L',
                    data: {!! json_encode($chartLogs->map(fn($l) => round($l->km_per_liter, 2))->values()) !!},
                    borderColor: '#4ade80',
                    backgroundColor: 'rgba(74,222,128,0.08)',
                    pointBackgroundColor: '#4ade80',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' km/L'
                        }
                    }
                },
                scales: {
                    x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
                    y: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' }, beginAtZero: false }
                }
            }
        });
    });
    </script>
    @endif

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-1"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" /><span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Fuel log list --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.fillup_history') }}</p>

    @php
        // Build cost-per-km map: sort ascending by km_reading, compute per log
        $sortedForCost = $fuelLogs->sortBy('km_reading')->values();
        $costPerKmMap  = [];
        foreach ($sortedForCost as $idx => $l) {
            if ($idx > 0) {
                $kmDriven = $l->km_reading - $sortedForCost[$idx - 1]->km_reading;
                $costPerKmMap[$l->id] = $kmDriven > 0 ? round($l->cost / $kmDriven, 2) : null;
            } else {
                $costPerKmMap[$l->id] = null;
            }
        }
    @endphp

    @forelse($fuelLogs as $index => $log)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-start gap-3">
                <div class="rounded-xl p-2 mt-0.5" style="background:rgba(74,222,128,0.1);">
                    <x-heroicon-o-beaker class="w-4 h-4" style="color:#4ade80;" />
                </div>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $log->liters }} {{ __('app.liters_filled') }}
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
                <p class="text-xs" style="color:#64748b;">{{ __('app.km_unit') }}</p>
            </div>
            @endif
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-4 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.odometer') }}</p>
                <p class="mono text-sm font-bold text-white">{{ number_format($log->km_reading) }}</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.liters') }}</p>
                <p class="mono text-sm font-bold text-cyan">{{ $log->liters }} L</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.cost') }}</p>
                <p class="mono text-sm font-bold" style="color:#4ade80;">{{ number_format($log->cost) }}</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.cost_per_km') }}</p>
                <p class="mono text-sm font-bold" style="color:#f59e0b;">
                    @if($costPerKmMap[$log->id] ?? null)
                        {{ $costPerKmMap[$log->id] }}
                    @else
                        —
                    @endif
                </p>
            </div>
        </div>

        {{-- Edit + Delete --}}
        <div class="flex gap-2">
            <a href="{{ route('fuel.edit', [$vehicle, $log]) }}"
               class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                {{ __('app.edit_log_btn') }}
            </a>
            <form method="POST" action="{{ route('fuel.destroy', [$vehicle, $log]) }}"
                  onsubmit="return confirm('{{ __('app.delete_fuel_confirm') }}')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,60,60,0.06);border:1px solid rgba(255,60,60,0.15);color:#f87171;">
                    {{ __('app.delete_btn') }}
                </button>
            </form>
        </div>
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border fade-in fade-in-3"
             style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-beaker class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_fuel_logs') }}</p>
            <p class="text-sm mb-5" style="color:#64748b;">{{ __('app.track_fuel_hint') }}</p>
            <a href="{{ route('fuel.create', $vehicle) }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                {{ __('app.log_fuel_btn') }}
            </a>
        </div>
    @endforelse

    {{-- Back --}}
    <div class="mt-2">
        <a href="{{ route('vehicles.show', $vehicle) }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            {{ __('app.back_to_vehicles') }}
        </a>
    </div>

</div>
</x-app-layout>
