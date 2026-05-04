<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.system_control_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">{{ __('app.admin_garages_title') }}</h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.admin_garages_desc') }}</p>
        </div>
        <a href="{{ route('admin.schedules') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1 flex-shrink-0"
           style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);color:#a855f7;">
            <x-heroicon-o-light-bulb class="w-3.5 h-3.5" />
            {{ __('app.admin_nav_schedules') }}
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3 mb-4 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">{{ $garages->where('is_active',true)->count() }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.admin_garage_active') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(248,113,113,0.05);border-color:rgba(248,113,113,0.15);">
            <p class="heading text-2xl font-bold" style="color:#f87171;">{{ $garages->where('is_active',false)->count() }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.admin_garage_inactive') }}</p>
        </div>
    </div>

    {{-- Search & Filter --}}
    <div class="mb-4 fade-in fade-in-2 space-y-2">
        <input type="text" id="garage-search"
               placeholder="{{ __('app.admin_garage_search_ph') }}"
               oninput="filterGarages()"
               class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(251,191,36,0.2);">
        <div class="flex gap-2">
            <button onclick="setGarageFilter('all')" id="gfilter-all"
                    class="flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                    style="background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.3);color:#fbbf24;">
                {{ __('app.admin_filter_all') }}
            </button>
            <button onclick="setGarageFilter('active')" id="gfilter-active"
                    class="flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                    style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
                {{ __('app.admin_garage_active') }}
            </button>
            <button onclick="setGarageFilter('inactive')" id="gfilter-inactive"
                    class="flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                    style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
                {{ __('app.admin_garage_inactive') }}
            </button>
        </div>
    </div>

    {{-- Garage list --}}
    @forelse($garages as $index => $garage)
    <div class="glass-bright rounded-2xl mb-3 border fade-in fade-in-{{ min($index+3,5) }} garage-card overflow-hidden"
         data-search="{{ strtolower($garage->name . ' ' . $garage->city) }}"
         data-status="{{ $garage->is_active ? 'active' : 'inactive' }}"
         style="border-color:rgba({{ $garage->is_active ? '0,245,255' : '255,255,255' }},0.08);">

        {{-- Garage photo banner --}}
        @if($garage->photo)
        <img src="{{ asset('storage/' . $garage->photo) }}"
             alt="{{ $garage->name }}"
             class="w-full object-cover" style="max-height:120px;">
        @else
        <div class="flex items-center justify-center" style="height:60px;background:rgba(168,85,247,0.03);">
            <x-heroicon-o-building-office-2 class="w-7 h-7" style="color:rgba(168,85,247,0.2);" />
        </div>
        @endif

        <div class="p-4">
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="tag" style="background:rgba({{ $garage->is_active ? '74,222,128' : '248,113,113' }},0.1);color:{{ $garage->is_active ? '#4ade80' : '#f87171' }};border:1px solid rgba({{ $garage->is_active ? '74,222,128' : '248,113,113' }},0.25);">
                        {{ $garage->is_active ? __('app.admin_garage_active') : __('app.admin_garage_inactive') }}
                    </span>
                </div>
                <h3 class="heading font-bold text-white text-base leading-tight">{{ $garage->name }}</h3>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ $garage->city }} · {{ $garage->phone }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.garages_stat') }}</p>
                <p class="mono text-xs font-bold text-white truncate">{{ $garage->specialization ?? '—' }}</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.total_bookings_stat') }}</p>
                <p class="mono text-xs font-bold" style="color:#a855f7;">{{ $garage->bookings_count }}</p>
            </div>
        </div>

        @if($garage->description)
        <p class="text-xs mb-3 leading-relaxed" style="color:#475569;">{{ Str::limit($garage->description, 80) }}</p>
        @endif

        <div class="flex items-center justify-between">
            <p class="text-xs" style="color:#475569;">
                {{ __('app.joined_text') }} {{ $garage->created_at->format('d M Y') }}
                @if($garage->user) · {{ $garage->user->email }} @endif
            </p>
            <form method="POST" action="{{ route('admin.garages.toggle', $garage) }}" data-no-warn>
                @csrf @method('PATCH')
                <button type="submit"
                        class="px-3 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba({{ $garage->is_active ? '248,113,113' : '74,222,128' }},0.08);border:1px solid rgba({{ $garage->is_active ? '248,113,113' : '74,222,128' }},0.25);color:{{ $garage->is_active ? '#f87171' : '#4ade80' }};">
                    {{ $garage->is_active ? __('app.admin_garage_deactivate') : __('app.admin_garage_activate') }}
                </button>
            </form>
        </div>
        </div>{{-- /p-4 --}}
    </div>
    @empty
    <div class="rounded-2xl p-10 text-center border fade-in fade-in-3" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
        <x-heroicon-o-building-office-2 class="w-10 h-10 mx-auto mb-3" style="color:#334155;" />
        <p class="heading font-bold text-white">No garages registered yet.</p>
    </div>
    @endforelse

</div>

<script>
var activeGarageFilter = 'all';

function filterGarages() {
    var search = document.getElementById('garage-search').value.toLowerCase();
    document.querySelectorAll('.garage-card').forEach(function(card) {
        var s      = card.dataset.search || '';
        var status = card.dataset.status || '';
        var matchSearch = s.includes(search);
        var matchFilter = activeGarageFilter === 'all' || status === activeGarageFilter;
        card.style.display = (matchSearch && matchFilter) ? '' : 'none';
    });
}

function setGarageFilter(f) {
    activeGarageFilter = f;
    ['all','active','inactive'].forEach(function(x) {
        var btn = document.getElementById('gfilter-' + x);
        var active = x === f;
        btn.style.background  = active ? 'rgba(251,191,36,0.12)' : 'rgba(255,255,255,0.04)';
        btn.style.borderColor = active ? 'rgba(251,191,36,0.3)'  : 'rgba(255,255,255,0.08)';
        btn.style.color       = active ? '#fbbf24' : '#64748b';
    });
    filterGarages();
}
</script>
</x-app-layout>
