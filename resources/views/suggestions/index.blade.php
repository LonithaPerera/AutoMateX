<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ __('app.maintenance_ai_label') }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.maintenance_ai_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.smart_suggestions_title') }}
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
            <p class="text-xs mt-0.5" style="color:#f87171;opacity:0.7;">{{ __('app.overdue_status') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(255,107,0,0.08);border-color:rgba(255,107,0,0.2);">
            <p class="heading text-2xl font-bold" style="color:var(--orange);">{{ $dueSoon }}</p>
            <p class="text-xs mt-0.5" style="color:var(--orange);opacity:0.7;">{{ __('app.due_soon_status') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $upcoming }}</p>
            <p class="text-xs mt-0.5" style="color:rgba(0,245,255,0.6);">{{ __('app.upcoming_status') }}</p>
        </div>
    </div>

    {{-- Suggestion cards --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.maintenance_sched_label') }}</p>

    @foreach($suggestions as $index => $item)
    @php
        $isOverdue  = $item['status'] === 'Overdue';
        $isDueSoon  = $item['status'] === 'Due Soon';
        $isUpcoming = $item['status'] === 'Upcoming';
        $pct = isset($item['percent']) ? min(100, max(0, $item['percent'])) : 50;

        if($isOverdue){
            $borderColor = 'rgba(255,60,60,0.4)'; $bg = 'rgba(255,60,60,0.08)';
            $accentColor = '#f87171'; $tagBg = 'rgba(255,60,60,0.15)';
            $barColor = '#f87171';
        } elseif($isDueSoon){
            $borderColor = 'rgba(255,107,0,0.4)'; $bg = 'rgba(255,107,0,0.06)';
            $accentColor = 'var(--orange)'; $tagBg = 'rgba(255,107,0,0.15)';
            $barColor = '#ff6b00';
        } else {
            $borderColor = 'rgba(0,245,255,0.12)'; $bg = 'rgba(13,20,33,0.7)';
            $accentColor = 'var(--cyan)'; $tagBg = 'rgba(0,245,255,0.1)';
            $barColor = '#00f5ff';
        }
    @endphp

    <div class="rounded-2xl p-4 mb-3 fade-in fade-in-{{ min($index+3,5) }} border"
         style="background:{{ $bg }};border-color:{{ $borderColor }};">
        <div class="flex items-start justify-between gap-2 mb-2">
            <div class="flex items-start gap-2 flex-1 min-w-0">
                @if($isOverdue)
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 flex-shrink-0 mt-0.5" style="color:#f87171;" />
                @elseif($isDueSoon)
                    <x-heroicon-o-bell class="w-5 h-5 flex-shrink-0 mt-0.5" style="color:var(--orange);" />
                @else
                    <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0 mt-0.5" style="color:var(--cyan);" />
                @endif
                <div class="min-w-0">
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $item['service_name'] }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ __('app.every_km', ['km' => number_format($item['interval_km'])]) }}
                        @if(isset($item['category'])) · {{ $item['category'] }} @endif
                    </p>
                    @if(!empty($item['description']))
                    <p class="text-xs mt-1.5 leading-relaxed" style="color:#475569;">
                        {{ $item['description'] }}
                    </p>
                    @endif
                </div>
            </div>
            <span class="tag flex-shrink-0" style="background:{{ $tagBg }};color:{{ $accentColor }};border:1px solid {{ $borderColor }};">
                @if($isOverdue){{ strtoupper(__('app.overdue_status')) }}@elseif($isDueSoon){{ strtoupper(__('app.due_soon_status')) }}@else{{ strtoupper(__('app.upcoming_status')) }}@endif
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="rounded-full overflow-hidden mb-2" style="height:5px;background:rgba(255,255,255,0.06);">
            <div class="h-full rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $barColor }};box-shadow:0 0 8px {{ $barColor }};"></div>
        </div>

        <div class="flex justify-between items-center">
            <p class="text-xs" style="color:#64748b;">
                @if($isOverdue)
                    <span style="color:#f87171;">{{ __('app.km_overdue_text', ['km' => number_format(abs($item['km_remaining']))]) }}</span>
                @else
                    <span style="color:{{ $accentColor }};">{{ __('app.km_remaining_text', ['km' => number_format($item['km_remaining'])]) }}</span>
                @endif
                @if(!$isOverdue && isset($item['days_left']) && $item['days_left'] !== null)
                    <span style="color:#475569;"> · ~{{ $item['days_left'] }} days</span>
                @endif
            </p>
            <p class="mono text-xs" style="color:#475569;">
                {{ __('app.due_at_text', ['km' => number_format($item['next_due_km'])]) }}
            </p>
        </div>

        {{-- Book a Garage button — only for Overdue and Due Soon --}}
        @if($isOverdue || $isDueSoon)
        <a href="{{ route('garages.index') }}"
           class="mt-3 flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:{{ $tagBg }};border:1px solid {{ $borderColor }};color:{{ $accentColor }};">
            <x-heroicon-o-wrench-screwdriver class="w-3.5 h-3.5" />
            {{ __('app.book_garage_btn') }}
        </a>
        @endif

    </div>
    @endforeach

    {{-- Back button --}}
    <div class="mt-4 mb-6 fade-in fade-in-5">
        <a href="{{ route('vehicles.show', $vehicle) }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl transition-all"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            {{ __('app.back_to_vehicles') }}
        </a>
    </div>

</div>
</x-app-layout>