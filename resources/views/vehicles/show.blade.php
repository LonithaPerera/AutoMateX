<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// VEHICLE DETAILS</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ $vehicle->make }} <span class="text-cyan">{{ $vehicle->model }}</span>
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->year }} · {{ number_format($vehicle->mileage) }} km
        </p>
    </div>

    {{-- Details card --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-3">// SPECIFICATIONS</p>

        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['Make', $vehicle->make],
                ['Model', $vehicle->model],
                ['Year', $vehicle->year],
                ['Mileage', number_format($vehicle->mileage).' km'],
                ['Fuel Type', ucfirst($vehicle->fuel_type ?? '—')],
                ['Color', $vehicle->color ?? 'Not specified'],
                ['License Plate', $vehicle->license_plate ?? 'Not specified'],
                ['VIN', $vehicle->vin ?? 'Not specified'],
            ] as [$label, $value])
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ $label }}</p>
                <p class="mono text-sm font-bold text-white">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Action buttons --}}
    <p class="section-label mb-3 fade-in fade-in-3">// QUICK ACTIONS</p>
    <div class="grid grid-cols-2 gap-3 mb-4 fade-in fade-in-3">

        <a href="{{ route('qrcode.show', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,245,255,0.12);">
            <span class="text-2xl">📱</span>
            <p class="heading text-xs font-bold text-white tracking-wider">QR CODE</p>
        </a>

        <a href="{{ route('suggestions.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(168,85,247,0.2);">
            <span class="text-2xl">🧠</span>
            <p class="heading text-xs font-bold tracking-wider" style="color:#a855f7;">SUGGESTIONS</p>
        </a>

        <a href="{{ route('service.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(74,222,128,0.2);">
            <span class="text-2xl">🔧</span>
            <p class="heading text-xs font-bold tracking-wider" style="color:#4ade80;">SERVICE</p>
        </a>

        <a href="{{ route('fuel.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,102,255,0.2);">
            <span class="text-2xl">⛽</span>
            <p class="heading text-xs font-bold tracking-wider" style="color:#6699ff;">FUEL LOGS</p>
        </a>

    </div>

    {{-- Back --}}
    <a href="{{ route('vehicles.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-4"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        ← Back to My Vehicles
    </a>

</div>
</x-app-layout>