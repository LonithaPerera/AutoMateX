<section>
    <p class="section-label mb-1">{{ __('app.profile_info_label') }}</p>
    <h2 class="heading text-lg font-bold text-white mb-1">{{ __('app.update_info_title') }}</h2>
    <p class="text-xs mb-4" style="color:#64748b;">{{ __('app.update_info_desc') }}</p>

    @if(session('status') === 'profile-updated')
    <div class="rounded-xl p-3 mb-4" style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);">
        <p class="text-xs flex items-center gap-1" style="color:rgba(0,245,255,0.8);"><x-heroicon-o-check class="w-3 h-3 flex-shrink-0" /> {{ __('app.profile_updated') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PATCH')

        <div class="mb-4">
            <label class="section-label mb-2 block">{{ __('app.field_full_name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            @error('name','profile')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="section-label mb-2 block">{{ __('app.field_email') }}</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            @error('email','profile')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.25);">
            {{ __('app.save_changes_btn') }}
        </button>
    </form>
</section>