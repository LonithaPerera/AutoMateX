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

    {{-- Search & Filter --}}
    <div class="mb-4 fade-in fade-in-2 space-y-3">
        {{-- Search input --}}
        <div class="relative">
            <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#64748b;" />
            <input type="text" id="garage-search"
                   placeholder="{{ __('app.search_garages_ph') }}"
                   oninput="filterGarages()"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.12);">
        </div>

        {{-- City pills --}}
        <div class="flex flex-wrap gap-2" id="city-filters">
            <button type="button" onclick="setCityFilter('all', this)"
                    class="city-pill px-3 py-1.5 rounded-lg text-xs font-semibold heading tracking-wider transition-all active-pill"
                    style="background:rgba(0,245,255,0.15);color:#00f5ff;border:1px solid rgba(0,245,255,0.3);">
                {{ __('app.filter_all_cities') }}
            </button>
            @foreach($cities as $city)
            <button type="button" onclick="setCityFilter('{{ $city }}', this)"
                    class="city-pill px-3 py-1.5 rounded-lg text-xs font-semibold heading tracking-wider transition-all"
                    style="background:rgba(255,255,255,0.04);color:#64748b;border:1px solid rgba(255,255,255,0.08);">
                {{ $city }}
            </button>
            @endforeach
        </div>

        {{-- Sort --}}
        <div class="flex items-center gap-2">
            <span class="text-xs section-label">{{ __('app.sort_label') }}:</span>
            <select id="garage-sort" onchange="filterGarages()"
                    class="text-xs px-3 py-1.5 rounded-lg text-white outline-none"
                    style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#94a3b8;">
                <option value="">{{ __('app.sort_rating_high') }}</option>
                <option value="rating_low">{{ __('app.sort_rating_low') }}</option>
                <option value="name_az">{{ __('app.sort_name_az') }}</option>
            </select>
        </div>
    </div>

    {{-- Garage list --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.available_garages') }}</p>

    <p id="no-garages-found" class="text-sm text-center py-6 hidden" style="color:#64748b;">
        {{ __('app.no_garages_found') }}
    </p>

    @forelse($garages as $index => $garage)
    <div class="garage-card glass-bright rounded-2xl mb-3 border fade-in fade-in-{{ min($index+3,5) }} overflow-hidden"
         data-name="{{ strtolower($garage->name) }}"
         data-city="{{ $garage->city }}"
         data-rating="{{ round($garage->ratings_avg_rating ?? 0, 1) }}"
         style="border-color:rgba(0,245,255,0.1);">

        {{-- Garage photo banner --}}
        @if($garage->photo)
        <img src="{{ asset('storage/' . $garage->photo) }}"
             alt="{{ $garage->name }}"
             loading="lazy" class="w-full object-cover" style="max-height:140px;">
        @else
        <div class="flex items-center justify-center" style="height:70px;background:rgba(0,245,255,0.03);">
            <x-heroicon-o-building-storefront class="w-8 h-8" style="color:rgba(0,245,255,0.12);" />
        </div>
        @endif

        <div class="p-4">

            {{-- Name / city / rating + active badge --}}
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">{{ $garage->name }}</h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        <x-heroicon-o-map-pin class="w-3 h-3 inline-block" style="color:#64748b;" /> {{ $garage->city }}
                        @if($garage->phone) · {{ $garage->phone }} @endif
                    </p>
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
                <span class="tag flex-shrink-0 ml-2" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);">
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
            <p class="text-xs mb-3" style="color:#64748b;">
                <x-heroicon-o-map-pin class="w-3 h-3 inline-block mr-1" style="color:#64748b;" />{{ $garage->address }}
            </p>
            @endif

            {{-- Working hours today --}}
            @if($garage->working_hours)
            @php
                $dayKeys = ['mon','tue','wed','thu','fri','sat','sun'];
                $todayKey = $dayKeys[now()->dayOfWeekIso - 1] ?? null;
                $todayHours = $todayKey ? ($garage->working_hours[$todayKey] ?? null) : null;
            @endphp
            @if($todayHours)
            <div class="flex items-center gap-2 mb-3">
                <x-heroicon-o-clock class="w-3 h-3 flex-shrink-0" style="color:{{ ($todayHours['closed'] ?? false) ? '#f87171' : '#4ade80' }};" />
                @if($todayHours['closed'] ?? false)
                    <span class="text-xs" style="color:#f87171;">Closed today</span>
                @else
                    <span class="text-xs" style="color:#4ade80;">Open today</span>
                    <span class="mono text-xs" style="color:#64748b;">{{ $todayHours['open'] ?? '' }} – {{ $todayHours['close'] ?? '' }}</span>
                @endif
            </div>
            @endif
            @endif

            {{-- Recent written reviews --}}
            @if($garage->ratings->isNotEmpty())
            <div class="mb-3">
                <p class="text-xs section-label mb-1.5">{{ __('app.recent_reviews_label') }}</p>
                @foreach($garage->ratings as $review)
                <div class="rounded-xl p-2.5 mb-1.5" style="background:rgba(251,191,36,0.04);border:1px solid rgba(251,191,36,0.1);">
                    <div class="flex items-center gap-0.5 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                        <span style="font-size:10px;color:{{ $i <= $review->rating ? '#fbbf24' : '#334155' }};">★</span>
                        @endfor
                    </div>
                    <p class="text-xs leading-relaxed" style="color:#94a3b8;">{{ Str::limit($review->review, 90) }}</p>
                </div>
                @endforeach
            </div>
            @endif

            <div class="flex gap-2">
                <a href="{{ route('garages.show', $garage) }}"
                   class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                   style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                    <x-heroicon-o-eye class="w-3.5 h-3.5" /> {{ __('app.view_garage_profile_btn') }}
                </a>
                @if(auth()->user()->role === 'vehicle_owner')
                <a href="{{ route('bookings.create', $garage) }}"
                   class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                   style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 12px rgba(0,245,255,0.2);">
                    <x-heroicon-o-calendar-days class="w-3.5 h-3.5" /> {{ __('app.book_appt_btn') }}
                </a>
                @endif
            </div>

        </div>{{-- /p-4 --}}
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-building-storefront class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_garages') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.garages_hint') }}</p>
        </div>
    @endforelse

</div>

<script>
let activeCity = 'all';

function setCityFilter(city, btn) {
    activeCity = city;
    document.querySelectorAll('.city-pill').forEach(p => {
        p.style.background = 'rgba(255,255,255,0.04)';
        p.style.color = '#64748b';
        p.style.borderColor = 'rgba(255,255,255,0.08)';
    });
    btn.style.background = 'rgba(0,245,255,0.15)';
    btn.style.color = '#00f5ff';
    btn.style.borderColor = 'rgba(0,245,255,0.3)';
    filterGarages();
}

function filterGarages() {
    const q     = document.getElementById('garage-search').value.toLowerCase().trim();
    const sort  = document.getElementById('garage-sort').value;
    const cards = Array.from(document.querySelectorAll('.garage-card'));

    // Filter
    let visible = cards.filter(c => {
        const matchCity = activeCity === 'all' || c.dataset.city === activeCity;
        const matchQ    = !q || c.dataset.name.includes(q) || c.dataset.city.toLowerCase().includes(q);
        return matchCity && matchQ;
    });

    // Sort
    visible.sort((a, b) => {
        if (sort === 'rating_low') return parseFloat(a.dataset.rating) - parseFloat(b.dataset.rating);
        if (sort === 'name_az')    return a.dataset.name.localeCompare(b.dataset.name);
        return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating); // default: high rating first
    });

    // Hide all first
    cards.forEach(c => c.style.display = 'none');

    // Reorder and show visible
    const parent = cards[0]?.parentNode;
    if (parent) {
        visible.forEach(c => {
            c.style.display = '';
            parent.appendChild(c);
        });
    }

    document.getElementById('no-garages-found').classList.toggle('hidden', visible.length > 0);
}
</script>
</x-app-layout>