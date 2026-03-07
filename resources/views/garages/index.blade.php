<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">// GARAGE NETWORK</p>
            <h1 class="heading text-3xl font-bold text-white">
                Find a <span class="text-cyan">Garage</span>
            </h1>
            <p class="text-xs mt-1" style="color:#64748b;">Browse registered service centres</p>
        </div>
        @if(auth()->user()->role === 'garage' && !auth()->user()->garage)
        <a href="{{ route('garages.create') }}"
           class="px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            + REGISTER
        </a>
        @endif
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">✓ {{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $garages->count() }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Active Garages</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">24/7</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Online Booking</p>
        </div>
    </div>

    {{-- Garage list --}}
    <p class="section-label mb-3 fade-in fade-in-2">// AVAILABLE GARAGES</p>

    @forelse($garages as $index => $garage)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        <div class="flex items-start justify-between mb-3">
            <div class="flex items-start gap-3">
                <div class="rounded-xl p-2.5 mt-0.5" style="background:rgba(0,245,255,0.1);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00f5ff" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">{{ $garage->name }}</h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        📍 {{ $garage->city }}
                        @if($garage->phone) · {{ $garage->phone }} @endif
                    </p>
                </div>
            </div>
            <span class="tag" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);">
                ACTIVE
            </span>
        </div>

        @if($garage->specialisation)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
            <p class="text-xs mb-0.5 section-label">// specialisation</p>
            <p class="text-sm text-white">{{ $garage->specialisation }}</p>
        </div>
        @endif

        @if($garage->description)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#64748b;">{{ Str::limit($garage->description, 100) }}</p>
        </div>
        @endif

        @if($garage->address)
        <p class="text-xs mb-3" style="color:#64748b;">🏠 {{ $garage->address }}</p>
        @endif

        @if(auth()->user()->role === 'vehicle_owner')
        <a href="{{ route('bookings.create', $garage) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 16px rgba(0,245,255,0.2);">
            📅 BOOK APPOINTMENT
        </a>
        @endif
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <div class="text-5xl mb-4">🏪</div>
            <p class="heading text-xl font-bold text-white mb-1">No Garages Yet</p>
            <p class="text-sm" style="color:#64748b;">Garage operators can register their service centre</p>
        </div>
    @endforelse

</div>
</x-app-layout>