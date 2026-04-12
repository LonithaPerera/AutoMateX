<section>
    <p class="section-label mb-1">{{ __('app.security_label') }}</p>
    <h2 class="heading text-lg font-bold text-white mb-1">{{ __('app.update_password_title') }}</h2>
    <p class="text-xs mb-4" style="color:#64748b;">{{ __('app.update_password_desc') }}</p>

    @if(session('status') === 'password-updated')
    <div class="rounded-xl p-3 mb-4" style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);">
        <p class="text-xs flex items-center gap-1" style="color:rgba(0,245,255,0.8);"><x-heroicon-o-check class="w-3 h-3 flex-shrink-0" /> {{ __('app.password_updated') }}</p>
    </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="section-label mb-2 block">{{ __('app.field_current_password') }}</label>
            <div class="relative">
                <input id="current_password" type="password" name="current_password" required
                       class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);padding-right:44px;">
                <button type="button" onclick="togglePw('current_password',this)" tabindex="-1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 transition-colors"
                        style="background:none;border:none;cursor:pointer;color:rgba(0,245,255,0.35);">
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                </button>
            </div>
            @error('current_password','updatePassword')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="section-label mb-2 block">{{ __('app.field_new_password') }}</label>
            <div class="relative">
                <input id="pw_new" type="password" name="password" required
                       class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);padding-right:44px;">
                <button type="button" onclick="togglePw('pw_new',this)" tabindex="-1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 transition-colors"
                        style="background:none;border:none;cursor:pointer;color:rgba(0,245,255,0.35);">
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                </button>
            </div>
            @error('password','updatePassword')
                <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="section-label mb-2 block">{{ __('app.field_confirm_new_pass') }}</label>
            <div class="relative">
                <input id="pw_confirm" type="password" name="password_confirmation" required
                       class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);padding-right:44px;">
                <button type="button" onclick="togglePw('pw_confirm',this)" tabindex="-1"
                        class="absolute right-3 top-1/2 -translate-y-1/2 p-1 transition-colors"
                        style="background:none;border:none;cursor:pointer;color:rgba(0,245,255,0.35);">
                    <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                </button>
            </div>
        </div>

        <button type="submit"
                class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
            {{ __('app.update_password_btn') }}
        </button>
    </form>

    <script>
        function togglePw(id, btn) {
            const inp = document.getElementById(id);
            const show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            btn.querySelector('.icon-eye').style.display = show ? 'none' : '';
            btn.querySelector('.icon-eye-off').style.display = show ? '' : 'none';
            btn.style.color = show ? 'rgba(0,245,255,0.8)' : 'rgba(0,245,255,0.35)';
        }
    </script>
</section>