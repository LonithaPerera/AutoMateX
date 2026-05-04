<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.my_bookings_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.service_appointments') }}
            </h1>
        </div>
        <a href="{{ route('garages.index') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 16px rgba(0,245,255,0.25);">
            <x-heroicon-o-plus class="w-3.5 h-3.5" />
            {{ __('app.book_appt_btn') }}
        </a>
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

    {{-- Upcoming / Past tabs --}}
    @php
        $upcoming = $bookings->whereIn('status', ['pending','confirmed'])->values();
        $past     = $bookings->whereIn('status', ['completed','cancelled'])->values();
    @endphp

    {{-- Tab switcher --}}
    <div class="flex gap-2 mb-5 fade-in fade-in-2">
        <button onclick="switchTab('upcoming')" id="tab-upcoming"
                class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.3);color:#00f5ff;">
            {{ __('app.upcoming_bookings_label') }}
            <span class="ml-1 px-1.5 py-0.5 rounded-md text-xs" style="background:rgba(0,245,255,0.2);">{{ $upcoming->count() }}</span>
        </button>
        <button onclick="switchTab('past')" id="tab-past"
                class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
            {{ __('app.past_bookings_label') }}
            <span class="ml-1 px-1.5 py-0.5 rounded-md text-xs" style="background:rgba(255,255,255,0.08);">{{ $past->count() }}</span>
        </button>
    </div>

    {{-- UPCOMING --}}
    <div id="section-upcoming">
        <p class="section-label mb-3">{{ __('app.upcoming_bookings_label') }}</p>
        @forelse($upcoming as $index => $booking)
    @php
        $statusColor = match($booking->status) {
            'pending'   => ['bg'=>'rgba(251,191,36,0.1)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
            'confirmed' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
            'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
            default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }} booking-card"
         data-status="{{ $booking->status }}"
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

        {{-- Garage note / reply --}}
        @if($booking->garage_notes)
        <div class="rounded-xl p-3 mb-3 border"
             style="background:rgba(0,245,255,0.04);border-color:rgba(0,245,255,0.15);">
            <div class="flex items-center gap-1.5 mb-1.5">
                <x-heroicon-o-chat-bubble-left-ellipsis class="w-3.5 h-3.5 flex-shrink-0" style="color:rgba(0,245,255,0.5);" />
                <p class="text-xs" style="color:rgba(0,245,255,0.5);">{{ __('app.garage_reply_label') }}</p>
            </div>
            <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $booking->garage_notes }}</p>
        </div>
        @endif

        {{-- [7] Customer Rating widget --}}
        @if($booking->status === 'completed')
            @if($booking->rating)
            <div class="rounded-xl p-3 mb-3 border"
                 style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
                <div class="flex items-center gap-1.5 mb-1.5">
                    <span style="color:#fbbf24;font-size:14px;">★</span>
                    <p class="text-xs font-semibold" style="color:rgba(251,191,36,0.7);">{{ __('app.your_rating_label') }}</p>
                </div>
                <div class="flex gap-0.5 mb-1">
                    @for($i = 1; $i <= 5; $i++)
                    <span style="font-size:20px;color:{{ $i <= $booking->rating->rating ? '#fbbf24' : '#334155' }};">★</span>
                    @endfor
                </div>
                @if($booking->rating->review)
                <p class="text-xs mt-1 leading-relaxed" style="color:#64748b;">{{ $booking->rating->review }}</p>
                @endif
            </div>
            @else
            <div class="rounded-xl p-3 mb-3 border"
                 style="background:rgba(251,191,36,0.04);border-color:rgba(251,191,36,0.15);">
                <p class="text-xs font-semibold mb-2" style="color:rgba(251,191,36,0.7);">{{ __('app.rate_service_label') }}</p>
                <form method="POST" action="{{ route('ratings.store') }}">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <div class="flex gap-1 mb-2" id="ustars-{{ $booking->id }}">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                            <span class="star-btn text-2xl transition-all select-none"
                                  style="color:#334155;"
                                  onmouseover="hoverStars('ustars-{{ $booking->id }}', {{ $i }})"
                                  onmouseout="resetStars('ustars-{{ $booking->id }}')"
                                  onclick="selectStars('ustars-{{ $booking->id }}', {{ $i }})">★</span>
                        </label>
                        @endfor
                    </div>
                    <textarea name="review" rows="2"
                              placeholder="{{ __('app.write_review_ph') }}"
                              class="w-full px-3 py-2 rounded-xl text-xs text-white placeholder-slate-600 outline-none resize-none mb-2"
                              style="background:rgba(255,255,255,0.04);border:1px solid rgba(251,191,36,0.15);"></textarea>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                            style="background:rgba(251,191,36,0.08);border-color:rgba(251,191,36,0.2);color:#fbbf24;">
                        {{ __('app.submit_rating_btn') }}
                    </button>
                </form>
            </div>
            @endif
        @endif

        @if($booking->status === 'completed' && $booking->invoice_amount)
        <div class="rounded-xl p-3 mb-3" style="background:rgba(74,222,128,0.06);border:1px solid rgba(74,222,128,0.15);">
            <div class="flex items-center justify-between mb-1">
                <p class="text-xs" style="color:rgba(74,222,128,0.6);">{{ __('app.invoice_label') }}</p>
                @if($booking->invoice_number)
                <p class="mono text-xs" style="color:#64748b;">{{ $booking->invoice_number }}</p>
                @endif
            </div>
            <p class="heading font-bold text-lg" style="color:#4ade80;">
                LKR {{ number_format($booking->invoice_amount) }}
            </p>
            @if($booking->invoice_date)
            <p class="text-xs mt-0.5" style="color:#64748b;">
                {{ __('app.invoice_date_label') }}: {{ \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y') }}
            </p>
            @endif
            @if($booking->invoice_notes)
            <p class="text-xs mt-1" style="color:#64748b;">{{ $booking->invoice_notes }}</p>
            @endif
        </div>
        <a href="{{ route('bookings.invoice.show', $booking) }}"
           class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mb-3"
           style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);color:#4ade80;">
            <x-heroicon-o-document-text class="w-3.5 h-3.5" />
            {{ __('app.view_invoice_btn') }}
        </a>
        @endif

        {{-- View Details --}}
        <a href="{{ route('bookings.show', $booking) }}"
           class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mb-3"
           style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.15);color:rgba(0,245,255,0.7);">
            <x-heroicon-o-eye class="w-3.5 h-3.5" />
            {{ __('app.view_details') }}
        </a>

        {{-- Cancel booking — only for pending or confirmed --}}
        @if(in_array($booking->status, ['pending', 'confirmed']))
        <div class="mt-3 rounded-xl p-3 border" style="background:rgba(248,113,113,0.04);border-color:rgba(248,113,113,0.15);">
            <p class="text-xs font-semibold mb-2" style="color:rgba(248,113,113,0.7);">{{ __('app.cancel_booking_label') }}</p>
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                @csrf @method('PATCH')
                <textarea name="cancel_reason" rows="2"
                          placeholder="{{ __('app.cancel_reason_ph') }}"
                          class="w-full px-3 py-2 rounded-xl text-xs text-white placeholder-slate-600 outline-none resize-none mb-2"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.15);"></textarea>
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                        style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);color:#f87171;"
                        onclick="return confirm('{{ __('app.cancel_confirm_msg') }}')">
                    <x-heroicon-o-x-circle class="w-3.5 h-3.5 inline-block mr-1 align-middle" />
                    {{ __('app.cancel_booking_btn') }}
                </button>
            </form>
        </div>
        @endif

        {{-- Show cancel reason if cancelled --}}
        @if($booking->status === 'cancelled' && $booking->cancel_reason)
        <div class="rounded-xl p-3 mt-2 border"
             style="background:rgba(248,113,113,0.04);border-color:rgba(248,113,113,0.15);">
            <div class="flex items-center gap-1.5 mb-1">
                <x-heroicon-o-x-circle class="w-3.5 h-3.5 flex-shrink-0" style="color:rgba(248,113,113,0.5);" />
                <p class="text-xs" style="color:rgba(248,113,113,0.5);">{{ __('app.cancel_reason_label') }}</p>
            </div>
            <p class="text-sm leading-relaxed" style="color:#94a3b8;">{{ $booking->cancel_reason }}</p>
        </div>
        @endif
    </div>
        @empty
            <div class="rounded-2xl p-8 text-center border mb-4" style="background:rgba(0,245,255,0.03);border-color:rgba(0,245,255,0.1);">
                <x-heroicon-o-calendar-days class="w-10 h-10 mx-auto mb-3" style="color:#334155;" />
                <p class="heading font-bold text-white mb-1">{{ __('app.no_upcoming_bookings') }}</p>
                <a href="{{ route('garages.index') }}"
                   class="mt-3 inline-block px-5 py-2 rounded-xl text-xs font-semibold heading tracking-wider"
                   style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                    {{ __('app.book_appt_btn') }}
                </a>
            </div>
        @endforelse
    </div>{{-- /section-upcoming --}}

    {{-- PAST --}}
    <div id="section-past" style="display:none;">
        <p class="section-label mb-3">{{ __('app.past_bookings_label') }}</p>
        @forelse($past as $index => $booking)
            @php
                $statusColor = match($booking->status) {
                    'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
                    'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
                    default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
                };
            @endphp
            {{-- Reuse same card structure --}}
            <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in" style="border-color:rgba(255,255,255,0.08);">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="heading font-bold text-white text-base">{{ $booking->service_type }}</h3>
                        <p class="text-xs mt-0.5" style="color:#64748b;">{{ $booking->garage->name ?? 'N/A' }} · {{ $booking->garage->city ?? '' }}</p>
                    </div>
                    <span class="tag" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};border:1px solid {{ $statusColor['border'] }};">
                        {{ strtoupper($booking->status) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.date_label') }}</p>
                        <p class="mono text-sm font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                    </div>
                    <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.vehicle_label') }}</p>
                        <p class="text-sm font-semibold text-white">{{ $booking->vehicle->make }} {{ $booking->vehicle->model }}</p>
                    </div>
                </div>
                {{-- View Details (past) --}}
                <a href="{{ route('bookings.show', $booking) }}"
                   class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mb-2"
                   style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.15);color:rgba(0,245,255,0.7);">
                    <x-heroicon-o-eye class="w-3.5 h-3.5" />
                    {{ __('app.view_details') }}
                </a>
                @if($booking->status === 'completed' && $booking->invoice_amount)
                <a href="{{ route('bookings.invoice.show', $booking) }}"
                   class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mb-2"
                   style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);color:#4ade80;">
                    <x-heroicon-o-document-text class="w-3.5 h-3.5" />
                    {{ __('app.view_invoice_btn') }}
                </a>
                @endif
                @if($booking->status === 'completed')
                    @if(!$booking->rating)
                    <div class="rounded-xl p-3 border" style="background:rgba(251,191,36,0.04);border-color:rgba(251,191,36,0.15);">
                        <p class="text-xs font-semibold mb-2" style="color:rgba(251,191,36,0.7);">{{ __('app.rate_service_label') }}</p>
                        <form method="POST" action="{{ route('ratings.store') }}">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <div class="flex gap-1 mb-2" id="pstars-{{ $booking->id }}">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                    <span class="star-btn text-2xl transition-all select-none" style="color:#334155;"
                                          onmouseover="hoverStars('pstars-{{ $booking->id }}', {{ $i }})"
                                          onmouseout="resetStars('pstars-{{ $booking->id }}')"
                                          onclick="selectStars('pstars-{{ $booking->id }}', {{ $i }})">★</span>
                                </label>
                                @endfor
                            </div>
                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider active:scale-95 border"
                                    style="background:rgba(251,191,36,0.08);border-color:rgba(251,191,36,0.2);color:#fbbf24;">
                                {{ __('app.submit_rating_btn') }}
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="rounded-xl p-3 border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <span style="font-size:18px;color:{{ $i <= $booking->rating->rating ? '#fbbf24' : '#334155' }};">★</span>
                            @endfor
                        </div>
                        @if($booking->rating->review)
                        <p class="text-xs mt-1" style="color:#64748b;">{{ $booking->rating->review }}</p>
                        @endif
                    </div>
                    @endif
                @endif
                @if($booking->status === 'cancelled' && $booking->cancel_reason)
                <div class="rounded-xl p-3 border" style="background:rgba(248,113,113,0.04);border-color:rgba(248,113,113,0.15);">
                    <p class="text-xs mb-1" style="color:rgba(248,113,113,0.5);">{{ __('app.cancel_reason_label') }}</p>
                    <p class="text-sm" style="color:#94a3b8;">{{ $booking->cancel_reason }}</p>
                </div>
                @endif
            </div>
        @empty
            <div class="rounded-2xl p-8 text-center border" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
                <x-heroicon-o-clock class="w-10 h-10 mx-auto mb-3" style="color:#334155;" />
                <p class="heading font-bold text-white">{{ __('app.past_bookings_label') }}</p>
                <p class="text-sm mt-1" style="color:#64748b;">{{ __('app.no_past_bookings') }}</p>
            </div>
        @endforelse
    </div>{{-- /section-past --}}

</div>

<script>
function switchTab(tab) {
    var isUpcoming = tab === 'upcoming';
    document.getElementById('section-upcoming').style.display = isUpcoming ? '' : 'none';
    document.getElementById('section-past').style.display     = isUpcoming ? 'none' : '';
    var up = document.getElementById('tab-upcoming');
    var pa = document.getElementById('tab-past');
    up.style.background  = isUpcoming ? 'rgba(0,245,255,0.12)' : 'rgba(255,255,255,0.05)';
    up.style.borderColor = isUpcoming ? 'rgba(0,245,255,0.3)'  : 'rgba(255,255,255,0.1)';
    up.style.color       = isUpcoming ? '#00f5ff' : '#64748b';
    pa.style.background  = isUpcoming ? 'rgba(255,255,255,0.05)' : 'rgba(0,245,255,0.12)';
    pa.style.borderColor = isUpcoming ? 'rgba(255,255,255,0.1)'  : 'rgba(0,245,255,0.3)';
    pa.style.color       = isUpcoming ? '#64748b' : '#00f5ff';
}

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