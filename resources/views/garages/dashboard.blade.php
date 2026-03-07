<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// GARAGE DASHBOARD</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ $garage->name }}
        </h1>
        <p class="text-xs mt-1" style="color:#64748b;">
            📍 {{ $garage->city }}
            @if($garage->phone) · {{ $garage->phone }} @endif
        </p>
    </div>

    {{-- Stats --}}
    @php
        $pending   = $bookings->where('status','pending')->count();
        $confirmed = $bookings->where('status','confirmed')->count();
        $completed = $bookings->where('status','completed')->count();
        $cancelled = $bookings->where('status','cancelled')->count();
    @endphp
    <div class="grid grid-cols-2 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
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
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(248,113,113,0.05);border-color:rgba(248,113,113,0.15);">
            <p class="heading text-2xl font-bold" style="color:#f87171;">{{ $cancelled }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">Cancelled</p>
        </div>
    </div>

    {{-- Bookings list --}}
    <p class="section-label mb-3 fade-in fade-in-2">// INCOMING BOOKINGS</p>

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
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        {{-- Top --}}
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="heading font-bold text-white text-base">{{ $booking->service_type }}</h3>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                    · {{ $booking->vehicle->license_plate }}
                </p>
            </div>
            <span class="tag" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};border:1px solid {{ $statusColor['border'] }};">
                {{ strtoupper($booking->status) }}
            </span>
        </div>

        {{-- Date & Time --}}
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

        @if($booking->notes)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#64748b;">{{ $booking->notes }}</p>
        </div>
        @endif

        {{-- Status update form --}}
        <div class="mb-3">
            <p class="section-label mb-2">// update status</p>
            <form method="POST" action="{{ route('bookings.update', $booking) }}">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-2 mb-2">
                    @foreach(['pending','confirmed','completed','cancelled'] as $status)
                    <button type="submit" name="status" value="{{ $status }}"
                            class="py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                            style="background:{{ $booking->status === $status ? $statusColor['bg'] : 'rgba(255,255,255,0.03)' }};
                                   color:{{ $booking->status === $status ? $statusColor['color'] : '#64748b' }};
                                   border-color:{{ $booking->status === $status ? $statusColor['border'] : 'rgba(255,255,255,0.08)' }};">
                        {{ strtoupper($status) }}
                    </button>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Invoice form (only for completed) --}}
        @if($booking->status === 'completed')
        <div style="border-top:1px solid rgba(74,222,128,0.15);padding-top:12px;">
            <p class="section-label mb-2">// generate invoice</p>
            <form method="POST" action="{{ route('bookings.invoice', $booking) }}">
                @csrf @method('PATCH')
                <div class="mb-2">
                    <input type="number" name="invoice_amount"
                           value="{{ $booking->invoice_amount }}"
                           placeholder="Invoice amount (LKR)" min="0" step="0.01"
                           class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(74,222,128,0.2);">
                </div>
                <div class="mb-2">
                    <textarea name="invoice_notes" rows="2"
                              placeholder="Invoice notes..."
                              class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                              style="background:rgba(255,255,255,0.04);border:1px solid rgba(74,222,128,0.2);">{{ $booking->invoice_notes }}</textarea>
                </div>
                <button type="submit"
                        class="w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                    💾 SAVE INVOICE
                </button>
            </form>
        </div>
        @endif

    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <div class="text-5xl mb-4">📭</div>
            <p class="heading text-xl font-bold text-white mb-1">No Bookings Yet</p>
            <p class="text-sm" style="color:#64748b;">Bookings will appear here when customers book your garage</p>
        </div>
    @endforelse

</div>
</x-app-layout>