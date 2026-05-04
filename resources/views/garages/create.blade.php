<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <a href="{{ route('garages.index') }}"
           class="inline-flex items-center gap-2 text-sm mb-3"
           style="color:#64748b;">
            {{ __('app.back_to_garages') }}
        </a>
        <p class="section-label mb-1">{{ __('app.register_garage_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.new_garage_title') }}
        </h1>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('garages.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_garage_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="AutoHub Lanka"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_city') }}</label>
                    <input type="text" name="city" value="{{ old('city') }}" required
                           placeholder="Colombo"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="0112345678"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_address') }}</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       placeholder="123 Main Street, Colombo 05"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_specialisation') }}</label>
                <input type="text" name="specialization" value="{{ old('specialization') }}"
                       placeholder="Toyota, Honda, Engine Repairs..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_description') }}</label>
                <textarea name="description" rows="3"
                          placeholder="Brief description of your garage and services..."
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('description') }}</textarea>
            </div>

            {{-- Garage Photo --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.garage_photo_label') }}</label>
                <label class="flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-all"
                       style="background:rgba(255,255,255,0.03);border:1px dashed rgba(0,245,255,0.2);">
                    <x-heroicon-o-camera class="w-5 h-5 flex-shrink-0" style="color:#00f5ff;" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white" id="garage-photo-name">{{ __('app.garage_photo_hint') }}</p>
                        <p class="text-xs mt-0.5" style="color:#475569;">JPG, PNG or WebP · max 2 MB</p>
                    </div>
                    <input type="file" name="photo" accept="image/*" class="hidden"
                           onchange="document.getElementById('garage-photo-name').textContent = this.files[0]?.name ?? '{{ __('app.garage_photo_hint') }}'">
                </label>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.register_garage_btn') }}
            </button>

            <a href="{{ route('garages.index') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>