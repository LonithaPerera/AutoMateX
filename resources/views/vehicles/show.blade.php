<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">
    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.vehicle_details_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ $vehicle->make }} <span class="text-cyan">{{ $vehicle->model }}</span>
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->year }} · {{ number_format($vehicle->mileage) }} km
        </p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl" style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);">
            <p class="text-xs flex items-center gap-1" style="color:#4ade80;"><x-heroicon-o-check class="w-3 h-3 flex-shrink-0" /> {{ session('success') }}</p>
        </div>
    @endif

    {{-- Details card --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-3">{{ __('app.specifications_label') }}</p>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                [__('app.spec_make'), $vehicle->make],
                [__('app.spec_model'), $vehicle->model],
                [__('app.spec_year'), $vehicle->year],
                [__('app.spec_mileage'), number_format($vehicle->mileage).' km'],
                [__('app.spec_fuel_type'), ucfirst($vehicle->fuel_type ?? '—')],
                [__('app.spec_color'), $vehicle->color ?? __('app.not_specified')],
                [__('app.spec_license'), $vehicle->license_plate ?? __('app.not_specified')],
                [__('app.spec_vin'), $vehicle->vin ?? __('app.not_specified')],
            ] as [$label, $value])
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ $label }}</p>
                <p class="mono text-sm font-bold text-white">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Update Mileage --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(255,107,0,0.2);">
        <p class="section-label mb-2">{{ __('app.update_mileage_label') }}</p>
        <form method="POST" action="{{ route('vehicles.updateMileage', $vehicle) }}">
            @csrf @method('PATCH')
            @if($errors->has('mileage'))
                <p class="text-xs mb-2 flex items-center gap-1" style="color:#f87171;"><x-heroicon-o-exclamation-triangle class="w-3 h-3 flex-shrink-0" /> {{ $errors->first('mileage') }}</p>
            @endif
            <div class="flex gap-2">
                <input type="number" name="mileage"
                       placeholder="{{ __('app.mileage_placeholder', ['min' => number_format($vehicle->mileage)]) }}"
                       min="{{ $vehicle->mileage }}"
                       class="flex-1 px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,107,0,0.2);">
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,107,0,0.15);border:1px solid rgba(255,107,0,0.3);color:#ff6b00;">
                    {{ __('app.update_btn') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Action buttons --}}
    <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.quick_actions_label') }}</p>
    <div class="grid grid-cols-2 gap-3 mb-4 fade-in fade-in-3">
        <a href="{{ route('qrcode.show', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,245,255,0.12);">
            <x-heroicon-o-qr-code class="w-6 h-6" style="color:var(--cyan);" />
            <p class="heading text-xs font-bold text-white tracking-wider">{{ __('app.action_qr_code') }}</p>
        </a>
        <a href="{{ route('suggestions.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(168,85,247,0.2);">
            <x-heroicon-o-light-bulb class="w-6 h-6" style="color:#a855f7;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#a855f7;">{{ __('app.action_suggestions') }}</p>
        </a>
        <a href="{{ route('service.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(74,222,128,0.2);">
            <x-heroicon-o-wrench-screwdriver class="w-6 h-6" style="color:#4ade80;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#4ade80;">{{ __('app.action_service') }}</p>
        </a>
        <a href="{{ route('fuel.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,102,255,0.2);">
            <x-heroicon-o-beaker class="w-6 h-6" style="color:#6699ff;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#6699ff;">{{ __('app.action_fuel_logs') }}</p>
        </a>
    </div>

    {{-- Back --}}
    <a href="{{ route('vehicles.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-4"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        {{ __('app.back_to_my_vehicles') }}
    </a>
</div>
</x-app-layout>
