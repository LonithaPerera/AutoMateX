<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.my_bookings_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.service_appointments') }}
        </h1>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);">
            <x-heroicon-o-x-circle class="w-4 h-4 inline-block mr-1" style="color:#f87171;" />
            <span class="text-sm" style="color:#f87171;">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    @php
        $pending = $bookings->where('status','pending')->count();
        $confirmed = $bookings->where('status','confirmed')->count();
        $completed = $bookings->where('status','completed')->count();
    @endphp
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(255,170,0,0.05);border-color:rgba(255,170,0,0.15);">
            <p class="heading text-2xl font-bold" style="color:#fbbf24;">{{ $pending }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.pending') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $confirmed }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.confirmed') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-2xl font-bold" style="color:#4ade80;">{{ $completed }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.completed') }}</p>
        </div>
    </div>

    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.booking_history') }}</p>

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
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.date_label') }}</p>
                <p class="mono text-sm font-bold text-white">
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                </p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.time_label') }}</p>
                <p class="mono text-sm font-bold text-white">
                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                </p>
            </div>
        </div>

        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.vehicle_label') }}</p>
            <p class="text-sm text-white font-semibold">
                {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                · {{ $booking->vehicle->license_plate }}
            </p>
        </div>

        @if($booking->status === 'completed' && $booking->invoice_amount)
        <div class="rounded-xl p-3" style="background:rgba(74,222,128,0.06);border:1px solid rgba(74,222,128,0.15);">
            <p class="text-xs mb-1" style="color:rgba(74,222,128,0.6);">{{ __('app.invoice_label') }}</p>
            <p class="heading font-bold text-lg" style="color:#4ade80;">
                LKR {{ number_format($booking->invoice_amount) }}
            </p>
            @if($booking->invoice_notes)
            <p class="text-xs mt-1" style="color:#64748b;">{{ $booking->invoice_notes }}</p>
            @endif
        </div>
        @endif

        {{-- Cancel button — only for pending or confirmed --}}
        @if(in_array($booking->status, ['pending', 'confirmed']))
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="mt-3"
              onsubmit="return confirm('{{ __('app.cancel_confirm_msg') }}')">
            @csrf @method('PATCH')
            <button type="submit"
                    class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                    style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);color:#f87171;">
                <x-heroicon-o-x-circle class="w-3.5 h-3.5 inline-block mr-1 align-middle" />
                {{ __('app.cancel_booking_btn') }}
            </button>
        </form>
        @endif
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-calendar-days class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_bookings_yet') }}</p>
            <p class="text-sm mb-5" style="color:#64748b;">{{ __('app.book_first_appt') }}</p>
            <a href="{{ route('garages.index') }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                {{ __('app.browse_garages_btn') }}
            </a>
        </div>
    @endforelse

</div>
</x-app-layout>