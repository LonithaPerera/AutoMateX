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
            {{ __('app.edit_part_title') }}
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">{{ $part->part_name }}</p>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow"
         style="border-color:rgba(0,245,255,0.12);">
        <form method="POST" action="{{ route('parts.update', $part) }}">
            @csrf @method('PATCH')

            {{-- Part Info section --}}
            <p class="section-label mb-3">{{ __('app.part_info_section') }}</p>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_part_name') }}</label>
                <input type="text" name="part_name" value="{{ old('part_name', $part->part_name) }}" required
                       placeholder="{{ __('app.ph_part_name') }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('part_name') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                @error('part_name')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_part_category') }}</label>
                    <input type="text" name="part_category" value="{{ old('part_category', $part->part_category) }}" required
                           placeholder="{{ __('app.ph_part_category') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('part_category') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('part_category')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_brand') }}</label>
                    <input type="text" name="brand" value="{{ old('brand', $part->brand) }}"
                           placeholder="{{ __('app.ph_brand_eg') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_oem_number') }}</label>
                    <input type="text" name="oem_part_number" value="{{ old('oem_part_number', $part->oem_part_number) }}" required
                           placeholder="{{ __('app.ph_oem_number_eg') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('oem_part_number') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('oem_part_number')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_alt_number') }}</label>
                    <input type="text" name="alternative_part_number" value="{{ old('alternative_part_number', $part->alternative_part_number) }}"
                           placeholder="{{ __('app.ph_alt_number_eg') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">
                </div>
            </div>

            <div class="mb-5">
                <label class="section-label mb-2 block">{{ __('app.field_description_p') }}</label>
                <textarea name="description" rows="2"
                          placeholder="{{ __('app.ph_part_notes') }}"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);">{{ old('description', $part->description) }}</textarea>
            </div>

            {{-- Vehicle Compatibility section --}}
            <p class="section-label mb-3">{{ __('app.vehicle_compat_section') }}</p>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_make') }}</label>
                    <input type="text" name="vehicle_make" value="{{ old('vehicle_make', $part->vehicle_make) }}" required
                           placeholder="{{ __('app.ph_make') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('vehicle_make') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('vehicle_make')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_model') }}</label>
                    <input type="text" name="vehicle_model" value="{{ old('vehicle_model', $part->vehicle_model) }}" required
                           placeholder="{{ __('app.ph_model') }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('vehicle_model') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('vehicle_model')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_year_from') }}</label>
                    <input type="number" name="vehicle_year_from" value="{{ old('vehicle_year_from', $part->vehicle_year_from) }}" required
                           placeholder="2010" min="1900" max="2099"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('vehicle_year_from') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('vehicle_year_from')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_year_to') }}</label>
                    <input type="number" name="vehicle_year_to" value="{{ old('vehicle_year_to', $part->vehicle_year_to) }}" required
                           placeholder="2018" min="1900" max="2099"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('vehicle_year_to') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    @error('vehicle_year_to')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);box-shadow:0 0 20px rgba(0,245,255,0.1);">
                <x-heroicon-o-check class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.update_part_btn') }}
            </button>

            <a href="{{ route('parts.index') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>
