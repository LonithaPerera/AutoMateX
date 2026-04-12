<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.system_control_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.admin_dash_title') }}
        </h1>
        <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.system_wide_desc') }}</p>
    </div>

    {{-- Stats grid --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.system_stats_label') }}</p>
    <div class="grid grid-cols-2 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-4 border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-3xl font-bold text-cyan">{{ $stats['total_users'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.total_users') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(0,102,255,0.05);border-color:rgba(0,102,255,0.15);">
            <p class="heading text-3xl font-bold" style="color:#6699ff;">{{ $stats['total_vehicles'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.total_vehicles_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-3xl font-bold" style="color:#4ade80;">{{ $stats['total_service_logs'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.service_logs_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(255,107,0,0.05);border-color:rgba(255,107,0,0.15);">
            <p class="heading text-3xl font-bold" style="color:#ff6b00;">{{ $stats['total_fuel_logs'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.fuel_logs_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(168,85,247,0.05);border-color:rgba(168,85,247,0.15);">
            <p class="heading text-3xl font-bold" style="color:#a855f7;">{{ $stats['total_bookings'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.total_bookings_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
            <p class="heading text-3xl font-bold" style="color:#fbbf24;">{{ $stats['total_garages'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.garages_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(248,113,113,0.05);border-color:rgba(248,113,113,0.15);">
            <p class="heading text-3xl font-bold" style="color:#f87171;">{{ $stats['pending_bookings'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.pending_bookings_stat') }}</p>
        </div>
        <div class="rounded-2xl p-4 border" style="background:rgba(20,184,166,0.05);border-color:rgba(20,184,166,0.15);">
            <p class="heading text-3xl font-bold" style="color:#2dd4bf;">{{ $stats['completed_bookings'] }}</p>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.completed_stat') }}</p>
        </div>
    </div>

    {{-- Recent Users --}}
    <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.recent_users_label') }}</p>
    <div class="glass-bright rounded-2xl p-4 mb-5 border fade-in fade-in-3"
         style="border-color:rgba(0,245,255,0.1);">
        @foreach($recentUsers as $user)
        @php
            $roleColor = match($user->role) {
                'admin'         => ['bg'=>'rgba(255,107,0,0.1)','color'=>'#ff6b00','border'=>'rgba(255,107,0,0.2)'],
                'garage'        => ['bg'=>'rgba(168,85,247,0.1)','color'=>'#a855f7','border'=>'rgba(168,85,247,0.2)'],
                'vehicle_owner' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
                default         => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
            };
        @endphp
        <div class="flex items-center justify-between py-2.5 border-b last:border-0"
             style="border-color:rgba(255,255,255,0.05);">
            <div class="flex items-center gap-3">
                <div class="rounded-xl w-8 h-8 flex items-center justify-center font-bold heading text-sm"
                     style="background:rgba(0,245,255,0.1);color:var(--cyan);">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">{{ $user->name }}</p>
                    <p class="text-xs" style="color:#64748b;">{{ $user->email }}</p>
                </div>
            </div>
            <span class="tag" style="background:{{ $roleColor['bg'] }};color:{{ $roleColor['color'] }};border:1px solid {{ $roleColor['border'] }};">
                {{ strtoupper(str_replace('_',' ',$user->role)) }}
            </span>
        </div>
        @endforeach

        <a href="{{ route('admin.users') }}"
           class="flex items-center justify-center gap-2 w-full mt-3 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider"
           style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.15);color:var(--cyan);">
            {{ __('app.view_all_users_btn') }}
        </a>
    </div>

    {{-- Recent Bookings --}}
    <p class="section-label mb-3 fade-in fade-in-4">{{ __('app.recent_bookings_label') }}</p>
    <div class="glass-bright rounded-2xl p-4 border fade-in fade-in-4"
         style="border-color:rgba(0,245,255,0.1);">
        @forelse($recentBookings as $booking)
        @php
            $sc = match($booking->status) {
                'pending'   => ['bg'=>'rgba(251,191,36,0.1)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
                'confirmed' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
                'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
                'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
                default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
            };
        @endphp
        <div class="flex items-start justify-between py-2.5 border-b last:border-0"
             style="border-color:rgba(255,255,255,0.05);">
            <div>
                <p class="text-sm font-semibold text-white">{{ $booking->service_type }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->vehicle->user->name }} → {{ $booking->garage->name }}
                </p>
                <p class="text-xs" style="color:#475569;">
                    {{ $booking->booking_date->format('d M Y') }}
                </p>
            </div>
            <span class="tag" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }};">
                {{ strtoupper($booking->status) }}
            </span>
        </div>
        @empty
        <p class="text-sm text-center py-4" style="color:#64748b;">{{ __('app.no_bookings_admin') }}</p>
        @endforelse
    </div>

</div>
</x-app-layout>