<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <a href="{{ route('fuel.index', $vehicle) }}"
           class="inline-flex items-center gap-2 text-sm mb-3"
           style="color:#64748b;">
            ← Back to Fuel Logs
        </a>
        <p class="section-label mb-1">// LOG FUEL</p>
        <h1 class="heading text-3xl font-bold text-white">
            New <span class="text-cyan">Fill-Up</span>
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}
        </p>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('fuel.store', $vehicle) }}">
            @csrf

            {{-- Date & Odometer --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">// date</label>
                    <input type="date" name="date"
                           value="{{ old('date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-2 block">// odometer (km)</label>
                    <input type="number" name="km_reading"
                           value="{{ old('km_reading', $vehicle->mileage) }}"
                           required placeholder="{{ $vehicle->mileage }}" min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Liters & Cost --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">// liters filled</label>
                    <input type="number" name="liters"
                           value="{{ old('liters') }}"
                           required placeholder="35.5" min="0" step="0.01"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">// total cost (LKR)</label>
                    <input type="number" name="cost"
                           value="{{ old('cost') }}"
                           required placeholder="8500" min="0" step="0.01"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Fuel Station --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">// fuel station (optional)</label>
                <input type="text" name="fuel_station"
                       value="{{ old('fuel_station') }}"
                       placeholder="e.g. Ceylon Petroleum, Nugegoda"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">// notes (optional)</label>
                <textarea name="notes" rows="2" placeholder="Any notes..."
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes') }}</textarea>
            </div>

            {{-- Auto calc notice --}}
            <div class="rounded-xl p-3 mb-4 flex items-center gap-2"
                 style="background:rgba(0,245,255,0.05);border:1px solid rgba(0,245,255,0.12);">
                <span style="color:var(--cyan);">⚡</span>
                <p class="text-xs" style="color:rgba(0,245,255,0.7);">
                    Fuel efficiency (km/L) will be calculated automatically
                </p>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                SAVE FILL-UP →
            </button>

            <a href="{{ route('fuel.index', $vehicle) }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">
                Cancel
            </a>
        </form>
    </div>

</div>
</x-app-layout>