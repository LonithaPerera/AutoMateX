<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.system_control_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.admin_all_bookings_title') }}
            </h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.admin_all_bookings_desc') }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1 flex-shrink-0"
           style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.15);color:#64748b;">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('app.admin_back_dashboard') }}
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-2 mb-2 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-xl font-bold text-cyan">{{ $stats['total'] }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.total') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(102,153,255,0.05);border-color:rgba(102,153,255,0.15);">
            <p class="heading text-xl font-bold" style="color:#6699ff;">{{ $stats['confirmed'] }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.confirmed') }}</p>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-2 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
            <p class="heading text-xl font-bold" style="color:#fbbf24;">{{ $stats['pending'] }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.pending') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-xl font-bold" style="color:#4ade80;">{{ $stats['completed'] }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.completed') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(248,113,113,0.05);border-color:rgba(248,113,113,0.15);">
            <p class="heading text-xl font-bold" style="color:#f87171;">{{ $stats['cancelled'] }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.cancelled') }}</p>
        </div>
    </div>

    {{-- Search & Status filter --}}
    <div class="mb-4 fade-in fade-in-2 space-y-2">
        <input type="text" id="booking-search"
               placeholder="{{ __('app.admin_search_ph') }}"
               oninput="filterBookings()"
               class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
        <div class="flex gap-2">
            @foreach(['all','pending','confirmed','completed','cancelled'] as $st)
            <button onclick="setStatusFilter('{{ $st }}')" id="bfilter-{{ $st }}"
                    class="flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                    style="background:{{ $st === 'all' ? 'rgba(168,85,247,0.12)' : 'rgba(255,255,255,0.04)' }};border:1px solid {{ $st === 'all' ? 'rgba(168,85,247,0.3)' : 'rgba(255,255,255,0.08)' }};color:{{ $st === 'all' ? '#a855f7' : '#64748b' }};">
                {{ strtoupper($st === 'all' ? __('app.admin_filter_all') : $st) }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Bookings list --}}
    @forelse($bookings as $index => $booking)
    @php
        $sc = match($booking->status) {
            'pending'   => ['bg'=>'rgba(251,191,36,0.08)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
            'confirmed' => ['bg'=>'rgba(0,245,255,0.08)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            'completed' => ['bg'=>'rgba(74,222,128,0.08)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
            'cancelled' => ['bg'=>'rgba(248,113,113,0.08)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
            default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }} booking-row"
         data-status="{{ $booking->status }}"
         data-search="{{ strtolower($booking->service_type . ' ' . ($booking->vehicle->user->name ?? '') . ' ' . ($booking->garage->name ?? '')) }}"
         style="border-color:rgba(168,85,247,0.1);">

        <div class="flex items-start justify-between mb-2">
            <div class="flex-1 min-w-0">
                <h3 class="heading font-bold text-white text-sm leading-tight">{{ $booking->service_type }}</h3>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->vehicle->user->name ?? '—' }} → {{ $booking->garage->name ?? '—' }}
                </p>
            </div>
            <span class="tag flex-shrink-0 ml-2" style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border:1px solid {{ $sc['border'] }};">
                {{ strtoupper($booking->status) }}
            </span>
        </div>

        <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="rounded-xl p-2" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.date_label') }}</p>
                <p class="mono text-xs font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                @if($booking->booking_time)
                <p class="mono text-xs" style="color:#64748b;">{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</p>
                @endif
            </div>
            <div class="rounded-xl p-2" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.vehicle_label') }}</p>
                <p class="mono text-xs font-bold text-white">{{ $booking->vehicle->make ?? '—' }} {{ $booking->vehicle->model ?? '' }}</p>
            </div>
            <div class="rounded-xl p-2" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.invoice_label') }}</p>
                <p class="mono text-xs font-bold" style="color:#4ade80;">
                    {{ $booking->invoice_amount ? 'LKR ' . number_format($booking->invoice_amount) : '—' }}
                </p>
            </div>
        </div>

        {{-- Status action buttons (only for non-terminal states) --}}
        @if($booking->status === 'pending')
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" data-no-warn style="flex:1;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit"
                        class="w-full py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.25);color:#00f5ff;">
                    <x-heroicon-o-check class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.admin_confirm_btn') }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" data-no-warn style="flex:1;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit"
                        class="w-full py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25);color:#f87171;">
                    <x-heroicon-o-x-mark class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.cancelled') }}
                </button>
            </form>
        </div>
        @elseif($booking->status === 'confirmed')
        <div class="flex gap-2">
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" data-no-warn style="flex:1;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit"
                        class="w-full py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                    <x-heroicon-o-check-circle class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.admin_complete_btn') }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" data-no-warn style="flex:1;">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit"
                        class="w-full py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.25);color:#f87171;">
                    <x-heroicon-o-x-mark class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.cancelled') }}
                </button>
            </form>
        </div>
        @endif

    </div>
    @empty
    <div class="rounded-2xl p-10 text-center border fade-in fade-in-3" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
        <x-heroicon-o-calendar-days class="w-10 h-10 mx-auto mb-3" style="color:#334155;" />
        <p class="heading font-bold text-white">{{ __('app.no_bookings_admin') }}</p>
    </div>
    @endforelse

</div>

<script>
var activeStatus = 'all';

function filterBookings() {
    var search = document.getElementById('booking-search').value.toLowerCase();
    document.querySelectorAll('.booking-row').forEach(function (row) {
        var s      = row.dataset.search || '';
        var status = row.dataset.status || '';
        var matchSearch = s.includes(search);
        var matchStatus = activeStatus === 'all' || status === activeStatus;
        row.style.display = (matchSearch && matchStatus) ? '' : 'none';
    });
}

function setStatusFilter(st) {
    activeStatus = st;
    ['all','pending','confirmed','completed','cancelled'].forEach(function (s) {
        var btn = document.getElementById('bfilter-' + s);
        var active = s === st;
        btn.style.background  = active ? 'rgba(168,85,247,0.12)' : 'rgba(255,255,255,0.04)';
        btn.style.borderColor = active ? 'rgba(168,85,247,0.3)'  : 'rgba(255,255,255,0.08)';
        btn.style.color       = active ? '#a855f7' : '#64748b';
    });
    filterBookings();
}
</script>
</x-app-layout>
