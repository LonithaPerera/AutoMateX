<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.my_garage_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.my_vehicles_title') }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('vehicles.archived') }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15);color:#f87171;">
                <x-heroicon-o-archive-box class="w-3.5 h-3.5" />
                {{ __('app.view_archived_btn') }}
            </a>
            <a href="{{ route('vehicles.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
                {{ __('app.add_btn') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="fade-in fade-in-1 rounded-2xl p-3 mb-4 flex items-center gap-3 border"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    @forelse($vehicles as $index => $vehicle)
    @php
        // ── Multi-factor health score ──────────────────────────────────────
        $mileageScore = max(0, (int) round(40 - ($vehicle->mileage / 100000) * 40));

        $lastSvc = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)
            ->orderBy('service_date', 'desc')->first();
        if (!$lastSvc) {
            $svcScore = 10;
        } else {
            $daysSince = \Carbon\Carbon::parse($lastSvc->service_date)->diffInDays(now());
            $svcScore  = max(0, min(40, (int) round(40 - ($daysSince / 365) * 40)));
        }

        $schedules = \App\Models\MaintenanceSchedule::all();
        $overdueCount = 0;
        $overdueServiceName = null;
        $dueSoonServiceName = null;
        foreach ($schedules as $sched) {
            $lastMaint = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)
                ->where('service_type', 'like', '%' . explode(' ', $sched->service_name)[0] . '%')
                ->orderBy('mileage_at_service', 'desc')->first();
            $lastKm = $lastMaint ? $lastMaint->mileage_at_service : 0;
            $kmLeft = ($lastKm + $sched->interval_km) - $vehicle->mileage;
            if ($kmLeft <= 0) {
                $overdueCount++;
                if (!$overdueServiceName) $overdueServiceName = explode(' ', $sched->service_name)[0];
            } elseif ($kmLeft <= 500 && !$dueSoonServiceName) {
                $dueSoonServiceName = explode(' ', $sched->service_name)[0];
            }
        }
        $maintScore = max(0, 20 - ($overdueCount * 10));
        $health     = $mileageScore + $svcScore + $maintScore;

        $ringColor = $health >= 70 ? '#00f5ff' : ($health >= 40 ? '#ff6b00' : '#f87171');
        $ringGlow  = $health >= 70 ? 'rgba(0,245,255,0.3)' : ($health >= 40 ? 'rgba(255,107,0,0.3)' : 'rgba(248,113,113,0.3)');

        // Pre-compute stats
        $avg        = \App\Models\FuelLog::where('vehicle_id',$vehicle->id)->whereNotNull('km_per_liter')->avg('km_per_liter');
        $svc        = \App\Models\ServiceLog::where('vehicle_id',$vehicle->id)->count();
        $totalSpend = \App\Models\ServiceLog::where('vehicle_id',$vehicle->id)->sum('cost')
                    + \App\Models\FuelLog::where('vehicle_id',$vehicle->id)->sum('cost');
    @endphp
    <div class="glass-bright rounded-2xl overflow-hidden mb-4 vehicle-card fade-in fade-in-{{ $index + 2 }} animate-glow border">

        {{-- Vehicle photo strip --}}
        @if($vehicle->image)
        <div class="w-full overflow-hidden" style="height:120px;">
            <img src="{{ asset('storage/' . $vehicle->image) }}"
                 alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                 class="w-full h-full object-cover">
        </div>
        @endif

        <div class="p-4">
        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="tag" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.25);">{{ __('app.active_tag') }}</span>
                    <span class="tag" style="background:rgba(255,255,255,0.05);color:#64748b;">{{ strtoupper($vehicle->fuel_type) }}</span>
                    @if($overdueServiceName)
                    <span class="tag" style="background:rgba(248,113,113,0.12);color:#f87171;border:1px solid rgba(248,113,113,0.3);">{{ strtoupper($overdueServiceName) }} {{ strtoupper(__('app.overdue_status')) }}</span>
                    @elseif($dueSoonServiceName)
                    <span class="tag" style="background:rgba(255,107,0,0.1);color:#ff6b00;border:1px solid rgba(255,107,0,0.25);">{{ strtoupper($dueSoonServiceName) }} {{ strtoupper(__('app.due_soon_status')) }}</span>
                    @endif
                </div>
                <h3 class="heading text-xl font-bold text-white">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                <p class="text-xs mono mt-0.5" style="color:#64748b;">
                    {{ $vehicle->year }}
                    @if($vehicle->license_plate) · {{ $vehicle->license_plate }} @endif
                </p>
                @if($vehicle->notes)
                <p class="text-xs mt-1 leading-snug" style="color:#475569;">{{ Str::limit($vehicle->notes, 60) }}</p>
                @endif
            </div>
            {{-- Ring gauge --}}
            <div class="relative ring-wrap flex-shrink-0">
                <div class="health-ring"
                     data-health="{{ $health }}"
                     data-color="{{ $ringColor }}"
                     style="width:56px;height:56px;border-radius:50%;
                            background:conic-gradient({{ $ringColor }} 0% 0%,rgba(255,255,255,0.05) 0% 100%);
                            display:flex;align-items:center;justify-content:center;
                            box-shadow:0 0 15px {{ $ringGlow }};">
                    <div style="width:44px;height:44px;border-radius:50%;background:var(--card);
                                display:flex;align-items:center;justify-content:center;">
                        <span class="mono text-xs font-bold" style="color:{{ $ringColor }};">{{ $health }}%</span>
                    </div>
                </div>
                {{-- Tooltip --}}
                <div class="absolute right-0 bottom-full mb-2 w-44 rounded-xl p-2.5 ring-tip pointer-events-none z-50"
                     style="background:rgba(8,12,20,0.97);border:1px solid rgba(0,245,255,0.18);">
                    <p class="mono text-xs font-bold mb-1.5" style="color:{{ $ringColor }};">HEALTH: {{ $health }}/100</p>
                    <div class="text-xs space-y-1" style="color:#64748b;">
                        <div class="flex justify-between"><span>Mileage</span><span class="mono">{{ $mileageScore }}/40</span></div>
                        <div class="flex justify-between"><span>Last Service</span><span class="mono">{{ $svcScore }}/40</span></div>
                        <div class="flex justify-between"><span>Maintenance</span><span class="mono">{{ $maintScore }}/20</span></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-3 gap-2 mb-2">
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="mono text-sm font-bold text-white">{{ number_format($vehicle->mileage) }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.km_odo') }}</p>
            </div>
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="mono text-sm font-bold" style="color:#4ade80;">{{ $avg ? number_format($avg,1) : '—' }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">km/L</p>
            </div>
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="mono text-sm font-bold text-white">{{ $svc }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.km_services') }}</p>
            </div>
        </div>
        {{-- Last service + total spend --}}
        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl p-2 flex items-center gap-2" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <x-heroicon-o-wrench-screwdriver class="w-3 h-3 flex-shrink-0" style="color:#64748b;" />
                <div>
                    <p class="text-xs" style="color:#64748b;">{{ __('app.last_service_label') }}</p>
                    <p class="mono text-xs font-bold text-white">{{ $lastSvc ? $lastSvc->service_date->format('d M Y') : '—' }}</p>
                </div>
            </div>
            <div class="rounded-xl p-2 flex items-center gap-2" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <x-heroicon-o-banknotes class="w-3 h-3 flex-shrink-0" style="color:#64748b;" />
                <div>
                    <p class="text-xs" style="color:#64748b;">{{ __('app.total_spend_label') }}</p>
                    <p class="mono text-xs font-bold" style="color:#4ade80;">LKR {{ number_format($totalSpend) }}</p>
                </div>
            </div>
        </div>

        {{-- Action buttons --}}
        <div class="grid grid-cols-2 gap-2 mb-2">
            <a href="{{ route('vehicles.show', $vehicle) }}"
               class="py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                <x-heroicon-o-eye class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.view_details') }}
            </a>
            <a href="{{ route('qrcode.show', $vehicle) }}"
               class="py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(0,102,255,0.1);border:1px solid rgba(0,102,255,0.2);color:#6699ff;">
                <x-heroicon-o-qr-code class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.qr_pass') }}
            </a>
        </div>
        <div class="grid grid-cols-4 gap-2">
            <a href="{{ route('service.index', $vehicle) }}"
               class="py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#94a3b8;">
                <x-heroicon-o-wrench-screwdriver class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.service_action') }}
            </a>
            <a href="{{ route('fuel.index', $vehicle) }}"
               class="py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#94a3b8;">
                <x-heroicon-o-beaker class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.fuel_action') }}
            </a>
            <a href="{{ route('suggestions.index', $vehicle) }}"
               class="py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);color:#a855f7;">
                <x-heroicon-o-light-bulb class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.insights') }}
            </a>
            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}"
                  onsubmit="return confirm('{{ __('app.archive_confirm', ['name' => $vehicle->make . ' ' . $vehicle->model]) }}')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                    <x-heroicon-o-archive-box class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.archive_vehicle_btn') }}
                </button>
            </form>
        </div>
        </div>{{-- /p-4 --}}
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center fade-in fade-in-2 border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-truck class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_vehicles_yet') }}</p>
            <p class="text-sm mb-5" style="color:#64748b;">{{ __('app.add_first_vehicle') }}</p>
            <a href="{{ route('vehicles.create') }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                {{ __('app.add_vehicle') }}
            </a>
        </div>
    @endforelse

</div>
</x-app-layout>
<style>
.ring-wrap:hover .ring-tip { opacity: 1; }
.ring-tip { opacity: 0; transition: opacity 0.2s ease; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.health-ring').forEach(function (ring) {
        var target = parseInt(ring.dataset.health, 10);
        var color  = ring.dataset.color;
        var current = 0;
        var steps   = 48;
        var inc     = target / steps;
        var i = 0;
        var timer = setInterval(function () {
            i++;
            current = Math.min(i * inc, target);
            ring.style.background =
                'conic-gradient(' + color + ' 0% ' + current + '%, rgba(255,255,255,0.05) ' + current + '% 100%)';
            if (i >= steps) clearInterval(timer);
        }, 16);
    });
});
</script>
