<section>
    <p class="section-label mb-1">// DANGER ZONE</p>
    <h2 class="heading text-lg font-bold mb-1" style="color:#f87171;">Delete Account</h2>
    <p class="text-xs mb-4" style="color:#64748b;">
        Once deleted, all data will be permanently removed and cannot be recovered.
    </p>

    <button onclick="document.getElementById('confirm-delete').classList.remove('hidden')"
            class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
            style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
        🗑 DELETE MY ACCOUNT
    </button>

    {{-- Confirm modal --}}
    <div id="confirm-delete" class="hidden mt-4">
        <div class="rounded-2xl p-4" style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);">
            <p class="text-sm font-semibold mb-3" style="color:#f87171;">
                ⚠️ Are you sure? This cannot be undone.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="mb-3">
                    <label class="section-label mb-2 block">// confirm your password</label>
                    <input type="password" name="password" required
                           placeholder="Enter password to confirm"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.3);">
                    @error('password','userDeletion')
                        <p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button"
                            onclick="document.getElementById('confirm-delete').classList.add('hidden')"
                            class="py-2.5 rounded-xl text-sm font-semibold heading tracking-wider"
                            style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
                        CANCEL
                    </button>
                    <button type="submit"
                            class="py-2.5 rounded-xl text-sm font-semibold heading tracking-wider"
                            style="background:rgba(248,113,113,0.15);border:1px solid rgba(248,113,113,0.3);color:#f87171;">
                        CONFIRM DELETE
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>