<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// MAINTENANCE AI</p>
        <h1 class="heading text-3xl font-bold text-white">
            Smart <span class="text-cyan">Suggestions</span>
        </h1>
        <p class="text-sm mt-1" style="color:#64748b;">
            {{ $vehicle->make }} {{ $vehicle->model }} ·
            <span class="mono" style="color:var(--cyan);">{{ number_format($vehicle->mileage) }} km</span>
        </p>
    </div>

    {{-- Summary cards --}}
    @php
        $overdue  = collect($suggestions)->where('status','Overdue')->count();
        $dueSoon  = collect($suggestions)->where('status','Due Soon')->count();
        $upcoming = collect($suggestions)->where('status','Upcoming')->count();
    @endphp
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(255,60,60,0.08);border-color:rgba(255,60,60,0.2);">
            <p class="heading text-2xl font-bold" style="color:#f87171;">{{ $overdue }}</p>
            <p class="text-xs mt-0.5" style="color:#f87171;opacity:0.7;">Overdue</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(255,107,0,0.08);border-color:rgba(255,107,0,0.2);">
            <p class="heading text-2xl font-bold" style="color:var(--orange);">{{ $dueSoon }}</p>
            <p class="text-xs mt-0.5" style="color:var(--orange);opacity:0.7;">Due Soon</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $upcoming }}</p>
            <p class="text-xs mt-0.5" style="color:rgba(0,245,255,0.6);">Upcoming</p>
        </div>
    </div>

    {{-- Suggestion cards --}}
    <p class="section-label mb-3 fade-in fade-in-2">// MAINTENANCE SCHEDULE</p>

    @foreach($suggestions as $index => $item)
    @php
        $isOverdue  = $item['status'] === 'Overdue';
        $isDueSoon  = $item['status'] === 'Due Soon';
        $isUpcoming = $item['status'] === 'Upcoming';
        $pct = isset($item['percent']) ? min(100, max(0, $item['percent'])) : 50;

        if($isOverdue){
            $borderColor = 'rgba(255,60,60,0.4)'; $bg = 'rgba(255,60,60,0.08)';
            $accentColor = '#f87171'; $tagBg = 'rgba(255,60,60,0.15)';
            $barColor = '#f87171'; $icon = '⚠️';
        } elseif($isDueSoon){
            $borderColor = 'rgba(255,107,0,0.4)'; $bg = 'rgba(255,107,0,0.06)';
            $accentColor = 'var(--orange)'; $tagBg = 'rgba(255,107,0,0.15)';
            $barColor = '#ff6b00'; $icon = '🔔';
        } else {
            $borderColor = 'rgba(0,245,255,0.12)'; $bg = 'rgba(13,20,33,0.7)';
            $accentColor = 'var(--cyan)'; $tagBg = 'rgba(0,245,255,0.1)';
            $barColor = '#00f5ff'; $icon = '✅';
        }
    @endphp

    <div class="rounded-2xl p-4 mb-3 fade-in fade-in-{{ min($index+3,5) }} border"
         style="background:{{ $bg }};border-color:{{ $borderColor }};">
        <div class="flex items-start justify-between mb-2">
            <div class="flex items-center gap-2">
                <span>{{ $icon }}</span>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $item['service_name'] }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        Every {{ number_format($item['interval_km']) }} km
                        @if(isset($item['category'])) · {{ $item['category'] }} @endif
                    </p>
                </div>
            </div>
            <span class="tag" style="background:{{ $tagBg }};color:{{ $accentColor }};border:1px solid {{ $borderColor }};">
                {{ strtoupper($item['status']) }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="rounded-full overflow-hidden mb-2" style="height:5px;background:rgba(255,255,255,0.06);">
            <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $barColor }};box-shadow:0 0 8px {{ $barColor }};"></div>
        </div>

        <div class="flex justify-between items-center">
            <p class="text-xs" style="color:#64748b;">
                @if($isOverdue)
                    <span style="color:#f87171;">{{ number_format(abs($item['km_remaining'])) }} km overdue</span>
                @else
                    <span style="color:{{ $accentColor }};">{{ number_format($item['km_remaining']) }} km remaining</span>
                @endif
            </p>
            <p class="mono text-xs" style="color:#475569;">
                Due @ {{ number_format($item['next_due_km']) }} km
            </p>
        </div>
    </div>
    @endforeach

    {{-- Back button --}}
    <div class="mt-4 mb-6 fade-in fade-in-5">
        <a href="{{ route('vehicles.index') }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl transition-all"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            ← Back to Vehicles
        </a>
    </div>

</div>
</x-app-layout>