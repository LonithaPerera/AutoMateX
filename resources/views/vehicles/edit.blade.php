<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
            <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
            <span>›</span>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
            <span>›</span>
            <span style="color:#94a3b8;">{{ __('app.edit_vehicle_title') }}</span>
        </nav>
        <p class="section-label mb-1">{{ __('app.edit_vehicle_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">{{ __('app.edit_vehicle_title') }}</h1>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
            @csrf
            @method('PUT')

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl" style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);">
                    @foreach($errors->all() as $error)
                        <p class="text-xs flex items-center gap-1" style="color:#f87171;"><x-heroicon-o-exclamation-triangle class="w-3 h-3 flex-shrink-0" /> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Make & Model --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_make') }}</label>
                    <input type="text" name="make" value="{{ old('make', $vehicle->make) }}" required placeholder="Toyota"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none transition-all"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_model') }}</label>
                    <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required placeholder="Premio"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none transition-all"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Year & Mileage --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_year') }}</label>
                    <input type="number" name="year" value="{{ old('year', $vehicle->year) }}" required placeholder="2018" min="1990" max="2026"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_mileage_km') }}</label>
                    <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" required placeholder="44100" min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- License Plate & Color --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_license') }}</label>
                    <input type="text" name="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" placeholder="CAY-8485"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_colour') }}</label>
                    <input type="text" name="color" value="{{ old('color', $vehicle->color) }}" placeholder="Silver"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- VIN --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_vin') }}</label>
                <input type="text" name="vin" value="{{ old('vin', $vehicle->vin) }}" placeholder="JT2BF22K1W0123456"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_vehicle_notes') }}</label>
                <textarea name="notes" rows="2" placeholder="{{ __('app.vehicle_notes_ph') }}"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes', $vehicle->notes) }}</textarea>
            </div>

            {{-- Fuel Type --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_fuel_type') }}</label>
                <div class="grid grid-cols-2 gap-2" id="fuel-group">
                    @foreach(['petrol','diesel','hybrid','electric'] as $fuel)
                    <label class="relative cursor-pointer" onclick="selectFuel('{{ $fuel }}')">
                        <input type="radio" name="fuel_type" value="{{ $fuel }}"
                               {{ old('fuel_type', $vehicle->fuel_type) === $fuel ? 'checked' : '' }}
                               class="peer absolute opacity-0 w-0 h-0">
                        <div id="fuel-{{ $fuel }}"
                             class="py-2.5 rounded-xl text-center text-xs font-semibold heading tracking-wider transition-all border"
                             style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.08);color:#64748b;">
                            {{ __('app.fuel_' . $fuel) }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.save_changes_btn') }}
            </button>

            <a href="{{ route('vehicles.show', $vehicle) }}"
               class="block text-center mt-3 text-sm py-2"
               style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>

<script>
function selectFuel(fuel) {
    ['petrol','diesel','hybrid','electric'].forEach(f => {
        const el = document.getElementById('fuel-' + f);
        if (f === fuel) {
            el.style.borderColor = 'rgba(0,245,255,0.5)';
            el.style.color = '#00f5ff';
            el.style.background = 'rgba(0,245,255,0.1)';
        } else {
            el.style.borderColor = 'rgba(255,255,255,0.08)';
            el.style.color = '#64748b';
            el.style.background = 'rgba(255,255,255,0.03)';
        }
    });
}
selectFuel('{{ old('fuel_type', $vehicle->fuel_type) }}');
</script>

</x-app-layout>
