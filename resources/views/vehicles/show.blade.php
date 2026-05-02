<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ $vehicle->make }} {{ $vehicle->model }}</span>
    </nav>

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

    {{-- Next Service Reminder --}}
    @if(isset($nextService) && $nextService)
    @php
        $nsColor  = $nextService['status'] === 'overdue' ? '#f87171' : ($nextService['status'] === 'due_soon' ? '#ff6b00' : '#00f5ff');
        $nsBg     = $nextService['status'] === 'overdue' ? 'rgba(248,113,113,0.08)' : ($nextService['status'] === 'due_soon' ? 'rgba(255,107,0,0.08)' : 'rgba(0,245,255,0.06)');
        $nsBorder = $nextService['status'] === 'overdue' ? 'rgba(248,113,113,0.3)' : ($nextService['status'] === 'due_soon' ? 'rgba(255,107,0,0.3)' : 'rgba(0,245,255,0.2)');
        $nsIcon   = $nextService['status'] === 'overdue' ? 'exclamation-triangle' : ($nextService['status'] === 'due_soon' ? 'exclamation-circle' : 'clock');
    @endphp
    <div class="rounded-2xl p-4 mb-4 border fade-in fade-in-2 flex items-center gap-3"
         style="background:{{ $nsBg }};border-color:{{ $nsBorder }};">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:{{ $nsBg }};border:1px solid {{ $nsBorder }};">
            @if($nextService['status'] === 'overdue')
                <x-heroicon-o-exclamation-triangle class="w-4 h-4" style="color:{{ $nsColor }};" />
            @elseif($nextService['status'] === 'due_soon')
                <x-heroicon-o-exclamation-circle class="w-4 h-4" style="color:{{ $nsColor }};" />
            @else
                <x-heroicon-o-clock class="w-4 h-4" style="color:{{ $nsColor }};" />
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="section-label mb-0.5">{{ __('app.next_service_due_label') }}</p>
            <p class="heading font-bold text-white text-sm leading-tight">{{ $nextService['name'] }}</p>
            <p class="text-xs mt-0.5" style="color:{{ $nsColor }};">
                @if($nextService['status'] === 'overdue')
                    {{ number_format(abs($nextService['km_left'])) }} {{ __('app.km_overdue') }}
                @else
                    {{ number_format($nextService['km_left']) }} {{ __('app.km_away') }}
                @endif
            </p>
        </div>
        <a href="{{ route('suggestions.index', $vehicle) }}"
           class="text-xs py-1.5 px-3 rounded-lg heading font-semibold tracking-wide flex-shrink-0"
           style="background:{{ $nsBg }};border:1px solid {{ $nsBorder }};color:{{ $nsColor }};">
            {{ __('app.view_maintenance') }}
        </a>
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

    {{-- Book a Service --}}
    <a href="{{ route('garages.index') }}"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl font-semibold heading tracking-widest text-sm transition-all active:scale-95 mb-4 fade-in fade-in-4"
       style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
        <x-heroicon-o-calendar-days class="w-5 h-5" />
        {{ __('app.book_service_btn') }}
    </a>

    {{-- Back --}}
    <a href="{{ route('vehicles.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-4"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        {{ __('app.back_to_my_vehicles') }}
    </a>
</div>
</x-app-layout>
