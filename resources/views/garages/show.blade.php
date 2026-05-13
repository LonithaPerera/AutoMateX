<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('garages.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_garages') }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ $garage->name }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.garage_profile_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white leading-tight">{{ $garage->name }}</h1>
        <p class="text-xs mt-1" style="color:#64748b;">
            <x-heroicon-o-map-pin class="w-3 h-3 inline-block" style="color:#64748b;" />
            {{ $garage->address }} · {{ $garage->city }}
            @if($garage->phone) · {{ $garage->phone }} @endif
        </p>
    </div>

    {{-- Photo --}}
    @if($garage->photo)
    <div class="rounded-2xl overflow-hidden mb-4 fade-in border" style="border-color:rgba(0,245,255,0.12);">
        <img src="{{ asset('storage/' . $garage->photo) }}" alt="{{ $garage->name }}"
             loading="lazy" class="w-full object-cover" style="max-height:180px;">
    </div>
    @endif

    {{-- Stats row --}}
    @php $avg = round($garage->ratings_avg_rating ?? 0, 1); @endphp
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
            <p class="heading text-2xl font-bold" style="color:#fbbf24;">{{ $avg > 0 ? $avg : '—' }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.garage_avg_rating') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $garage->ratings_count }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.reviews') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">{{ $completedJobs }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.garage_completed_jobs') }}</p>
        </div>
    </div>

    {{-- Specialization + Description --}}
    @if($garage->specialization)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.1);">
        <p class="text-xs section-label mb-1">{{ __('app.specialisation_label') }}</p>
        <p class="text-sm text-white font-semibold">{{ $garage->specialization }}</p>
        @if($garage->description)
        <p class="text-xs mt-2 leading-relaxed" style="color:#64748b;">{{ $garage->description }}</p>
        @endif
    </div>
    @elseif($garage->description)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.1);">
        <p class="text-xs leading-relaxed" style="color:#64748b;">{{ $garage->description }}</p>
    </div>
    @endif

    {{-- Working hours --}}
    @if($garage->working_hours)
    @php
        $days = ['mon','tue','wed','thu','fri','sat','sun'];
        $dayLabels = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
        $todayKey = $days[\Carbon\Carbon::now()->dayOfWeekIso - 1] ?? null;
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.1);">
        <p class="section-label mb-3">{{ __('app.working_hours_label') }}</p>
        <div class="grid grid-cols-4 gap-1.5">
            @foreach($days as $day)
            @php
                $dh = $garage->working_hours[$day] ?? null;
                $isClosed = $dh['closed'] ?? true;
                $isToday  = $day === $todayKey;
            @endphp
            <div class="rounded-lg px-1.5 py-1.5 text-center {{ $isToday ? 'ring-1' : '' }}"
                 style="background:{{ $isClosed ? 'rgba(248,113,113,0.06)' : 'rgba(74,222,128,0.06)' }};border:1px solid {{ $isClosed ? 'rgba(248,113,113,0.15)' : 'rgba(74,222,128,0.15)' }};">
                <p class="text-xs font-bold heading" style="color:{{ $isToday ? '#00f5ff' : '#94a3b8' }};">{{ $dayLabels[$day] }}</p>
                @if($isClosed)
                    <p class="mono" style="font-size:8px;color:#f87171;">CLOSED</p>
                @else
                    <p class="mono" style="font-size:7px;color:#4ade80;line-height:1.4;">{{ $dh['open'] ?? '' }}<br>{{ $dh['close'] ?? '' }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Rating breakdown --}}
    @if($garage->ratings_count > 0)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(251,191,36,0.12);">
        <div class="flex items-center gap-3 mb-3">
            <div>
                <p class="heading text-4xl font-bold" style="color:#fbbf24;">{{ $avg }}</p>
                <div class="flex gap-0.5 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <span style="font-size:14px;color:{{ $i <= $avg ? '#fbbf24' : '#334155' }};">★</span>
                    @endfor
                </div>
                <p class="text-xs mt-1" style="color:#64748b;">{{ $garage->ratings_count }} {{ __('app.reviews') }}</p>
            </div>
            <div class="flex-1 space-y-1">
                @for($i = 5; $i >= 1; $i--)
                @php $cnt = $ratingBreakdown[$i] ?? 0; $pct = $garage->ratings_count > 0 ? round(($cnt / $garage->ratings_count) * 100) : 0; @endphp
                <div class="flex items-center gap-2">
                    <span class="mono text-xs w-3" style="color:#64748b;">{{ $i }}</span>
                    <div class="flex-1 rounded-full overflow-hidden" style="height:5px;background:rgba(255,255,255,0.06);">
                        <div style="width:{{ $pct }}%;height:100%;background:#fbbf24;border-radius:9999px;"></div>
                    </div>
                    <span class="mono text-xs w-6 text-right" style="color:#64748b;">{{ $cnt }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>
    @endif

    {{-- Reviews --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.all_reviews_label') }}</p>

    @forelse($reviews as $review)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in" style="border-color:rgba(251,191,36,0.1);">
        <div class="flex items-start justify-between mb-2">
            <div>
                <p class="text-sm font-semibold text-white">
                    {{ optional(optional(optional($review->booking)->vehicle)->user)->name ?? 'Customer' }}
                </p>
                <p class="text-xs" style="color:#64748b;">{{ $review->created_at->format('d M Y') }}</p>
            </div>
            <div class="flex gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                <span style="font-size:13px;color:{{ $i <= $review->rating ? '#fbbf24' : '#334155' }};">★</span>
                @endfor
            </div>
        </div>
        <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $review->review }}</p>
    </div>
    @empty
    <div class="rounded-2xl p-8 text-center border mb-4" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
        <p class="text-sm" style="color:#64748b;">{{ __('app.no_reviews_yet') }}</p>
    </div>
    @endforelse

    {{-- Book CTA --}}
    @if(auth()->user()->role === 'vehicle_owner')
    <div class="mt-2">
        <a href="{{ route('bookings.create', $garage) }}"
           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
            <x-heroicon-o-calendar-days class="w-4 h-4" />
            {{ __('app.book_appt_btn') }}
        </a>
    </div>
    @endif

</div>
</x-app-layout>
