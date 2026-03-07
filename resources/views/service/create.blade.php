<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// LOG SERVICE</p>
        <h1 class="heading text-3xl font-bold text-white">
            New <span class="text-cyan">Service Record</span>
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}
        </p>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('service.store', $vehicle) }}">
            @csrf

            {{-- Service Type --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">// service type</label>
                <input type="text" name="service_type" value="{{ old('service_type') }}"
                       required placeholder="e.g. Engine Oil Change"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none transition-all"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">// category</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['maintenance','repair','inspection'] as $cat)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="{{ $cat }}"
                               {{ old('type','maintenance') === $cat ? 'checked' : '' }}
                               class="peer absolute opacity-0 w-0 h-0">
                        <div class="py-2.5 rounded-xl text-center text-xs font-semibold heading tracking-wider transition-all border peer-checked:border-cyan-400"
                             style="background:rgba(255,255,255,0.03);border-color:rgba(255,255,255,0.08);">
                            {{ strtoupper($cat) }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Date & Mileage --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">// date</label>
                    <input type="date" name="service_date" value="{{ old('service_date', date('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-2 block">// mileage (km)</label>
                    <input type="number" name="mileage_at_service"
                           value="{{ old('mileage_at_service', $vehicle->mileage) }}"
                           required placeholder="{{ $vehicle->mileage }}" min="0"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Cost & Garage --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">// cost (LKR)</label>
                    <input type="number" name="cost" value="{{ old('cost') }}"
                           required placeholder="12000" min="0" step="0.01"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">// garage name</label>
                    <input type="text" name="garage_name" value="{{ old('garage_name') }}"
                           placeholder="AutoHub Lanka"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">// notes (optional)</label>
                <textarea name="notes" rows="3" placeholder="Any additional notes..."
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes') }}</textarea>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                SAVE SERVICE RECORD →
            </button>

            <a href="{{ route('service.index', $vehicle) }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">
                Cancel
            </a>
        </form>
    </div>

</div>
</x-app-layout>