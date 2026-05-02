<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.invoice_history_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.invoice_history_title') }}
            </h1>
        </div>
        <a href="{{ route('garage.dashboard') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border mt-1"
           style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);color:#00f5ff;">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('app.back_to_dashboard') }}
        </a>
    </div>

    {{-- Summary card --}}
    <div class="rounded-2xl p-4 mb-5 fade-in fade-in-2 border"
         style="background:linear-gradient(135deg,rgba(74,222,128,0.08),rgba(0,245,255,0.04));border-color:rgba(74,222,128,0.2);">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs mb-1" style="color:rgba(74,222,128,0.6);">{{ __('app.total_revenue_label') }}</p>
                <p class="heading text-2xl font-bold" style="color:#4ade80;">
                    LKR {{ number_format($totalRevenue) }}
                </p>
            </div>
            <div>
                <p class="text-xs mb-1" style="color:rgba(0,245,255,0.5);">{{ __('app.total_invoices_label') }}</p>
                <p class="heading text-2xl font-bold text-cyan">
                    {{ $invoices->count() }}
                </p>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $invoices->count() === 1 ? __('app.invoice_singular') : __('app.invoices_plural') }}
                </p>
            </div>
        </div>
    </div>

    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.all_invoices_label') }}</p>

    @forelse($invoices as $index => $booking)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(74,222,128,0.12);">

        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1 min-w-0 pr-2">
                <h3 class="heading font-bold text-white text-sm leading-tight">
                    {{ $booking->service_type }}
                </h3>
                <p class="text-xs mt-0.5 font-semibold" style="color:#94a3b8;">
                    {{ $booking->vehicle->user->name }}
                </p>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->vehicle->make }} {{ $booking->vehicle->model }}
                    · {{ $booking->vehicle->license_plate }}
                </p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="heading font-bold text-base" style="color:#4ade80;">
                    LKR {{ number_format($booking->invoice_amount) }}
                </p>
                @if($booking->invoice_number)
                <p class="mono text-xs mt-0.5" style="color:#64748b;">
                    {{ $booking->invoice_number }}
                </p>
                @endif
            </div>
        </div>

        {{-- Date & Invoice date --}}
        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.date_label') }}</p>
                <p class="mono text-xs font-bold text-white">
                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                </p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.invoice_date_label') }}</p>
                <p class="mono text-xs font-bold text-white">
                    {{ $booking->invoice_date
                        ? \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y')
                        : '—' }}
                </p>
            </div>
        </div>

        @if($booking->invoice_notes)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#64748b;">{{ $booking->invoice_notes }}</p>
        </div>
        @endif

        {{-- View invoice button --}}
        <a href="{{ route('bookings.invoice.show', $booking) }}"
           class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);color:#4ade80;">
            <x-heroicon-o-document-text class="w-3.5 h-3.5" />
            {{ __('app.view_invoice_btn') }}
        </a>

    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-document-text class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_invoices_yet') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.invoices_appear_hint') }}</p>
        </div>
    @endforelse

</div>
</x-app-layout>
