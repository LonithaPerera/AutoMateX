<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// MY BOOKINGS</p>
        <h1 class="heading text-3xl font-bold text-white">
            Service <span class="text-cyan">Appointments</span>
        </h1>
    </div>

    {{-- Stats --}}
    @php
        $pending = $bookings->where('status','pending')->count();
        $confirmed = $bookings->where('status','confirmed')->count();
        $completed = $bookings->where('status','completed')->count();
    @endphp
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(255,170,0,0.05);border-color:rgba(255,170,0,0.15);">
            <p class="heading text-2xl font-bold" style="color:#fbbf24;">{{ $pending }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Pending</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $confirmed }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Confirmed</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">{{ $completed }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Completed</p>
        </div>
    </div>

    <p class="section-label mb-3 fade-in fade-in-2">// BOOKING HISTORY</p>

    @forelse($bookings as $index => $booking)
    @php
        $statusColor = match($booking->status) {
            'pending'   => ['bg'=>'rgba(251,191,36,0.1)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
            'confirmed' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
            'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
            default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="heading font-bold text-white text-base">{{ $booking->service_type }}</h3>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->garage->name ?? 'N/A' }} · {{ $booking->garage->city ?? '' }}
                </p>
            </div>
            <span class="tag" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};border:1px solid {{ $statusColor['border'] }};">
                {{ strtoupper($booking->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">Date</p>
                <p class="mono text-sm font-bold text-white">
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                </p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">Time</p>
                <p class="mono text-sm font-bold text-white">
                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                </p>
            </div>
        </div>

        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs mb-0.5" style="color:#64748b;">Vehicle</p>
            <p class="text-sm text-white font-semibold">
                {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                · {{ $booking->vehicle->license_plate }}
            </p>
        </div>

        @if($booking->status === 'completed' && $booking->invoice_amount)
        <div class="rounded-xl p-3" style="background:rgba(74,222,128,0.06);border:1px solid rgba(74,222,128,0.15);">
            <p class="text-xs mb-1" style="color:rgba(74,222,128,0.6);">// INVOICE</p>
            <p class="heading font-bold text-lg" style="color:#4ade80;">
                LKR {{ number_format($booking->invoice_amount) }}
            </p>
            @if($booking->invoice_notes)
            <p class="text-xs mt-1" style="color:#64748b;">{{ $booking->invoice_notes }}</p>
            @endif
        </div>
        @endif
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <div class="text-5xl mb-4">📅</div>
            <p class="heading text-xl font-bold text-white mb-1">No Bookings Yet</p>
            <p class="text-sm mb-5" style="color:#64748b;">Book your first garage appointment</p>
            <a href="{{ route('garages.index') }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                BROWSE GARAGES
            </a>
        </div>
    @endforelse

</div>
</x-app-layout>