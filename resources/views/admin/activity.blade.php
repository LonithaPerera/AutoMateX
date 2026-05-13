<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm mb-3"
           style="color:#64748b;">
            ← {{ __('app.admin_back_dashboard') }}
        </a>
        <p class="section-label mb-1">{{ __('app.activity_log_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">{{ __('app.activity_log_title') }}</h1>
        <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.activity_log_desc') }}</p>
    </div>

    {{-- Log entries --}}
    @if($logs->isEmpty())
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-clipboard-document-list class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_activity_yet') }}</p>
        </div>
    @else
        <div class="space-y-2 fade-in fade-in-2">
        @foreach($logs as $log)
        @php
            $actionColors = [
                'make_admin'     => ['bg' => 'rgba(0,245,255,0.05)',  'border' => 'rgba(0,245,255,0.15)',  'icon' => '#00f5ff'],
                'delete_user'    => ['bg' => 'rgba(248,113,113,0.05)','border' => 'rgba(248,113,113,0.18)','icon' => '#f87171'],
                'toggle_garage'  => ['bg' => 'rgba(251,191,36,0.05)', 'border' => 'rgba(251,191,36,0.18)', 'icon' => '#fbbf24'],
            ];
            $c = $actionColors[$log->action] ?? ['bg' => 'rgba(255,255,255,0.02)', 'border' => 'rgba(255,255,255,0.07)', 'icon' => '#64748b'];
        @endphp
        <div class="glass-bright rounded-2xl p-4 border fade-in"
             style="background:{{ $c['bg'] }};border-color:{{ $c['border'] }};">
            <div class="flex items-start gap-3">
                {{-- Icon --}}
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                     style="background:rgba(255,255,255,0.04);border:1px solid {{ $c['border'] }};">
                    @if($log->action === 'make_admin')
                        <x-heroicon-o-shield-check class="w-4 h-4" style="color:{{ $c['icon'] }};" />
                    @elseif($log->action === 'delete_user')
                        <x-heroicon-o-trash class="w-4 h-4" style="color:{{ $c['icon'] }};" />
                    @elseif($log->action === 'toggle_garage')
                        <x-heroicon-o-building-storefront class="w-4 h-4" style="color:{{ $c['icon'] }};" />
                    @else
                        <x-heroicon-o-bolt class="w-4 h-4" style="color:{{ $c['icon'] }};" />
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-0.5">
                        <span class="tag text-xs"
                              style="background:rgba(255,255,255,0.04);color:{{ $c['icon'] }};border:1px solid {{ $c['border'] }};">
                            {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                        </span>
                        <p class="mono text-xs flex-shrink-0" style="color:#475569;">
                            {{ $log->created_at->format('d M Y · H:i') }}
                        </p>
                    </div>
                    <p class="text-sm text-white mt-1 leading-snug">{{ $log->description }}</p>
                    @if($log->admin)
                    <p class="text-xs mt-1.5" style="color:#64748b;">
                        <x-heroicon-o-user class="w-3 h-3 inline-block mr-0.5 align-middle" style="color:#64748b;" />
                        {{ $log->admin->name }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="mt-5 flex justify-center">
            {{ $logs->links() }}
        </div>
        @endif
    @endif

</div>
</x-app-layout>
