<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.account_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.my_profile_title') }}
        </h1>
    </div>

    {{-- Flash messages --}}
    @if(session('status') === 'avatar-updated')
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(74,222,128,0.06);border-color:rgba(74,222,128,0.2);">
        <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:#4ade80;" />
        <span class="text-sm" style="color:#4ade80;">{{ __('app.avatar_updated') }}</span>
    </div>
    @elseif(session('status') === 'avatar-removed')
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
        <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
        <span class="text-sm" style="color:var(--cyan);">{{ __('app.avatar_removed') }}</span>
    </div>
    @endif

    {{-- #9 Profile Avatar --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-1" style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-4">{{ __('app.profile_photo_label') }}</p>
        <div class="flex items-center gap-4 mb-4">
            {{-- Avatar preview --}}
            <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 flex items-center justify-center"
                 style="background:rgba(0,245,255,0.06);border:2px solid rgba(0,245,255,0.15);">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         class="w-full h-full object-cover" id="avatar-preview">
                @else
                    <div id="avatar-preview-placeholder" class="w-full h-full flex items-center justify-center">
                        <x-heroicon-o-user-circle class="w-12 h-12" style="color:#1e293b;" />
                    </div>
                @endif
            </div>
            <div>
                <p class="heading font-bold text-white text-base">{{ $user->name }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ $user->email }}</p>
                <span class="tag mt-1 inline-block" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.25);">
                    {{ strtoupper($user->role ?? 'USER') }}
                </span>
            </div>
        </div>

        {{-- Upload form --}}
        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
            @csrf
            <label class="flex items-center gap-2 px-3 py-2.5 rounded-xl cursor-pointer transition-all mb-2"
                   style="background:rgba(255,255,255,0.03);border:1px dashed rgba(0,245,255,0.2);">
                <x-heroicon-o-camera class="w-4 h-4 flex-shrink-0" style="color:#475569;" />
                <span class="text-xs" style="color:#64748b;" id="avatar-filename">{{ $user->avatar ? __('app.change_photo_btn') : __('app.upload_photo_btn') }}</span>
                <input type="file" name="avatar" accept="image/*" class="sr-only"
                       onchange="document.getElementById('avatar-filename').textContent = this.files[0]?.name ?? ''; document.getElementById('avatar-submit').classList.remove('hidden');">
            </label>
            @error('avatar') <p class="text-xs mb-2" style="color:#f87171;">{{ $message }}</p> @enderror
            <button type="submit" id="avatar-submit"
                    class="hidden w-full py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.3);color:var(--cyan);">
                {{ __('app.update_btn') }}
            </button>
        </form>

        {{-- Remove avatar (only when set) --}}
        @if($user->avatar)
        <form method="POST" action="{{ route('profile.avatar.remove') }}" class="mt-2"
              onsubmit="return confirm('{{ __('app.remove_photo_confirm') }}')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-xl transition-all active:scale-95"
                    style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                <x-heroicon-o-trash class="w-3 h-3" />
                {{ __('app.remove_photo_btn') }}
            </button>
        </form>
        @endif
    </div>

    {{-- Update Profile Info --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Update Password --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(0,245,255,0.12);">
        @include('profile.partials.update-password-form')
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" class="mb-4 fade-in fade-in-4">
        @csrf
        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm"
                style="background:rgba(255,107,0,0.1);border:1px solid rgba(255,107,0,0.3);color:#ff6b00;">
            <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.logout_btn') }}
        </button>
    </form>

    {{-- Delete Account --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-5"
         style="border-color:rgba(248,113,113,0.15);">
        @include('profile.partials.delete-user-form')
    </div>

    {{-- Back --}}
    <div class="mt-2 mb-6 fade-in fade-in-5">
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            {{ __('app.back_to_dashboard') }}
        </a>
    </div>

</div>
</x-app-layout>