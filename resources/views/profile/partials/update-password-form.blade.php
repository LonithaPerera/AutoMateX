<section>
    <p class="section-label mb-1">// SECURITY</p>
    <h2 class="heading text-lg font-bold text-white mb-1">Update Password</h2>
    <p class="text-xs mb-4" style="color:#64748b;">Use a strong password to keep your account secure</p>

    @if(session('status') === 'password-updated')
    <div class="rounded-xl p-3 mb-4" style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);">
        <p class="text-xs" style="color:rgba(0,245,255,0.8);">✓ Password updated successfully</p>
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="section-label mb-2 block">// current password</label>
            <input type="password" name="current_password" required
                   class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            @error('current_password','updatePassword')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="section-label mb-2 block">// new password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            @error('password','updatePassword')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="section-label mb-2 block">// confirm new password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
            UPDATE PASSWORD →
        </button>
    </form>
</section>