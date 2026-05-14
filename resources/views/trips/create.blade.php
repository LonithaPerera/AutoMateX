<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <nav class="flex items-center gap-1.5 text-xs mb-3" style="color:#64748b;">
            <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
            <span>›</span>
            <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
            <span>›</span>
            <a href="{{ route('trips.index', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.trip_log_label') }}</a>
            <span>›</span>
            <span style="color:#94a3b8;">{{ __('app.new_trip_title') }}</span>
        </nav>
        <p class="section-label mb-1">{{ __('app.trip_log_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">{{ __('app.new_trip_title') }}</h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}</p>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('trips.store', $vehicle) }}">
            @csrf

            {{-- Date --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_date') }}</label>
                <input type="date" name="trip_date"
                       value="{{ old('trip_date', date('Y-m-d')) }}" required
                       class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('trip_date') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};color-scheme:dark;">
                @error('trip_date')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            {{-- Start & End km --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_start_km') }}</label>
                    <input type="number" name="start_km"
                           value="{{ old('start_km', $vehicle->mileage) }}"
                           required min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('start_km') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('start_km')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_end_km') }}</label>
                    <input type="number" name="end_km"
                           value="{{ old('end_km') }}"
                           required min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('end_km') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('end_km')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Purpose --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_purpose') }}</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['personal','business','other'] as $p)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="purpose" value="{{ $p }}"
                               {{ old('purpose', 'personal') === $p ? 'checked' : '' }}
                               class="peer absolute opacity-0 w-0 h-0">
                        <div class="py-2.5 rounded-xl text-center text-xs font-semibold heading tracking-wider transition-all border peer-checked:border-cyan-400"
                             style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.08);">
                            {{ ucfirst($p) }}
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('purpose')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_notes') }}</label>
                <textarea name="notes" rows="2"
                          placeholder="{{ __('app.trip_notes_ph') }}"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.save_trip_btn') }}
            </button>

            <a href="{{ route('trips.index', $vehicle) }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">
                {{ __('app.cancel') }}
            </a>
        </form>
    </div>

</div>
</x-app-layout>
