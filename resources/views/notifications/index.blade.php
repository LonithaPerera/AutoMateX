<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.notifications_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">{{ __('app.notifications_title') }}</h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.notifications_hint') }}</p>
        </div>
        @if($notifications->where('read_at', null)->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}" class="flex-shrink-0 mt-1">
            @csrf @method('PATCH')
            <button type="submit"
                    class="px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                    style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);color:rgba(0,245,255,0.7);">
                {{ __('app.mark_all_read_btn') }}
            </button>
        </form>
        @endif
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
        <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
        <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
    </div>
    @endif

    @if($notifications->isEmpty())
        {{-- Empty state --}}
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-bell class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_notifications') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.no_notifications_hint') }}</p>
        </div>
    @else
        <div class="space-y-2">
        @foreach($notifications as $notif)
        @php
            $isUnread = $notif->read_at === null;
            $typeColors = [
                'booking_new'         => ['icon_color' => '#00f5ff',  'bg' => 'rgba(0,245,255,0.05)',  'border' => 'rgba(0,245,255,0.15)'],
                'booking_confirmed'   => ['icon_color' => '#00f5ff',  'bg' => 'rgba(0,245,255,0.05)',  'border' => 'rgba(0,245,255,0.15)'],
                'booking_completed'   => ['icon_color' => '#4ade80',  'bg' => 'rgba(74,222,128,0.05)', 'border' => 'rgba(74,222,128,0.15)'],
                'booking_cancelled'   => ['icon_color' => '#f87171',  'bg' => 'rgba(248,113,113,0.05)','border' => 'rgba(248,113,113,0.15)'],
                'booking_rescheduled' => ['icon_color' => '#60a5fa',  'bg' => 'rgba(96,165,250,0.05)', 'border' => 'rgba(96,165,250,0.15)'],
            ];
            $colors = $typeColors[$notif->type] ?? ['icon_color' => '#64748b', 'bg' => 'rgba(255,255,255,0.03)', 'border' => 'rgba(255,255,255,0.08)'];
        @endphp
        <div class="glass-bright rounded-2xl p-4 border fade-in transition-all {{ $isUnread ? 'unread-notif' : '' }}"
             style="background:{{ $colors['bg'] }};border-color:{{ $colors['border'] }};">
            <div class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background:rgba(255,255,255,0.04);border:1px solid {{ $colors['border'] }};">
                    @if(str_contains($notif->type, 'completed'))
                        <x-heroicon-o-check-circle class="w-4 h-4" style="color:{{ $colors['icon_color'] }};" />
                    @elseif(str_contains($notif->type, 'cancelled'))
                        <x-heroicon-o-x-circle class="w-4 h-4" style="color:{{ $colors['icon_color'] }};" />
                    @elseif(str_contains($notif->type, 'rescheduled'))
                        <x-heroicon-o-arrow-path class="w-4 h-4" style="color:{{ $colors['icon_color'] }};" />
                    @else
                        <x-heroicon-o-bell class="w-4 h-4" style="color:{{ $colors['icon_color'] }};" />
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <p class="text-sm font-semibold text-white leading-tight">{{ $notif->title }}</p>
                        @if($isUnread)
                        <span class="tag flex-shrink-0 text-xs"
                              style="background:rgba(0,245,255,0.1);color:#00f5ff;border:1px solid rgba(0,245,255,0.2);">
                            {{ __('app.unread_tag') }}
                        </span>
                        @endif
                    </div>
                    <p class="text-xs leading-relaxed" style="color:#94a3b8;">{{ $notif->message }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <p class="mono text-xs" style="color:#475569;">
                            {{ $notif->created_at->diffForHumans() }}
                        </p>
                        @if($notif->url)
                        <a href="{{ $notif->url }}"
                           class="text-xs font-semibold heading tracking-wider transition-colors"
                           style="color:{{ $colors['icon_color'] }};">
                            VIEW →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    @endif

</div>
</x-app-layout>
