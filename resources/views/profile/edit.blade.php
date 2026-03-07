<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// ACCOUNT</p>
        <h1 class="heading text-3xl font-bold text-white">
            My <span class="text-cyan">Profile</span>
        </h1>
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
            ⏻ LOGOUT
        </button>
    </form>

    {{-- Delete Account --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-5"
         style="border-color:rgba(248,113,113,0.15);">
        @include('profile.partials.delete-user-form')
    </div>

</div>
</x-app-layout>