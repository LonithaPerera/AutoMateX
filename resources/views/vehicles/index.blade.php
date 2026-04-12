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
        <a href="{{ route('vehicles.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            {{ __('app.add_btn') }}
        </a>
    </div>

    @if(session('success'))
        <div class="fade-in fade-in-1 rounded-2xl p-3 mb-4 flex items-center gap-3 border"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    @forelse($vehicles as $index => $vehicle)
    <div class="glass-bright rounded-2xl p-4 mb-4 vehicle-card fade-in fade-in-{{ $index + 2 }} animate-glow border">

        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="tag" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.25);">{{ __('app.active_tag') }}</span>
                    <span class="tag" style="background:rgba(255,255,255,0.05);color:#64748b;">{{ strtoupper($vehicle->fuel_type) }}</span>
                </div>
                <h3 class="heading text-xl font-bold text-white">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                <p class="text-xs mono mt-0.5" style="color:#64748b;">
                    {{ $vehicle->year }}
                    @if($vehicle->license_plate) · {{ $vehicle->license_plate }} @endif
                </p>
            </div>
            {{-- Ring gauge --}}
            @php $health = max(10, min(99, 100 - round(($vehicle->mileage / 100000) * 100))); @endphp
            <div style="
                width:56px;height:56px;border-radius:50%;
                background:conic-gradient(var(--cyan) 0% {{ $health }}%, rgba(255,255,255,0.05) {{ $health }}% 100%);
                display:flex;align-items:center;justify-content:center;
                box-shadow:0 0 15px rgba(0,245,255,0.3);">
                <div style="width:44px;height:44px;border-radius:50%;background:var(--card);display:flex;align-items:center;justify-content:center;">
                    <span class="mono text-xs font-bold text-cyan">{{ $health }}%</span>
                </div>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="mono text-sm font-bold text-white">{{ number_format($vehicle->mileage) }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.km_odo') }}</p>
            </div>
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                @php $avg = \App\Models\FuelLog::where('vehicle_id',$vehicle->id)->whereNotNull('km_per_liter')->avg('km_per_liter'); @endphp
                <p class="mono text-sm font-bold" style="color:#4ade80;">{{ $avg ? number_format($avg,1) : '—' }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">km/L</p>
            </div>
            <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                @php $svc = \App\Models\ServiceLog::where('vehicle_id',$vehicle->id)->count(); @endphp
                <p class="mono text-sm font-bold text-white">{{ $svc }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.km_services') }}</p>
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
        <div class="grid grid-cols-3 gap-2">
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
            <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}"
                  onsubmit="return confirm('{{ __('app.remove_confirm', ['name' => $vehicle->make . ' ' . $vehicle->model]) }}')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,60,60,0.08);border:1px solid rgba(255,60,60,0.2);color:#f87171;">
                    <x-heroicon-o-trash class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.remove_btn') }}
                </button>
            </form>
        </div>
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
