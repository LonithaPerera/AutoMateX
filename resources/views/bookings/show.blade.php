<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('bookings.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.my_bookings_label') }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ $booking->service_type }}</span>
    </nav>

    {{-- Header --}}
    @php
        $statusColor = match($booking->status) {
            'pending'   => ['bg'=>'rgba(251,191,36,0.1)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
            'confirmed' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
            'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
            default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="mb-5 fade-in fade-in-1 flex items-start justify-between">
        <div>
            <p class="section-label mb-1">{{ __('app.booking_detail_label') }}</p>
            <h1 class="heading text-2xl font-bold text-white leading-tight">{{ $booking->service_type }}</h1>
            <p class="text-xs mt-1" style="color:#64748b;">
                {{ $booking->garage->name ?? '—' }} · {{ $booking->garage->city ?? '' }}
            </p>
        </div>
        <span class="tag mt-1 flex-shrink-0" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};border:1px solid {{ $statusColor['border'] }};">
            {{ strtoupper($booking->status) }}
        </span>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
        <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
        <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);">
        <x-heroicon-o-x-circle class="w-4 h-4 inline-block mr-1" style="color:#f87171;" />
        <span class="text-sm" style="color:#f87171;">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Appointment details --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-3">{{ __('app.appointment_details_label') }}</p>
        <div class="grid grid-cols-2 gap-3">
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ __('app.date_label') }}</p>
                <p class="mono text-sm font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ __('app.time_label') }}</p>
                <p class="mono text-sm font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ __('app.vehicle_label') }}</p>
                <p class="text-sm font-bold text-white">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                <p class="mono text-xs mt-0.5" style="color:#64748b;">{{ $booking->vehicle->license_plate }}</p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ __('app.garage_label') }}</p>
                <p class="text-sm font-bold text-white">{{ $booking->garage->name ?? '—' }}</p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ $booking->garage->city ?? '' }}</p>
            </div>
        </div>

        {{-- Garage phone --}}
        @if($booking->garage?->phone)
        <div class="mt-3 rounded-xl p-3 flex items-center gap-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <x-heroicon-o-phone class="w-4 h-4 flex-shrink-0" style="color:#64748b;" />
            <div>
                <p class="text-xs" style="color:#64748b;">{{ __('app.garage_phone_label') }}</p>
                <p class="mono text-sm font-bold text-white">{{ $booking->garage->phone }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Customer notes --}}
    @if($booking->notes)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.1);">
        <p class="section-label mb-2">{{ __('app.your_notes_label') }}</p>
        <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $booking->notes }}</p>
    </div>
    @endif

    {{-- Garage reply --}}
    @if($booking->garage_notes)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2" style="background:rgba(0,245,255,0.03);border-color:rgba(0,245,255,0.15);">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-chat-bubble-left-ellipsis class="w-4 h-4 flex-shrink-0" style="color:rgba(0,245,255,0.5);" />
            <p class="section-label">{{ __('app.garage_reply_label') }}</p>
        </div>
        <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $booking->garage_notes }}</p>
    </div>
    @endif

    {{-- Invoice --}}
    @if($booking->status === 'completed' && $booking->invoice_amount)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="background:rgba(74,222,128,0.04);border-color:rgba(74,222,128,0.2);">
        <div class="flex items-center justify-between mb-3">
            <p class="section-label">{{ __('app.invoice_label') }}</p>
            @if($booking->invoice_number)
            <span class="mono text-xs" style="color:#64748b;">{{ $booking->invoice_number }}</span>
            @endif
        </div>
        <p class="heading text-2xl font-bold mb-1" style="color:#4ade80;">LKR {{ number_format($booking->invoice_amount) }}</p>
        @if($booking->invoice_date)
        <p class="text-xs mb-2" style="color:#64748b;">{{ __('app.invoice_date_label') }}: {{ \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y') }}</p>
        @endif
        @if($booking->invoice_notes)
        <p class="text-sm leading-relaxed mb-3" style="color:#64748b;">{{ $booking->invoice_notes }}</p>
        @endif
        <a href="{{ route('bookings.invoice.show', $booking) }}"
           class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
            <x-heroicon-o-document-text class="w-4 h-4" />
            {{ __('app.view_invoice_btn') }}
        </a>
    </div>
    @endif

    {{-- Rating --}}
    @if($booking->status === 'completed')
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="border-color:rgba(251,191,36,0.15);">
        @if($booking->rating)
        <p class="section-label mb-2">{{ __('app.your_rating_label') }}</p>
        <div class="flex gap-0.5 mb-1">
            @for($i = 1; $i <= 5; $i++)
            <span style="font-size:24px;color:{{ $i <= $booking->rating->rating ? '#fbbf24' : '#334155' }};">★</span>
            @endfor
        </div>
        @if($booking->rating->review)
        <p class="text-sm mt-2 leading-relaxed" style="color:#64748b;">{{ $booking->rating->review }}</p>
        @endif
        @else
        <p class="section-label mb-3">{{ __('app.rate_service_label') }}</p>
        <form method="POST" action="{{ route('ratings.store') }}">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
            <div class="flex gap-1.5 mb-3" id="detail-stars">
                @for($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                    <span class="star-btn text-3xl transition-all select-none" style="color:#334155;"
                          onmouseover="hoverStars('detail-stars', {{ $i }})"
                          onmouseout="resetStars('detail-stars')"
                          onclick="selectStars('detail-stars', {{ $i }})">★</span>
                </label>
                @endfor
            </div>
            <textarea name="review" rows="3"
                      placeholder="{{ __('app.write_review_ph') }}"
                      class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none mb-3"
                      style="background:rgba(255,255,255,0.04);border:1px solid rgba(251,191,36,0.15);"></textarea>
            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95 border"
                    style="background:rgba(251,191,36,0.1);border-color:rgba(251,191,36,0.25);color:#fbbf24;">
                {{ __('app.submit_rating_btn') }}
            </button>
        </form>
        @endif
    </div>
    @endif

    {{-- Cancel booking --}}
    @if(in_array($booking->status, ['pending', 'confirmed']))
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="background:rgba(248,113,113,0.03);border-color:rgba(248,113,113,0.15);">
        <p class="section-label mb-1" style="color:rgba(248,113,113,0.6);">{{ __('app.cancel_booking_label') }}</p>
        <p class="text-xs mb-3" style="color:#475569;">{{ __('app.cancel_booking_hint') }}</p>
        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
            @csrf @method('PATCH')
            <textarea name="cancel_reason" rows="2"
                      placeholder="{{ __('app.cancel_reason_ph') }}"
                      class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none mb-2"
                      style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.15);"></textarea>
            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95 border"
                    style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);color:#f87171;"
                    onclick="return confirm('{{ __('app.cancel_confirm_msg') }}')">
                <x-heroicon-o-x-circle class="w-4 h-4 inline-block mr-1 align-middle" />
                {{ __('app.cancel_booking_btn') }}
            </button>
        </form>
    </div>
    @endif

    {{-- Cancel reason (if already cancelled) --}}
    @if($booking->status === 'cancelled' && $booking->cancel_reason)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="background:rgba(248,113,113,0.04);border-color:rgba(248,113,113,0.15);">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-x-circle class="w-4 h-4 flex-shrink-0" style="color:rgba(248,113,113,0.5);" />
            <p class="section-label" style="color:rgba(248,113,113,0.5);">{{ __('app.cancel_reason_label') }}</p>
        </div>
        <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $booking->cancel_reason }}</p>
    </div>
    @endif

    {{-- Back --}}
    <a href="{{ route('bookings.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        ← {{ __('app.back_to_bookings') }}
    </a>

</div>

<script>
function hoverStars(containerId, n) {
    document.querySelectorAll(`#${containerId} .star-btn`).forEach((s, i) => {
        s.style.color = i < n ? '#fbbf24' : '#334155';
    });
}
function resetStars(containerId) {
    const sel = document.querySelector(`#${containerId} input[type=radio]:checked`);
    const val = sel ? parseInt(sel.value) : 0;
    document.querySelectorAll(`#${containerId} .star-btn`).forEach((s, i) => {
        s.style.color = i < val ? '#fbbf24' : '#334155';
    });
}
function selectStars(containerId, n) {
    document.querySelectorAll(`#${containerId} .star-btn`).forEach((s, i) => {
        s.style.color = i < n ? '#fbbf24' : '#334155';
    });
}
</script>
</x-app-layout>
