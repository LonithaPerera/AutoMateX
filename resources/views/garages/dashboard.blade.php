<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        {{-- Garage banner photo --}}
        @if($garage->photo)
        <div class="rounded-2xl overflow-hidden mb-3 border" style="border-color:rgba(0,245,255,0.12);">
            <img src="{{ asset('storage/' . $garage->photo) }}"
                 alt="{{ $garage->name }}"
                 class="w-full object-cover" style="max-height:160px;">
        </div>
        @endif
        <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0 pr-2">
                <p class="section-label mb-1">{{ __('app.garage_dash_label') }}</p>
                <h1 class="heading text-3xl font-bold text-white">{{ $garage->name }}</h1>
                <p class="text-xs mt-0.5" style="color:#64748b;">
                    <x-heroicon-o-map-pin class="w-3 h-3 inline-block mr-0.5 align-middle" /> {{ $garage->city }}
                    @if($garage->phone) · {{ $garage->phone }} @endif
                </p>
                {{-- Profile completeness bar --}}
                <div class="mt-2 flex items-center gap-2">
                    <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                        <div class="h-full rounded-full"
                             style="width:{{ $profileScore }}%;
                                    background:{{ $profileScore >= 100 ? '#4ade80' : ($profileScore >= 60 ? '#00f5ff' : '#fbbf24') }};
                                    transition:width 0.6s ease;"></div>
                    </div>
                    <span class="text-xs mono flex-shrink-0" style="color:#64748b;">
                        {{ __('app.profile_complete_label') }} {{ $profileScore }}%
                    </span>
                </div>
                {{-- [7] Average rating chip --}}
                @if($totalRatings > 0)
                <div class="mt-2 flex items-center gap-1.5">
                    <div class="flex items-center gap-1 px-2 py-1 rounded-lg"
                         style="background:rgba(251,191,36,0.1);border:1px solid rgba(251,191,36,0.2);">
                        <span style="color:#fbbf24;font-size:13px;">★</span>
                        <span class="mono text-xs font-bold" style="color:#fbbf24;">{{ $avgRating }}</span>
                        <span class="text-xs" style="color:#64748b;">({{ $totalRatings }})</span>
                    </div>
                    <span class="text-xs" style="color:#64748b;">{{ __('app.avg_rating_label') }}</span>
                </div>
                @else
                <div class="mt-2">
                    <span class="text-xs" style="color:#475569;">{{ __('app.no_ratings_yet') }}</span>
                </div>
                @endif
            </div>
            <div class="flex flex-col gap-2 mt-1">
                <a href="{{ route('garage.invoices') }}"
                   class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                   style="background:rgba(74,222,128,0.08);border-color:rgba(74,222,128,0.25);color:#4ade80;">
                    <x-heroicon-o-document-text class="w-3.5 h-3.5" />
                    {{ __('app.all_invoices_btn') }}
                </a>
                <a href="{{ route('garages.edit') }}"
                   class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                   style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);color:#00f5ff;">
                    <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
                    {{ __('app.edit_profile_btn') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- [3] Today's Schedule --}}
    @if($todayBookings->isNotEmpty())
    <div id="today-schedule-section" class="rounded-2xl p-4 mb-3 fade-in fade-in-1 border"
         style="background:linear-gradient(135deg,rgba(251,191,36,0.07),rgba(255,255,255,0.02));border-color:rgba(251,191,36,0.25);">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-7 h-7 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(251,191,36,0.12);border:1px solid rgba(251,191,36,0.2);">
                <x-heroicon-o-calendar-days class="w-3.5 h-3.5" style="color:#fbbf24;" />
            </div>
            <p class="section-label" style="color:rgba(251,191,36,0.7);">{{ __('app.today_schedule_label') }}</p>
            <span class="ml-auto mono text-xs px-2 py-0.5 rounded-lg"
                  style="background:rgba(251,191,36,0.1);color:#fbbf24;border:1px solid rgba(251,191,36,0.2);">
                {{ $todayBookings->count() }}
            </span>
            {{-- [3] Print button --}}
            <button onclick="window.print()"
                    class="no-print flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                    style="background:rgba(251,191,36,0.08);border-color:rgba(251,191,36,0.2);color:#fbbf24;">
                <x-heroicon-o-printer class="w-3 h-3" />
                {{ __('app.print_schedule_btn') }}
            </button>
        </div>
        <div class="space-y-2">
            @foreach($todayBookings as $appt)
            @php
                $apptColor = $appt->status === 'confirmed' ? '#00f5ff' : '#fbbf24';
                $apptBg    = $appt->status === 'confirmed' ? 'rgba(0,245,255,0.06)' : 'rgba(251,191,36,0.06)';
                $apptBdr   = $appt->status === 'confirmed' ? 'rgba(0,245,255,0.15)' : 'rgba(251,191,36,0.15)';
            @endphp
            <div class="flex items-center gap-3 rounded-xl px-3 py-2.5 border"
                 style="background:{{ $apptBg }};border-color:{{ $apptBdr }};">
                <span class="mono text-sm font-bold flex-shrink-0" style="color:{{ $apptColor }};">
                    {{ \Carbon\Carbon::parse($appt->booking_time)->format('h:i A') }}
                </span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $appt->service_type }}</p>
                    <p class="text-xs truncate" style="color:#64748b;">
                        {{ $appt->vehicle->user->name }} · {{ $appt->vehicle->license_plate }}
                    </p>
                </div>
                <span class="text-xs font-semibold heading tracking-wider px-2 py-0.5 rounded-lg flex-shrink-0"
                      style="background:{{ $apptBg }};color:{{ $apptColor }};border:1px solid {{ $apptBdr }};">
                    {{ strtoupper($appt->status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Stats --}}
    @php
        $pending   = $bookings->where('status','pending')->count();
        $confirmed = $bookings->where('status','confirmed')->count();
        $completed = $bookings->where('status','completed')->count();
        $cancelled = $bookings->where('status','cancelled')->count();
    @endphp
    <div class="grid grid-cols-2 gap-3 mb-3 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(251,191,36,0.05);border-color:rgba(251,191,36,0.15);">
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
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(248,113,113,0.05);border-color:rgba(248,113,113,0.15);">
            <p class="heading text-2xl font-bold" style="color:#f87171;">{{ $cancelled }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.cancelled') }}</p>
        </div>
    </div>

    {{-- Revenue summary --}}
    <div class="rounded-2xl p-4 mb-3 fade-in fade-in-2 border"
         style="background:linear-gradient(135deg,rgba(74,222,128,0.08),rgba(0,245,255,0.04));border-color:rgba(74,222,128,0.2);">

        <div class="flex items-center gap-2 mb-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                 style="background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.2);">
                <x-heroicon-o-banknotes class="w-4 h-4" style="color:#4ade80;" />
            </div>
            <p class="section-label">{{ __('app.revenue_summary_label') }}</p>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="rounded-xl p-3 border" style="background:rgba(74,222,128,0.06);border-color:rgba(74,222,128,0.15);">
                <p class="text-xs mb-1" style="color:rgba(74,222,128,0.6);">{{ __('app.this_month_label') }}</p>
                <p class="heading text-xl font-bold" style="color:#4ade80;">LKR {{ number_format($revenueThisMonth) }}</p>
                <p class="text-xs mt-1" style="color:#64748b;">
                    {{ $completedJobsThisMonth }} {{ $completedJobsThisMonth === 1 ? __('app.job_completed_singular') : __('app.jobs_completed_plural') }}
                </p>
            </div>
            <div class="rounded-xl p-3 border" style="background:rgba(0,245,255,0.04);border-color:rgba(0,245,255,0.12);">
                <p class="text-xs mb-1" style="color:rgba(0,245,255,0.5);">{{ __('app.all_time_label') }}</p>
                <p class="heading text-xl font-bold text-cyan">LKR {{ number_format($revenue) }}</p>
                <p class="text-xs mt-1" style="color:#64748b;">
                    {{ $completedJobsTotal }} {{ $completedJobsTotal === 1 ? __('app.job_completed_singular') : __('app.jobs_completed_plural') }}
                </p>
            </div>
        </div>

        {{-- [5] Monthly Revenue Target --}}
        @php
            $target         = $garage->monthly_target;
            $targetProgress = $target > 0 ? min(100, (int) round($revenueThisMonth / $target * 100)) : 0;
        @endphp
        <div class="rounded-xl p-3 mb-3 border" style="background:rgba(255,107,0,0.04);border-color:rgba(255,107,0,0.15);">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold" style="color:rgba(255,107,0,0.7);">{{ __('app.revenue_target_label') }}</p>
                @if($target)
                    @if($targetProgress >= 100)
                    <span class="mono text-xs px-2 py-0.5 rounded-lg font-bold"
                          style="background:rgba(74,222,128,0.12);color:#4ade80;border:1px solid rgba(74,222,128,0.25);">
                        ✓ {{ __('app.target_achieved') }}
                    </span>
                    @else
                    <span class="mono text-xs" style="color:#64748b;">{{ $targetProgress }}% {{ __('app.on_track_label') }}</span>
                    @endif
                @endif
            </div>
            @if($target)
            <div class="mb-2">
                <div class="w-full h-2 rounded-full overflow-hidden mb-1" style="background:rgba(255,255,255,0.06);">
                    <div class="h-full rounded-full transition-all"
                         style="width:{{ $targetProgress }}%;
                                background:{{ $targetProgress >= 100 ? '#4ade80' : ($targetProgress >= 60 ? '#00f5ff' : '#ff6b00') }};"></div>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs" style="color:#64748b;">LKR {{ number_format($revenueThisMonth) }}</span>
                    <span class="text-xs" style="color:#64748b;">/ LKR {{ number_format($target) }}</span>
                </div>
            </div>
            @endif
            <form method="POST" action="{{ route('garage.updateTarget') }}" class="flex gap-2">
                @csrf @method('PATCH')
                <input type="number" name="monthly_target" min="0" max="99999999"
                       value="{{ $target }}"
                       placeholder="{{ __('app.set_target_ph') }}"
                       class="flex-1 px-3 py-1.5 rounded-xl text-xs text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,107,0,0.2);">
                <button type="submit"
                        class="px-3 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border flex-shrink-0"
                        style="background:rgba(255,107,0,0.08);border-color:rgba(255,107,0,0.25);color:#ff6b00;">
                    {{ __('app.save_target_btn') }}
                </button>
            </form>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="rounded-xl p-3 border" style="background:rgba(255,107,0,0.05);border-color:rgba(255,107,0,0.15);">
                <p class="text-xs mb-1" style="color:rgba(255,107,0,0.6);">{{ __('app.avg_invoice_label') }}</p>
                <p class="heading text-lg font-bold" style="color:#ff6b00;">LKR {{ number_format($avgInvoice) }}</p>
                <p class="text-xs mt-1" style="color:#64748b;">per completed job</p>
            </div>
            <div class="rounded-xl p-3 border" style="background:rgba(168,85,247,0.05);border-color:rgba(168,85,247,0.15);">
                <p class="text-xs mb-1" style="color:rgba(168,85,247,0.6);">{{ __('app.most_booked_label') }}</p>
                @if($mostBookedService)
                <p class="heading text-sm font-bold leading-tight" style="color:#c084fc;">
                    {{ Str::limit($mostBookedService->service_type, 22) }}
                </p>
                <p class="text-xs mt-1" style="color:#64748b;">{{ $mostBookedService->cnt }}× booked</p>
                @else
                <p class="text-sm" style="color:#64748b;">—</p>
                @endif
            </div>
        </div>

        {{-- 6-month revenue chart --}}
        <p class="text-xs mb-2" style="color:#64748b;">{{ __('app.last_6_months_label') }}</p>
        <div style="position:relative;height:120px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Bookings by Status chart --}}
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-2" style="border-color:rgba(168,85,247,0.15);">
        <p class="heading text-xs font-bold text-white tracking-wider mb-3">{{ __('app.admin_chart_status_title') }}</p>
        <div style="position:relative;height:200px;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    {{-- Top Services chart --}}
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-2" style="border-color:rgba(0,245,255,0.15);">
        <p class="heading text-xs font-bold text-white tracking-wider mb-3">{{ __('app.garage_chart_services_title') }}</p>
        <div style="position:relative;height:{{ max(150, $topServices->count() * 50) }}px;">
            <canvas id="topServicesChart"></canvas>
        </div>
    </div>

    {{-- Bookings link --}}
    <a href="{{ route('garage.bookings') }}"
       class="flex items-center justify-between w-full px-4 py-3 rounded-2xl border mb-3 fade-in fade-in-3 transition-all active:scale-95"
       style="background:rgba(0,245,255,0.04);border-color:rgba(0,245,255,0.15);">
        <div class="flex items-center gap-2">
            <x-heroicon-o-calendar-days class="w-4 h-4" style="color:var(--cyan);" />
            <span class="text-sm font-semibold heading tracking-wider text-white">{{ __('app.garage_bookings_title') }}</span>
        </div>
        <div class="flex items-center gap-2">
            @if($bookings->where('status','pending')->count() > 0)
            <span class="mono text-xs px-2 py-0.5 rounded-lg font-bold"
                  style="background:rgba(251,191,36,0.12);color:#fbbf24;border:1px solid rgba(251,191,36,0.25);">
                {{ $bookings->where('status','pending')->count() }} pending
            </span>
            @endif
            <x-heroicon-o-arrow-right class="w-4 h-4" style="color:#475569;" />
        </div>
    </a>


</div>

{{-- Print CSS --}}
<style>
@media print {
    .no-print, .bottom-nav, header, .orb-1, .orb-2 { display: none !important; }
    body, body::before { background: white !important; }
    #today-schedule-section { background: white !important; border: 1px solid #ddd !important; color: black !important; }
    #today-schedule-section * { color: black !important; background: transparent !important; border-color: #ccc !important; }
    .booking-card, #empty-upcoming, .section-header { display: none !important; }
    .page-wrapper { padding-bottom: 0 !important; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Revenue chart ──────────────────────────────────────────────────────────
const chartLabels  = @json($chartData->pluck('month'));
const chartRevenue = @json($chartData->pluck('revenue'));
const chartJobs    = @json($chartData->pluck('jobs'));

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: chartLabels,
        datasets: [{
            data: chartRevenue,
            backgroundColor: chartRevenue.map((v, i) => {
                if (v > 0)  return 'rgba(74,222,128,0.5)';
                if (i < 5)  return 'rgba(248,113,113,0.18)';
                return 'rgba(255,255,255,0.05)';
            }),
            borderColor: chartRevenue.map((v, i) => {
                if (v > 0)  return 'rgba(74,222,128,0.9)';
                if (i < 5)  return 'rgba(248,113,113,0.5)';
                return 'rgba(255,255,255,0.1)';
            }),
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' LKR ' + ctx.parsed.y.toLocaleString(),
                    afterLabel: ctx => {
                        const jobs = chartJobs[ctx.dataIndex];
                        return jobs > 0 ? ` ${jobs} job${jobs === 1 ? '' : 's'} completed` : ' no completed jobs';
                    }
                },
                backgroundColor: 'rgba(13,20,33,0.95)', borderColor: 'rgba(74,222,128,0.3)',
                borderWidth: 1, titleColor: '#94a3b8', bodyColor: '#4ade80', padding: 8,
            }
        },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 10, family: "'Share Tech Mono'" } }, border: { color: 'rgba(255,255,255,0.06)' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#64748b', font: { size: 9, family: "'Share Tech Mono'" }, callback: v => v >= 1000 ? (v/1000).toFixed(0)+'k' : v, maxTicksLimit: 4 }, border: { color: 'rgba(255,255,255,0.06)' }, beginAtZero: true }
        }
    }
});

// ── Bookings by Status (Donut) ─────────────────────────────────────────────
var garageStatusData = @json($bookingsByStatus);
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
        datasets: [{
            data: [garageStatusData.pending, garageStatusData.confirmed, garageStatusData.completed, garageStatusData.cancelled],
            backgroundColor: ['rgba(251,191,36,0.8)', 'rgba(102,153,255,0.8)', 'rgba(74,222,128,0.8)', 'rgba(248,113,113,0.8)'],
            borderColor:     ['rgba(251,191,36,0.3)', 'rgba(102,153,255,0.3)', 'rgba(74,222,128,0.3)', 'rgba(248,113,113,0.3)'],
            borderWidth: 1,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 14, boxWidth: 10, boxHeight: 10, borderRadius: 3, useBorderRadius: true, color: '#64748b' }
            }
        }
    }
});

// ── Top Services (Horizontal Bar) ──────────────────────────────────────────
var topServicesData = @json($topServices->map(fn($s) => ['name' => $s->service_type, 'count' => $s->cnt]));
new Chart(document.getElementById('topServicesChart'), {
    type: 'bar',
    data: {
        labels: topServicesData.map(function(d){ return d.name; }),
        datasets: [{
            label: 'Bookings',
            data:  topServicesData.map(function(d){ return d.count; }),
            backgroundColor: 'rgba(0,245,255,0.2)',
            borderColor:     'rgba(0,245,255,0.7)',
            borderWidth: 1,
            borderRadius: 4,
            borderSkipped: 'start',
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        layout: { padding: { right: 8 } },
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { label: ctx => '  ' + ctx.parsed.x + ' booking' + (ctx.parsed.x === 1 ? '' : 's') },
                backgroundColor: 'rgba(13,20,33,0.95)',
                borderColor: 'rgba(0,245,255,0.3)',
                borderWidth: 1,
                titleColor: '#94a3b8',
                bodyColor: '#00f5ff',
                padding: 8,
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                border: { color: 'rgba(255,255,255,0.06)' },
                ticks: { color: '#64748b', precision: 0, stepSize: 1, font: { size: 10, family: "'Share Tech Mono'" } },
                beginAtZero: true,
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                border: { color: 'rgba(255,255,255,0.06)' },
                ticks: {
                    color: '#94a3b8',
                    font: { size: 10 },
                    crossAlign: 'far',
                    callback: function(val) {
                        var label = this.getLabelForValue(val);
                        return label.length > 20 ? label.substring(0, 20) + '…' : label;
                    }
                }
            }
        }
    }
});

// Filter/search JS lives on garages/bookings.blade.php
</script>
</x-app-layout>
