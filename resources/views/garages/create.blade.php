<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// REGISTER GARAGE</p>
        <h1 class="heading text-3xl font-bold text-white">
            New <span class="text-cyan">Garage Profile</span>
        </h1>
    </div>

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('garages.store') }}">
            @csrf

            <div class="mb-4">
                <label class="section-label mb-2 block">// garage name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="AutoHub Lanka"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">// city</label>
                    <input type="text" name="city" value="{{ old('city') }}" required
                           placeholder="Colombo"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">// phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           placeholder="0112345678"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">// address</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       placeholder="123 Main Street, Colombo 05"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-4">
                <label class="section-label mb-2 block">// specialisation</label>
                <input type="text" name="specialisation" value="{{ old('specialisation') }}"
                       placeholder="Toyota, Honda, Engine Repairs..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <div class="mb-6">
                <label class="section-label mb-2 block">// description</label>
                <textarea name="description" rows="3"
                          placeholder="Brief description of your garage and services..."
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('description') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                REGISTER GARAGE →
            </button>

            <a href="{{ route('garages.index') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">Cancel</a>
        </form>
    </div>

</div>
</x-app-layout>