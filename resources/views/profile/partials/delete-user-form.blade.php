<section>
    <p class="section-label mb-1">{{ __('app.danger_zone_label') }}</p>
    <h2 class="heading text-lg font-bold mb-1" style="color:#f87171;">{{ __('app.delete_account_title') }}</h2>
    <p class="text-xs mb-4" style="color:#64748b;">
        {{ __('app.delete_account_desc') }}
    </p>

    <button onclick="document.getElementById('confirm-delete').classList.remove('hidden')"
            class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
            style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
        <x-heroicon-o-trash class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.delete_my_account_btn') }}
    </button>

    {{-- Confirm modal --}}
    <div id="confirm-delete" class="hidden mt-4">
        <div class="rounded-2xl p-4" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);">
            <p class="text-sm font-semibold mb-3" style="color:#f87171;">
                <x-heroicon-o-exclamation-triangle class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.delete_confirm_warning') }}
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')

                {{-- Step 1: type DELETE --}}
                <div class="mb-3">
                    <label class="section-label mb-2 block">TYPE <span style="color:#f87171;letter-spacing:0.15em;">DELETE</span> TO CONFIRM</label>
                    <input id="delete_confirm_text"
                           type="text"
                           autocomplete="off"
                           placeholder="DELETE"
                           oninput="onDeleteTextInput()"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.3);letter-spacing:0.1em;">
                </div>

                {{-- Step 2: password (locked until DELETE typed) --}}
                <div class="mb-3" id="delete-pw-section" style="opacity:0.35;pointer-events:none;transition:opacity 0.2s;">
                    <label class="section-label mb-2 block">{{ __('app.field_confirm_password') }}</label>
                    <div class="relative">
                        <input id="delete_password" type="password" name="password" required
                               placeholder="Enter password to confirm"
                               class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.3);padding-right:44px;">
                        <button type="button" onclick="toggleDeletePw(this)" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1"
                                style="background:none;border:none;cursor:pointer;color:rgba(248,113,113,0.4);">
                            <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('password','userDeletion')
                        <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            onclick="document.getElementById('confirm-delete').classList.add('hidden');document.getElementById('delete_confirm_text').value='';onDeleteTextInput();"
                            class="py-2.5 rounded-xl text-sm font-semibold heading tracking-wider"
                            style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
                        {{ __('app.delete_cancel_btn') }}
                    </button>
                    <button type="submit"
                            id="delete-submit-btn"
                            disabled
                            class="py-2.5 rounded-xl text-sm font-semibold heading tracking-wider"
                            style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.15);color:rgba(248,113,113,0.35);cursor:not-allowed;transition:all 0.2s;">
                        {{ __('app.confirm_delete_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDeletePw(btn) {
            const inp = document.getElementById('delete_password');
            const show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            btn.querySelector('.icon-eye').style.display = show ? 'none' : '';
            btn.querySelector('.icon-eye-off').style.display = show ? '' : 'none';
        }

        function onDeleteTextInput() {
            const val     = document.getElementById('delete_confirm_text').value;
            const matched = val === 'DELETE';
            const pwSect  = document.getElementById('delete-pw-section');
            const submitBtn = document.getElementById('delete-submit-btn');

            // Unlock password section
            pwSect.style.opacity = matched ? '1' : '0.35';
            pwSect.style.pointerEvents = matched ? 'auto' : 'none';

            // Enable/disable submit button
            submitBtn.disabled = !matched;
            if (matched) {
                submitBtn.style.background   = 'rgba(248,113,113,0.15)';
                submitBtn.style.borderColor  = 'rgba(248,113,113,0.3)';
                submitBtn.style.color        = '#f87171';
                submitBtn.style.cursor       = 'pointer';
            } else {
                submitBtn.style.background   = 'rgba(248,113,113,0.06)';
                submitBtn.style.borderColor  = 'rgba(248,113,113,0.15)';
                submitBtn.style.color        = 'rgba(248,113,113,0.35)';
                submitBtn.style.cursor       = 'not-allowed';
            }
        }
    </script>
</section>