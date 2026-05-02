<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
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

    {{-- [6] Calendar toggle --}}
    <div class="mb-3 fade-in fade-in-2">
        <button onclick="toggleCalendar()" id="calendarToggleBtn"
                class="no-print w-full flex items-center justify-between px-4 py-3 rounded-2xl border transition-all"
                style="background:rgba(0,102,255,0.05);border-color:rgba(0,102,255,0.15);">
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar class="w-4 h-4" style="color:#60a5fa;" />
                <span class="text-sm font-semibold heading tracking-wider" style="color:#60a5fa;">{{ __('app.calendar_label') }}</span>
            </div>
            <span id="calendarToggleLabel" class="text-xs mono" style="color:#64748b;">{{ __('app.show_calendar_btn') }}</span>
        </button>
        <div id="calendarSection" style="display:none;" class="mt-2">
            <div class="p-4 rounded-2xl border" style="background:rgba(0,10,30,0.5);border-color:rgba(0,102,255,0.15);">
                <div class="flex items-center justify-between mb-4">
                    <button onclick="prevMonth()" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all"
                            style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:#94a3b8;font-size:18px;">‹</button>
                    <span id="calendarTitle" class="heading font-bold text-white text-base"></span>
                    <button onclick="nextMonth()" class="w-8 h-8 rounded-xl flex items-center justify-center transition-all"
                            style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:#94a3b8;font-size:18px;">›</button>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['S','M','T','W','T','F','S'] as $d)
                    <div class="text-center text-xs font-semibold mono py-1" style="color:#475569;">{{ $d }}</div>
                    @endforeach
                </div>
                <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
                <div class="flex items-center gap-4 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.05);">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:#fbbf24;"></div>
                        <span class="text-xs" style="color:#64748b;">Pending</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:#00f5ff;"></div>
                        <span class="text-xs" style="color:#64748b;">Confirmed</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full" style="background:#4ade80;"></div>
                        <span class="text-xs" style="color:#64748b;">Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="flex gap-2 mb-3 fade-in fade-in-3 overflow-x-auto pb-1 no-print">
        @foreach([
            ['key'=>'all',       'label'=> __('app.filter_all'),  'color'=>'rgba(0,245,255,0.15)',   'border'=>'rgba(0,245,255,0.3)',   'text'=>'var(--cyan)'],
            ['key'=>'pending',   'label'=> __('app.pending'),      'color'=>'rgba(251,191,36,0.15)',  'border'=>'rgba(251,191,36,0.3)',  'text'=>'#fbbf24'],
            ['key'=>'confirmed', 'label'=> __('app.confirmed'),    'color'=>'rgba(0,245,255,0.15)',   'border'=>'rgba(0,245,255,0.3)',   'text'=>'#00f5ff'],
            ['key'=>'completed', 'label'=> __('app.completed'),    'color'=>'rgba(74,222,128,0.15)',  'border'=>'rgba(74,222,128,0.3)',  'text'=>'#4ade80'],
            ['key'=>'cancelled', 'label'=> __('app.cancelled'),    'color'=>'rgba(248,113,113,0.15)','border'=>'rgba(248,113,113,0.3)','text'=>'#f87171'],
        ] as $tab)
        @php
            $tabCount = match($tab['key']) {
                'all'       => $bookings->count(),
                'pending'   => $pending,
                'confirmed' => $confirmed,
                'completed' => $completed,
                'cancelled' => $cancelled,
                default     => 0,
            };
        @endphp
        <button onclick="filterBookings('{{ $tab['key'] }}')"
                id="tab-{{ $tab['key'] }}"
                class="filter-tab flex-shrink-0 px-3 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all border"
                style="background:{{ $tab['key'] === 'all' ? $tab['color'] : 'rgba(255,255,255,0.03)' }};
                       border-color:{{ $tab['key'] === 'all' ? $tab['border'] : 'rgba(255,255,255,0.08)' }};
                       color:{{ $tab['key'] === 'all' ? $tab['text'] : '#64748b' }};"
                data-filter="{{ $tab['key'] }}"
                data-color="{{ $tab['color'] }}"
                data-border="{{ $tab['border'] }}"
                data-text="{{ $tab['text'] }}">
            {{ strtoupper($tab['label']) }}@if($tabCount > 0) <span style="opacity:0.65;">· {{ $tabCount }}</span>@endif
        </button>
        @endforeach
    </div>

    {{-- Search bar --}}
    <div class="relative mb-4 fade-in fade-in-3 no-print">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <x-heroicon-o-magnifying-glass class="w-4 h-4" style="color:#64748b;" />
        </div>
        <input type="text" id="bookingSearch"
               placeholder="{{ __('app.search_bookings_ph') }}"
               oninput="searchBookings(this.value)"
               class="w-full pl-9 pr-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.12);">
    </div>

    {{-- Bookings list --}}
    @if($bookings->isEmpty())
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-inbox class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_bookings_garage') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.bookings_appear_hint') }}</p>
        </div>
    @else

        @php
            $hasUpcoming  = $bookings->whereIn('status',['pending','confirmed'])->isNotEmpty();
            $historyShown = false;
        @endphp
        @if(!$hasUpcoming)
            <div id="empty-upcoming" class="section-header rounded-2xl p-4 mb-3 text-center border"
                 style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
                <p class="text-sm" style="color:#64748b;">{{ __('app.no_upcoming_bookings') }}</p>
            </div>
        @endif

        @php $overdueShown = false; $upcomingShown = false; @endphp
        @foreach($bookings as $index => $booking)
        @php
            $isHistory   = in_array($booking->status, ['completed','cancelled']);
            $isOverdue   = !$isHistory && $booking->booking_date->lt(today());
            $isReturning = in_array(optional($booking->vehicle)->user_id, $returningUserIds);
            $userId      = optional($booking->vehicle)->user_id;
            $custHistory = $userId ? ($customerHistory[$userId] ?? null) : null;
            $statusColor = match($booking->status) {
                'pending'   => ['bg'=>'rgba(251,191,36,0.1)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
                'confirmed' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
                'completed' => ['bg'=>'rgba(74,222,128,0.1)','color'=>'#4ade80','border'=>'rgba(74,222,128,0.2)'],
                'cancelled' => ['bg'=>'rgba(248,113,113,0.1)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
                default     => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
            };
        @endphp

        @if($isOverdue && !$overdueShown)
            @php $overdueShown = true; @endphp
            <div id="header-overdue" class="section-header">
                <p class="section-label mb-3" style="color:rgba(248,113,113,0.7);">{{ __('app.overdue_bookings_label') }}</p>
            </div>
        @endif
        @if(!$isHistory && !$isOverdue && !$upcomingShown)
            @php $upcomingShown = true; @endphp
            <div id="header-upcoming-inner" class="section-header">
                <p class="section-label mb-3 {{ $overdueShown ? 'mt-2' : '' }}">{{ __('app.upcoming_bookings_label') }}</p>
            </div>
        @endif
        @if($isHistory && !$historyShown)
            @php $historyShown = true; @endphp
            <div id="header-history" class="section-header">
                <p class="section-label mb-3 mt-2">{{ __('app.booking_history_label') }}</p>
            </div>
        @endif

        <div class="booking-card glass-bright rounded-2xl p-4 mb-4 border fade-in"
             data-status="{{ $booking->status }}"
             data-group="{{ in_array($booking->status, ['pending','confirmed']) ? 'upcoming' : 'history' }}"
             data-customer="{{ strtolower(optional($booking->vehicle->user)->name ?? '') }}"
             data-plate="{{ strtolower($booking->vehicle->license_plate ?? '') }}"
             style="border-color:{{ $isOverdue ? 'rgba(248,113,113,0.35)' : 'rgba(0,245,255,0.1)' }};
                    {{ $isOverdue ? 'background:rgba(248,113,113,0.03);' : '' }}">

            {{-- Top row --}}
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1 min-w-0 pr-2">
                    <h3 class="heading font-bold text-white text-base leading-tight">{{ $booking->service_type }}</h3>
                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                        <p class="text-xs font-semibold" style="color:#94a3b8;">{{ $booking->vehicle->user->name }}</p>
                        @if($isReturning)
                        <span class="text-xs font-bold heading tracking-wider px-1.5 py-0.5 rounded-md"
                              style="background:rgba(168,85,247,0.12);color:#c084fc;border:1px solid rgba(168,85,247,0.25);font-size:0.6rem;">
                            {{ __('app.returning_customer_tag') }}
                        </span>
                        @endif
                    </div>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ $booking->vehicle->make }} {{ $booking->vehicle->model }} · {{ $booking->vehicle->license_plate }}
                    </p>
                </div>
                <div class="flex flex-col items-end gap-1 flex-shrink-0">
                    <span class="tag" style="background:{{ $statusColor['bg'] }};color:{{ $statusColor['color'] }};border:1px solid {{ $statusColor['border'] }};">
                        {{ strtoupper($booking->status) }}
                    </span>
                    @if($isOverdue)
                    <span class="tag" style="background:rgba(248,113,113,0.12);color:#f87171;border:1px solid rgba(248,113,113,0.3);">
                        <x-heroicon-o-clock class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.overdue_tag') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Customer contact --}}
            <div class="flex items-center gap-1.5 mb-2 rounded-xl px-3 py-2"
                 style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <x-heroicon-o-envelope class="w-3.5 h-3.5 flex-shrink-0" style="color:#64748b;" />
                <span class="text-xs" style="color:#64748b;">{{ __('app.contact_label') }}:</span>
                <span class="text-xs font-semibold" style="color:#94a3b8;">{{ $booking->vehicle->user->email }}</span>
            </div>

            {{-- [4] Customer history panel --}}
            @if($custHistory && $custHistory['count'] > 1)
            <div class="flex items-start gap-2 mb-2 rounded-xl px-3 py-2"
                 style="background:rgba(168,85,247,0.04);border:1px solid rgba(168,85,247,0.12);">
                <x-heroicon-o-clock class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" style="color:rgba(168,85,247,0.5);" />
                <div class="min-w-0">
                    <span class="text-xs font-semibold" style="color:rgba(168,85,247,0.7);">{{ __('app.customer_history_label') }}:</span>
                    <span class="text-xs ml-1" style="color:#64748b;">{{ $custHistory['count'] }} {{ __('app.total_visits_label') }}</span>
                    @if(count($custHistory['services']) > 0)
                    <p class="text-xs mt-0.5 truncate" style="color:#475569;">
                        {{ __('app.prev_services_label') }} {{ implode(', ', $custHistory['services']) }}
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Date & Time --}}
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                    <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.date_label') }}</p>
                    <p class="mono text-sm font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</p>
                </div>
                <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                    <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.time_label') }}</p>
                    <p class="mono text-sm font-bold text-white">{{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}</p>
                </div>
            </div>

            @if($booking->notes)
            <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <p class="text-xs" style="color:#64748b;">{{ $booking->notes }}</p>
            </div>
            @endif

            {{-- [1] Quick action buttons --}}
            @if($booking->status === 'pending')
            <form method="POST" action="{{ route('bookings.update', $booking) }}" class="mb-3 no-print">
                @csrf @method('PATCH')
                <button type="submit" name="status" value="confirmed"
                        class="w-full py-2.5 rounded-xl text-sm font-bold heading tracking-wider transition-all active:scale-95"
                        style="background:linear-gradient(135deg,rgba(0,102,255,0.2),rgba(0,245,255,0.15));border:1px solid rgba(0,245,255,0.35);color:#00f5ff;">
                    <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1 align-middle" />
                    {{ __('app.quick_confirm_btn') }}
                </button>
            </form>
            @elseif($booking->status === 'confirmed')
            <form method="POST" action="{{ route('bookings.update', $booking) }}" class="mb-3 no-print">
                @csrf @method('PATCH')
                <button type="submit" name="status" value="completed"
                        class="w-full py-2.5 rounded-xl text-sm font-bold heading tracking-wider transition-all active:scale-95"
                        style="background:linear-gradient(135deg,rgba(74,222,128,0.15),rgba(0,245,255,0.08));border:1px solid rgba(74,222,128,0.35);color:#4ade80;">
                    <x-heroicon-o-check-badge class="w-4 h-4 inline-block mr-1 align-middle" />
                    {{ __('app.quick_complete_btn') }}
                </button>
            </form>
            @endif

            {{-- Status update grid --}}
            <div class="mb-3 no-print">
                <p class="section-label mb-2">{{ __('app.update_status_label') }}</p>
                <form method="POST" action="{{ route('bookings.update', $booking) }}">
                    @csrf @method('PATCH')
                    <div class="grid grid-cols-2 gap-2">
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

            {{-- Garage Note --}}
            <div class="no-print" style="border-top:1px solid rgba(0,245,255,0.08);padding-top:12px;margin-bottom:12px;">
                <p class="section-label mb-2">{{ __('app.garage_note_label') }}</p>
                <form method="POST" action="{{ route('bookings.note', $booking) }}">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <textarea name="garage_notes" rows="2"
                                  placeholder="{{ __('app.garage_note_ph') }}"
                                  class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                                  style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ $booking->garage_notes }}</textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 border"
                            style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);color:#00f5ff;">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="w-3.5 h-3.5 inline-block mr-1 align-middle" />{{ __('app.save_note_btn') }}
                    </button>
                </form>
            </div>

            {{-- Invoice (completed only) --}}
            @if($booking->status === 'completed')
            <div class="no-print" style="border-top:1px solid rgba(74,222,128,0.15);padding-top:12px;">
                <p class="section-label mb-2">{{ __('app.gen_invoice_label') }}</p>
                @if($booking->invoice_number)
                <div class="flex items-center justify-between mb-2 rounded-xl px-3 py-2"
                     style="background:rgba(74,222,128,0.05);border:1px solid rgba(74,222,128,0.15);">
                    <div>
                        <p class="mono text-xs font-bold" style="color:#4ade80;">{{ $booking->invoice_number }}</p>
                        @if($booking->invoice_date)
                        <p class="text-xs mt-0.5" style="color:#64748b;">{{ \Carbon\Carbon::parse($booking->invoice_date)->format('d M Y') }}</p>
                        @endif
                    </div>
                    <a href="{{ route('bookings.invoice.show', $booking) }}"
                       class="flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                       style="background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                        <x-heroicon-o-document-text class="w-3 h-3" />{{ __('app.view_invoice_btn') }}
                    </a>
                </div>
                @endif
                <form method="POST" action="{{ route('bookings.invoice', $booking) }}">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <input type="number" name="invoice_amount" value="{{ $booking->invoice_amount }}"
                               placeholder="{{ __('app.invoice_amount_ph') }}" min="0" step="0.01"
                               class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(74,222,128,0.2);">
                    </div>
                    <div class="mb-2">
                        <textarea name="invoice_notes" rows="2"
                                  placeholder="{{ __('app.invoice_notes_ph') }}"
                                  class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                                  style="background:rgba(255,255,255,0.04);border:1px solid rgba(74,222,128,0.2);">{{ $booking->invoice_notes }}</textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
                            style="background:rgba(74,222,128,0.12);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                        <x-heroicon-o-archive-box-arrow-down class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.save_invoice_btn') }}
                    </button>
                </form>
            </div>
            @endif

        </div>
        @endforeach
    @endif

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

// ── [6] Booking Calendar ────────────────────────────────────────────────────
const bookingDates = @json($calendarDates);
let calYear  = new Date().getFullYear();
let calMonth = new Date().getMonth();

function toggleCalendar() {
    const sec = document.getElementById('calendarSection');
    const lbl = document.getElementById('calendarToggleLabel');
    const opening = sec.style.display === 'none';
    sec.style.display = opening ? 'block' : 'none';
    lbl.textContent   = opening ? '{{ __("app.hide_calendar_btn") }}' : '{{ __("app.show_calendar_btn") }}';
    if (opening) renderCalendar();
}

function prevMonth() { if (--calMonth < 0) { calMonth = 11; calYear--; } renderCalendar(); }
function nextMonth() { if (++calMonth > 11) { calMonth = 0; calYear++; } renderCalendar(); }

function renderCalendar() {
    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    document.getElementById('calendarTitle').textContent = months[calMonth] + ' ' + calYear;

    const firstDay    = new Date(calYear, calMonth, 1).getDay();
    const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
    const today       = new Date();

    const dateMap = {};
    bookingDates.forEach(b => {
        if (!dateMap[b.date]) dateMap[b.date] = [];
        dateMap[b.date].push(b.status);
    });

    let html = '';
    for (let i = 0; i < firstDay; i++) html += '<div></div>';
    for (let d = 1; d <= daysInMonth; d++) {
        const ds       = calYear + '-' + String(calMonth + 1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
        const statuses = dateMap[ds] || [];
        const isToday  = today.getFullYear() === calYear && today.getMonth() === calMonth && today.getDate() === d;
        const hasPend  = statuses.includes('pending');
        const hasConf  = statuses.includes('confirmed');
        const hasComp  = statuses.includes('completed');

        html += `<div style="min-height:34px;${isToday?'background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);':''}border-radius:8px;" class="flex flex-col items-center justify-start pt-1">
            <span style="font-size:11px;color:${isToday?'#00f5ff':(statuses.length?'#e2e8f0':'#475569')};font-weight:${isToday?'700':'400'};">${d}</span>
            <div style="display:flex;gap:2px;margin-top:2px;">
                ${hasPend?'<div style="width:5px;height:5px;border-radius:50%;background:#fbbf24;"></div>':''}
                ${hasConf?'<div style="width:5px;height:5px;border-radius:50%;background:#00f5ff;"></div>':''}
                ${hasComp?'<div style="width:5px;height:5px;border-radius:50%;background:#4ade80;"></div>':''}
            </div>
        </div>`;
    }
    document.getElementById('calendarGrid').innerHTML = html;
}

// ── Section header helpers ─────────────────────────────────────────────────
function setHeaders(showAll) {
    ['header-overdue','header-upcoming-inner','header-history','empty-upcoming'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = showAll ? 'block' : 'none';
    });
}

// ── Filter tabs ─────────────────────────────────────────────────────────────
function filterBookings(filter) {
    const cards = document.querySelectorAll('.booking-card');
    const tabs  = document.querySelectorAll('.filter-tab');
    const query = document.getElementById('bookingSearch').value.toLowerCase();

    tabs.forEach(tab => {
        const on = tab.dataset.filter === filter;
        tab.style.background  = on ? tab.dataset.color  : 'rgba(255,255,255,0.03)';
        tab.style.borderColor = on ? tab.dataset.border : 'rgba(255,255,255,0.08)';
        tab.style.color       = on ? tab.dataset.text   : '#64748b';
    });

    setHeaders(filter === 'all' && query === '');

    cards.forEach(card => {
        const sm = filter === 'all' || card.dataset.status === filter;
        const qm = query === '' || card.dataset.customer.includes(query) || card.dataset.plate.includes(query);
        card.style.display = (sm && qm) ? 'block' : 'none';
    });
    window._activeFilter = filter;
}

// ── Search ─────────────────────────────────────────────────────────────────
function searchBookings(value) {
    const filter = window._activeFilter || 'all';
    const query  = value.toLowerCase();
    setHeaders(filter === 'all' && query === '');
    document.querySelectorAll('.booking-card').forEach(card => {
        const sm = filter === 'all' || card.dataset.status === filter;
        const qm = query === '' || card.dataset.customer.includes(query) || card.dataset.plate.includes(query);
        card.style.display = (sm && qm) ? 'block' : 'none';
    });
}

window._activeFilter = 'all';
</script>
</x-app-layout>
