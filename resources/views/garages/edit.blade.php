<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <a href="{{ route('garage.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm mb-3"
           style="color:#64748b;">
            ← {{ __('app.back_to_dashboard') }}
        </a>
        <p class="section-label mb-1">{{ __('app.edit_garage_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.edit_garage_title') }}
        </h1>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('garages.update') }}">
            @csrf @method('PATCH')

            @if($errors->any())
                <div class="rounded-xl p-3 mb-4 border" style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);">
                    @foreach($errors->all() as $error)
                        <p class="text-xs" style="color:#f87171;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_garage_name') }}</label>
                <input type="text" name="name" value="{{ old('name', $garage->name) }}" required
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_city') }}</label>
                    <input type="text" name="city" value="{{ old('city', $garage->city) }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $garage->phone) }}"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_address') }}</label>
                <input type="text" name="address" value="{{ old('address', $garage->address) }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_specialisation') }}</label>
                <input type="text" name="specialization" value="{{ old('specialization', $garage->specialization) }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_description') }}</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('description', $garage->description) }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.save_changes_btn') }}
            </button>

            <a href="{{ route('garage.dashboard') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>
