<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// USER MANAGEMENT</p>
        <h1 class="heading text-3xl font-bold text-white">
            All <span class="text-cyan">Users</span>
        </h1>
        <p class="text-xs mt-1" style="color:#64748b;">{{ $users->count() }} registered users</p>
    </div>

    {{-- Success / Error --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">✓ {{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(248,113,113,0.06);border-color:rgba(248,113,113,0.2);">
            <span class="text-sm" style="color:#f87171;">✕ {{ session('error') }}</span>
        </div>
    @endif

    {{-- Users list --}}
    @foreach($users as $index => $user)
    @php
        $roleColor = match($user->role) {
            'admin'         => ['bg'=>'rgba(255,107,0,0.1)','color'=>'#ff6b00','border'=>'rgba(255,107,0,0.2)'],
            'garage'        => ['bg'=>'rgba(168,85,247,0.1)','color'=>'#a855f7','border'=>'rgba(168,85,247,0.2)'],
            'vehicle_owner' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            default         => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+2,5) }}"
         style="border-color:rgba(0,245,255,0.08);">

        {{-- User info --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="rounded-xl w-10 h-10 flex items-center justify-center font-bold heading text-lg flex-shrink-0"
                     style="background:rgba(0,245,255,0.1);color:var(--cyan);">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div>
                    <p class="heading font-bold text-white text-sm">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="text-xs" style="color:#475569;">(You)</span>
                        @endif
                    </p>
                    <p class="text-xs" style="color:#64748b;">{{ $user->email }}</p>
                    <p class="text-xs mt-0.5" style="color:#475569;">
                        Joined {{ $user->created_at->format('d M Y') }}
                        @if(isset($user->vehicles_count))
                         · {{ $user->vehicles_count }} vehicles
                        @endif
                    </p>
                </div>
            </div>
            <span class="tag flex-shrink-0" style="background:{{ $roleColor['bg'] }};color:{{ $roleColor['color'] }};border:1px solid {{ $roleColor['border'] }};">
                {{ strtoupper(str_replace('_',' ',$user->role)) }}
            </span>
        </div>

        {{-- Actions --}}
        @if($user->id !== auth()->id())
        <div class="grid grid-cols-2 gap-2">
            @if($user->role !== 'admin')
            <form method="POST" action="{{ route('admin.makeAdmin', $user) }}"
                  onsubmit="return confirm('Promote {{ $user->name }} to Admin?')">
                @csrf
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,107,0,0.08);border:1px solid rgba(255,107,0,0.2);color:#ff6b00;">
                    ↑ MAKE ADMIN
                </button>
            </form>
            @else
            <div></div>
            @endif

            <form method="POST" action="{{ route('admin.deleteUser', $user) }}"
                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                    🗑 DELETE
                </button>
            </form>
        </div>
        @else
        <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#475569;">This is your account</p>
        </div>
        @endif

    </div>
    @endforeach

</div>
</x-app-layout>