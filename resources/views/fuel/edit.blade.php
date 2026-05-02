<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
            <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
            <span>›</span>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
            <span>›</span>
            <a href="{{ route('fuel.index', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.fuel_tracker_label') }}</a>
            <span>›</span>
            <span style="color:#94a3b8;">{{ __('app.edit_fuel_title') }}</span>
        </nav>
        <p class="section-label mb-1">{{ __('app.log_fuel_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">{{ __('app.edit_fuel_title') }}</h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}
        </p>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('fuel.update', [$vehicle, $fuelLog]) }}">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_date') }}</label>
                    <input type="date" name="date"
                           value="{{ old('date', $fuelLog->date->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_odometer') }}</label>
                    <input type="number" name="km_reading"
                           value="{{ old('km_reading', $fuelLog->km_reading) }}"
                           required min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_liters') }}</label>
                    <input type="number" name="liters"
                           value="{{ old('liters', $fuelLog->liters) }}"
                           required min="0" step="0.01"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_cost_lkr') }}</label>
                    <input type="number" name="cost"
                           value="{{ old('cost', $fuelLog->cost) }}"
                           required min="0" step="0.01"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_fuel_station') }}</label>
                <input type="text" name="fuel_station"
                       value="{{ old('fuel_station', $fuelLog->fuel_station) }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_notes') }}</label>
                <textarea name="notes" rows="2"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes', $fuelLog->notes) }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.update_fillup_btn') }}
            </button>

            <a href="{{ route('fuel.index', $vehicle) }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">
                {{ __('app.cancel') }}
            </a>
        </form>
    </div>

</div>
</x-app-layout>
