<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.garage_network_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.find_garage_title') }}
            </h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.browse_centres_hint') }}</p>
        </div>
        @if(auth()->user()->role === 'garage' && !auth()->user()->garage)
        <a href="{{ route('garages.create') }}"
           class="px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            {{ __('app.register_btn') }}
        </a>
        @endif
    </div>

    {{-- Success --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" /><span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $garages->count() }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.active_garages') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">24/7</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.online_booking_text') }}</p>
        </div>
    </div>

    {{-- Garage list --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.available_garages') }}</p>

    @forelse($garages as $index => $garage)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        <div class="flex items-start justify-between mb-3">
            <div class="flex items-start gap-3">
                <div class="rounded-xl p-2.5 mt-0.5" style="background:rgba(0,245,255,0.1);">
                    <x-heroicon-o-building-storefront class="w-5 h-5" style="color:#00f5ff;" />
                </div>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">{{ $garage->name }}</h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        <x-heroicon-o-map-pin class="w-3 h-3 inline-block" style="color:#64748b;" /> {{ $garage->city }}
                        @if($garage->phone) · {{ $garage->phone }} @endif
                    </p>
                    {{-- Average rating --}}
                    @if($garage->ratings_count > 0)
                    <div class="flex items-center gap-1 mt-1">
                        @php $avg = round($garage->ratings_avg_rating ?? 0, 1); @endphp
                        @for($i = 1; $i <= 5; $i++)
                        <span style="font-size:12px;color:{{ $i <= $avg ? '#fbbf24' : '#334155' }};">★</span>
                        @endfor
                        <span class="mono text-xs font-bold ml-0.5" style="color:#fbbf24;">{{ $avg }}</span>
                        <span class="text-xs" style="color:#475569;">({{ $garage->ratings_count }})</span>
                    </div>
                    @else
                    <p class="text-xs mt-1" style="color:#334155;">No ratings yet</p>
                    @endif
                </div>
            </div>
            <span class="tag" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);">
                {{ __('app.active_badge') }}
            </span>
        </div>

        @if($garage->specialization)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
            <p class="text-xs mb-0.5 section-label">{{ __('app.specialisation_label') }}</p>
            <p class="text-sm text-white">{{ $garage->specialization }}</p>
        </div>
        @endif

        @if($garage->description)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#64748b;">{{ Str::limit($garage->description, 100) }}</p>
        </div>
        @endif

        @if($garage->address)
        <p class="text-xs mb-3" style="color:#64748b;"><x-heroicon-o-map-pin class="w-3 h-3 inline-block mr-1" style="color:#64748b;" />{{ $garage->address }}</p>
        @endif

        @if(auth()->user()->role === 'vehicle_owner')
        <a href="{{ route('bookings.create', $garage) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 16px rgba(0,245,255,0.2);">
            <x-heroicon-o-calendar-days class="w-4 h-4 inline-block mr-1 align-middle" /> {{ __('app.book_appt_btn') }}
        </a>
        @endif
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-building-storefront class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_garages') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.garages_hint') }}</p>
        </div>
    @endforelse

</div>
</x-app-layout>