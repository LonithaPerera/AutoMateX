<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <a href="{{ route('parts.index') }}"
           class="inline-flex items-center gap-2 text-sm mb-3"
           style="color:#64748b;">
            {{ __('app.back_to_parts') }}
        </a>
        <p class="section-label mb-1">{{ __('app.parts_admin_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.create_part_title') }}
        </h1>
    </div>

    @if($errors->any())
    <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-1"
         style="background:rgba(248,113,113,0.06);border-color:rgba(248,113,113,0.2);">
        @foreach($errors->all() as $error)
            <p class="text-xs flex items-center gap-1 mb-1 last:mb-0" style="color:#f87171;">
                <x-heroicon-o-exclamation-triangle class="w-3 h-3 flex-shrink-0" /> {{ $error }}
            </p>
        @endforeach
    </div>
    @endif

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow"
         style="border-color:rgba(0,245,255,0.12);">
        <form method="POST" action="{{ route('parts.store') }}">
            @csrf

            {{-- Part Info section --}}
            <p class="section-label mb-3">// PART INFO</p>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_part_name') }}</label>
                <input type="text" name="part_name" value="{{ old('part_name') }}" required
                       placeholder="e.g. Oil Filter"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_part_category') }}</label>
                    <input type="text" name="part_category" value="{{ old('part_category') }}" required
                           placeholder="e.g. Filters"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_brand') }}</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           placeholder="e.g. Bosch"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_oem_number') }}</label>
                    <input type="text" name="oem_part_number" value="{{ old('oem_part_number') }}" required
                           placeholder="e.g. 90915-YZZD4"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_alt_number') }}</label>
                    <input type="text" name="alternative_part_number" value="{{ old('alternative_part_number') }}"
                           placeholder="e.g. 90915-YZZJ3"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                </div>
            </div>

            <div class="mb-5">
                <label class="section-label mb-2 block">{{ __('app.field_description_p') }}</label>
                <textarea name="description" rows="2"
                          placeholder="Brief notes about this part..."
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">{{ old('description') }}</textarea>
            </div>

            {{-- Vehicle Compatibility section --}}
            <p class="section-label mb-3">// VEHICLE COMPATIBILITY</p>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_make') }}</label>
                    <input type="text" name="vehicle_make" value="{{ old('vehicle_make') }}" required
                           placeholder="Toyota"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_model') }}</label>
                    <input type="text" name="vehicle_model" value="{{ old('vehicle_model') }}" required
                           placeholder="Premio"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_year_from') }}</label>
                    <input type="number" name="vehicle_year_from" value="{{ old('vehicle_year_from') }}" required
                           placeholder="2010" min="1900" max="2099"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_year_to') }}</label>
                    <input type="number" name="vehicle_year_to" value="{{ old('vehicle_year_to') }}" required
                           placeholder="2018" min="1900" max="2099"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                <x-heroicon-o-plus class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.save_part_btn') }}
            </button>

            <a href="{{ route('parts.index') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>
