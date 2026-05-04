<x-app-layout>

    <div class="max-w-lg mx-auto px-4 pt-5">

        @php
            $vehicles     = Auth::user()->vehicles;
            $vehicleIds   = $vehicles->pluck('id');
            $serviceCount = \App\Models\ServiceLog::whereIn('vehicle_id', $vehicleIds)->count();
            $fuelCount    = \App\Models\FuelLog::whereIn('vehicle_id', $vehicleIds)->count();
            $bookingCount = Auth::user()->bookings()->count();

            // #2 Total spend this month
            $monthlyFuel    = (float) \App\Models\FuelLog::whereIn('vehicle_id', $vehicleIds)
                                ->whereYear('date', now()->year)->whereMonth('date', now()->month)->sum('cost');
            $monthlySvc     = (float) \App\Models\ServiceLog::whereIn('vehicle_id', $vehicleIds)
                                ->whereYear('service_date', now()->year)->whereMonth('service_date', now()->month)->sum('cost');
            $monthlyTotal   = $monthlyFuel + $monthlySvc;

            // #3 Last activity
            $lastFuel = \App\Models\FuelLog::whereIn('vehicle_id', $vehicleIds)->orderBy('date','desc')->orderBy('id','desc')->first();
            $lastSvcAll = \App\Models\ServiceLog::whereIn('vehicle_id', $vehicleIds)->orderBy('service_date','desc')->orderBy('id','desc')->first();
            $lastActivity = null;
            if ($lastFuel && $lastSvcAll) {
                $lastActivity = $lastFuel->date >= $lastSvcAll->service_date
                    ? ['type' => 'fuel',    'label' => __('app.activity_fuelled'), 'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastFuel->date)->startOfDay(), false)]
                    : ['type' => 'service', 'label' => __('app.activity_serviced'),'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastSvcAll->service_date)->startOfDay(), false)];
            } elseif ($lastFuel) {
                $lastActivity = ['type' => 'fuel',    'label' => __('app.activity_fuelled'), 'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastFuel->date)->startOfDay(), false)];
            } elseif ($lastSvcAll) {
                $lastActivity = ['type' => 'service', 'label' => __('app.activity_serviced'),'days' => now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($lastSvcAll->service_date)->startOfDay(), false)];
            }

            // #1 Quick-log smart link — direct if single vehicle
            $singleVehicle = $vehicles->count() === 1 ? $vehicles->first() : null;

            // Latest booking for status card
            $latestBooking = Auth::user()->bookings()->with(['garage','vehicle'])->latest()->first();

            // Find overdue suggestions across all vehicles
            $overdueVehicle = null;
            $overdueService = null;
            foreach ($vehicles as $v) {
                $schedules = \App\Models\MaintenanceSchedule::all();
                foreach ($schedules as $schedule) {
                    $lastService = \App\Models\ServiceLog::where('vehicle_id', $v->id)
                        ->where('service_type', 'like', '%' . explode(' ', $schedule->service_name)[0] . '%')
                        ->orderBy('mileage_at_service', 'desc')->first();
                    $lastKm  = $lastService ? $lastService->mileage_at_service : 0;
                    $nextDue = $lastKm + $schedule->interval_km;
                    if ($v->mileage >= $nextDue) {
                        $overdueVehicle = $v;
                        $overdueService = $schedule->service_name;
                        break 2;
                    }
                }
            }
        @endphp

        {{-- Greeting --}}
        <div class="fade-in fade-in-1 mb-5 flex items-start gap-3">
            {{-- User avatar --}}
            @if(Auth::user()->avatar)
            <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                 alt="{{ Auth::user()->name }}"
                 class="w-14 h-14 rounded-2xl object-cover flex-shrink-0 mt-1"
                 style="border:1px solid rgba(0,245,255,0.2);">
            @else
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-bold heading text-2xl flex-shrink-0 mt-1"
                 style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.15);color:#00f5ff;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            @endif
            <div class="flex-1">
            <p class="section-label mb-1">{{ __('app.welcome_back') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ explode(' ', Auth::user()->name)[0] }}
                <span class="text-cyan">{{ explode(' ', Auth::user()->name)[1] ?? '' }}</span>
            </h1>
            <p class="text-slate-400 text-sm mt-0.5">
                {{ $vehicles->count() }} {{ __('app.vehicles_active') }}
            </p>
            {{-- #3 Last activity --}}
            @if($lastActivity)
            <p class="text-xs mt-1" style="color:#475569;">
                @if($lastActivity['type'] === 'fuel')
                    <x-heroicon-o-beaker class="w-3 h-3 inline-block mr-0.5 align-middle" style="color:#4ade80;" />
                @else
                    <x-heroicon-o-wrench-screwdriver class="w-3 h-3 inline-block mr-0.5 align-middle" style="color:#6699ff;" />
                @endif
                {{ $lastActivity['label'] }}
                @if($lastActivity['days'] == 0) {{ __('app.activity_today') }}
                @elseif($lastActivity['days'] == -1) {{ __('app.activity_yesterday') }}
                @else {{ __('app.activity_days_ago', ['days' => abs((int)$lastActivity['days'])]) }}
                @endif
            </p>
            @endif
            </div>{{-- /flex-1 --}}
        </div>

        {{-- ⚠️ NEXT SERVICE DUE ALERT --}}
        @if($overdueVehicle)
            <div class="fade-in fade-in-2 rounded-2xl p-4 mb-4 animate-glow-orange relative overflow-hidden border"
                 style="background:linear-gradient(135deg,rgba(255,107,0,0.12),rgba(255,60,0,0.06));border-color:rgba(255,107,0,0.35);">
                <div class="absolute top-0 right-0 w-24 h-24 opacity-10 pointer-events-none"
                     style="background:radial-gradient(circle at top right,var(--orange),transparent);"></div>
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center"
                         style="background:rgba(255,107,0,0.15);border:1px solid rgba(255,107,0,0.4);">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5" style="color:var(--orange);" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="tag" style="background:rgba(255,107,0,0.2);color:var(--orange);border:1px solid rgba(255,107,0,0.3);">{{ __('app.overdue') }}</span>
                            <span class="tag" style="background:rgba(255,255,255,0.05);color:#94a3b8;">{{ $overdueVehicle->make }} {{ $overdueVehicle->model }}</span>
                        </div>
                        <h3 class="heading text-lg font-bold text-white leading-tight">{{ $overdueService }}</h3>
                        <p class="text-sm text-slate-400 mt-0.5">
                            {{ __('app.current_mileage') }} <span style="color:var(--orange);" class="font-semibold mono">{{ number_format($overdueVehicle->mileage) }} km</span>
                        </p>
                        <a href="{{ route('suggestions.index', $overdueVehicle) }}"
                           class="mt-3 w-full block text-center py-2 rounded-xl text-sm font-semibold heading tracking-wide transition-all active:scale-95"
                           style="background:rgba(255,107,0,0.2);border:1px solid rgba(255,107,0,0.5);color:var(--orange);">
                            {{ __('app.view_maintenance') }}
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="fade-in fade-in-2 rounded-2xl p-4 mb-4 border"
                 style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.2);">
                        <x-heroicon-o-check class="w-5 h-5" style="color:var(--cyan);" />
                    </div>
                    <div>
                        <p class="font-semibold heading text-white">{{ __('app.all_systems_good') }}</p>
                        <p class="text-xs text-slate-400">{{ __('app.no_overdue') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- LATEST BOOKING STATUS --}}
        @if(isset($latestBooking) && $latestBooking)
        @php
            $bStatusColors = [
                'pending'   => ['bg'=>'rgba(255,107,0,0.08)','border'=>'rgba(255,107,0,0.25)','color'=>'#ff6b00'],
                'confirmed' => ['bg'=>'rgba(0,245,255,0.08)','border'=>'rgba(0,245,255,0.25)','color'=>'#00f5ff'],
                'completed' => ['bg'=>'rgba(74,222,128,0.08)','border'=>'rgba(74,222,128,0.25)','color'=>'#4ade80'],
                'cancelled' => ['bg'=>'rgba(248,113,113,0.08)','border'=>'rgba(248,113,113,0.25)','color'=>'#f87171'],
            ];
            $bsc = $bStatusColors[$latestBooking->status] ?? $bStatusColors['pending'];
        @endphp
        <div class="fade-in fade-in-3 rounded-2xl p-4 mb-4 border"
             style="background:{{ $bsc['bg'] }};border-color:{{ $bsc['border'] }};">
            <div class="flex items-center justify-between mb-1">
                <p class="section-label">{{ __('app.recent_booking_label') }}</p>
                <span class="tag" style="background:{{ $bsc['bg'] }};color:{{ $bsc['color'] }};border:1px solid {{ $bsc['border'] }};">
                    {{ strtoupper($latestBooking->status) }}
                </span>
            </div>
            <p class="font-semibold heading text-white text-sm">{{ $latestBooking->service_type }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">
                {{ $latestBooking->garage->name ?? '—' }} ·
                {{ \Carbon\Carbon::parse($latestBooking->booking_date)->format('d M Y') }}
                @if($latestBooking->vehicle) · {{ $latestBooking->vehicle->make }} {{ $latestBooking->vehicle->model }} @endif
            </p>
            <a href="{{ route('bookings.index') }}"
               class="mt-2 inline-block text-xs py-1 px-3 rounded-lg heading font-semibold tracking-wide"
               style="background:{{ $bsc['bg'] }};border:1px solid {{ $bsc['border'] }};color:{{ $bsc['color'] }};">
                {{ __('app.view_all_bookings') }} →
            </a>
        </div>
        @endif

        {{-- STATS ROW --}}
        <div class="grid grid-cols-2 gap-3 mb-3 fade-in fade-in-3">
            <div class="glass rounded-2xl p-3 text-center border" style="border-color:rgba(255,255,255,0.06);">
                <p class="heading text-2xl font-bold text-white">{{ $serviceCount }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('app.services') }}</p>
            </div>
            <div class="glass rounded-2xl p-3 text-center border" style="border-color:rgba(255,255,255,0.06);">
                <p class="heading text-2xl font-bold text-cyan">{{ $fuelCount }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('app.fuel_logs') }}</p>
            </div>
            <div class="glass rounded-2xl p-3 text-center border" style="border-color:rgba(255,255,255,0.06);">
                <p class="heading text-2xl font-bold" style="color:var(--orange);">{{ $bookingCount }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ __('app.bookings') }}</p>
            </div>
            {{-- #2 Total spend this month --}}
            <div class="glass rounded-2xl p-3 text-center border" style="border-color:rgba(74,222,128,0.1);">
                <p class="heading text-lg font-bold" style="color:#4ade80;">
                    @if($monthlyTotal > 0) {{ number_format($monthlyTotal) }} @else — @endif
                </p>
                <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.this_month_spend') }}</p>
            </div>
        </div>

        {{-- #1 QUICK LOG ACTIONS --}}
        @if($vehicles->isNotEmpty())
        <div class="grid grid-cols-2 gap-3 mb-5 fade-in fade-in-3">
            <a href="{{ $singleVehicle ? route('fuel.create', $singleVehicle) : route('vehicles.index') }}"
               class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                <x-heroicon-o-beaker class="w-3.5 h-3.5" />
                {{ __('app.quick_log_fuel') }}
            </a>
            <a href="{{ $singleVehicle ? route('service.create', $singleVehicle) : route('vehicles.index') }}"
               class="flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:rgba(0,102,255,0.1);border:1px solid rgba(0,102,255,0.25);color:#6699ff;">
                <x-heroicon-o-wrench-screwdriver class="w-3.5 h-3.5" />
                {{ __('app.quick_log_service') }}
            </a>
        </div>
        @endif

        {{-- MY VEHICLES --}}
        <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.my_vehicles') }}</p>

        @forelse($vehicles as $index => $vehicle)
        @php
            // ── Multi-factor health score ──────────────────────────────────────
            // 1. Mileage wear (40 pts): 0 km = 40, 100 000 km = 0
            $mileageScore = max(0, (int) round(40 - ($vehicle->mileage / 100000) * 40));

            // 2. Service recency (40 pts): serviced this week = 40, never/1yr+ = 10
            $lastSvc = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)
                ->orderBy('service_date', 'desc')->first();
            if (!$lastSvc) {
                $svcScore = 10;
            } else {
                $daysSince = \Carbon\Carbon::parse($lastSvc->service_date)->diffInDays(now());
                $svcScore  = max(0, min(40, (int) round(40 - ($daysSince / 365) * 40)));
            }

            // 3. Overdue maintenance (20 pts): -10 per overdue item
            $schedules = \App\Models\MaintenanceSchedule::all();
            $overdueCount = 0;
            $overdueServiceName = null;
            $dueSoonServiceName = null;
            foreach ($schedules as $sched) {
                $lastMaint = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)
                    ->where('service_type', 'like', '%' . explode(' ', $sched->service_name)[0] . '%')
                    ->orderBy('mileage_at_service', 'desc')->first();
                $lastKm = $lastMaint ? $lastMaint->mileage_at_service : 0;
                $kmLeft = ($lastKm + $sched->interval_km) - $vehicle->mileage;
                if ($kmLeft <= 0) {
                    $overdueCount++;
                    if (!$overdueServiceName) $overdueServiceName = explode(' ', $sched->service_name)[0];
                } elseif ($kmLeft <= 500 && !$dueSoonServiceName) {
                    $dueSoonServiceName = explode(' ', $sched->service_name)[0];
                }
            }
            $maintScore = max(0, 20 - ($overdueCount * 10));
            $health     = $mileageScore + $svcScore + $maintScore;   // 0–100

            // Colour zone
            $ringColor = $health >= 70 ? '#00f5ff' : ($health >= 40 ? '#ff6b00' : '#f87171');
            $ringGlow  = $health >= 70 ? 'rgba(0,245,255,0.3)' : ($health >= 40 ? 'rgba(255,107,0,0.3)' : 'rgba(248,113,113,0.3)');

            // Pre-compute stats (reuse $lastSvc from above)
            $avgEfficiency = \App\Models\FuelLog::where('vehicle_id', $vehicle->id)->whereNotNull('km_per_liter')->avg('km_per_liter');
            $svcCount      = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)->count();
            $totalSpend    = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)->sum('cost')
                           + \App\Models\FuelLog::where('vehicle_id', $vehicle->id)->sum('cost');

            // Document expiry alerts
            $expiryAlerts = [];
            foreach ([
                __('app.insurance_expiry_label') => $vehicle->insurance_expiry,
                __('app.registration_expiry_label') => $vehicle->registration_expiry,
                __('app.emission_due_label') => $vehicle->emission_due,
            ] as $docName => $docDate) {
                if ($docDate) {
                    $dLeft = now()->startOfDay()->diffInDays($docDate->copy()->startOfDay(), false);
                    if ($dLeft < 0)       $expiryAlerts[] = ['name' => $docName, 'days' => abs((int)$dLeft), 'status' => 'expired'];
                    elseif ($dLeft <= 30) $expiryAlerts[] = ['name' => $docName, 'days' => (int)$dLeft,      'status' => 'soon'];
                }
            }

        @endphp
            <div class="glass-bright rounded-2xl overflow-hidden mb-3 vehicle-card fade-in fade-in-{{ $index + 3 }} animate-glow border">
                {{-- Vehicle photo strip --}}
                @if($vehicle->image)
                <div class="w-full overflow-hidden" style="height:100px;">
                    <img src="{{ asset('storage/' . $vehicle->image) }}"
                         alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                         class="w-full h-full object-cover">
                </div>
                @endif
                <div class="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="tag" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.25);">{{ __('app.active_badge') }}</span>
                            <span class="tag" style="background:rgba(255,255,255,0.05);color:#64748b;">{{ strtoupper($vehicle->fuel_type) }}</span>
                            @if($overdueServiceName)
                            <span class="tag" style="background:rgba(248,113,113,0.12);color:#f87171;border:1px solid rgba(248,113,113,0.3);">{{ strtoupper($overdueServiceName) }} OVERDUE</span>
                            @elseif($dueSoonServiceName)
                            <span class="tag" style="background:rgba(255,107,0,0.1);color:#ff6b00;border:1px solid rgba(255,107,0,0.25);">{{ strtoupper($dueSoonServiceName) }} DUE SOON</span>
                            @endif
                        </div>
                        <h3 class="heading text-xl font-bold text-white">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                        <p class="text-xs text-slate-500 mono mt-0.5">{{ $vehicle->year }} · {{ $vehicle->license_plate ?? __('app.no_plate') }}</p>
                        @if($vehicle->notes)
                        <p class="text-xs mt-1 leading-snug" style="color:#475569;">{{ Str::limit($vehicle->notes, 55) }}</p>
                        @endif
                    </div>
                    <!-- Ring gauge -->
                    <div class="relative ring-wrap flex-shrink-0">
                        <div class="health-ring"
                             data-health="{{ $health }}"
                             data-color="{{ $ringColor }}"
                             style="width:56px;height:56px;border-radius:50%;
                                    background:conic-gradient({{ $ringColor }} 0% 0%,rgba(255,255,255,0.05) 0% 100%);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 0 15px {{ $ringGlow }};">
                            <div style="width:44px;height:44px;border-radius:50%;background:var(--card);
                                        display:flex;align-items:center;justify-content:center;">
                                <span class="mono text-xs font-bold" style="color:{{ $ringColor }};">{{ $health }}%</span>
                            </div>
                        </div>
                        {{-- Tooltip --}}
                        <div class="absolute right-0 bottom-full mb-2 w-44 rounded-xl p-2.5 ring-tip pointer-events-none z-50"
                             style="background:rgba(8,12,20,0.97);border:1px solid rgba(0,245,255,0.18);">
                            <p class="mono text-xs font-bold mb-1.5" style="color:{{ $ringColor }};">HEALTH: {{ $health }}/100</p>
                            <div class="text-xs space-y-1" style="color:#64748b;">
                                <div class="flex justify-between"><span>Mileage</span><span class="mono">{{ $mileageScore }}/40</span></div>
                                <div class="flex justify-between"><span>Last Service</span><span class="mono">{{ $svcScore }}/40</span></div>
                                <div class="flex justify-between"><span>Maintenance</span><span class="mono">{{ $maintScore }}/20</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-2 mb-2">
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="mono text-sm font-bold text-white">{{ number_format($vehicle->mileage) }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.km_odo') }}</p>
                    </div>
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="mono text-sm font-bold" style="color:#4ade80;">{{ $avgEfficiency ? number_format($avgEfficiency, 1) : '—' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.km_unit') }}</p>
                    </div>
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="mono text-sm font-bold text-white">{{ $svcCount }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.services') }}</p>
                    </div>
                </div>
                {{-- Last service + total spend --}}
                <div class="grid grid-cols-2 gap-2 mb-3">
                    <div class="rounded-xl p-2 flex items-center gap-2" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                        <x-heroicon-o-wrench-screwdriver class="w-3 h-3 flex-shrink-0" style="color:#64748b;" />
                        <div>
                            <p class="text-xs" style="color:#64748b;">{{ __('app.last_service_label') }}</p>
                            <p class="mono text-xs font-bold text-white">{{ $lastSvc ? $lastSvc->service_date->format('d M Y') : '—' }}</p>
                        </div>
                    </div>
                    <div class="rounded-xl p-2 flex items-center gap-2" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                        <x-heroicon-o-banknotes class="w-3 h-3 flex-shrink-0" style="color:#64748b;" />
                        <div>
                            <p class="text-xs" style="color:#64748b;">{{ __('app.total_spend_label') }}</p>
                            <p class="mono text-xs font-bold" style="color:#4ade80;">LKR {{ number_format($totalSpend) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Document expiry alerts --}}
                @if(count($expiryAlerts) > 0)
                <div class="rounded-xl p-2.5 mb-3 space-y-1.5" style="background:rgba(248,113,113,0.05);border:1px solid rgba(248,113,113,0.15);">
                    @foreach($expiryAlerts as $alert)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <x-heroicon-o-exclamation-triangle class="w-3 h-3 flex-shrink-0" style="color:{{ $alert['status'] === 'expired' ? '#f87171' : '#ff6b00' }};" />
                            <span class="text-xs" style="color:#94a3b8;">{{ $alert['name'] }}</span>
                        </div>
                        <span class="mono text-xs font-bold" style="color:{{ $alert['status'] === 'expired' ? '#f87171' : '#ff6b00' }};">
                            {{ $alert['status'] === 'expired' ? __('app.doc_expired_days', ['days' => $alert['days']]) : __('app.doc_expires_in', ['days' => $alert['days']]) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Actions --}}
                <a href="{{ route('vehicles.show', $vehicle) }}"
                   class="flex items-center justify-center gap-1.5 w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95 mb-2"
                   style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                    <x-heroicon-o-eye class="w-3 h-3 align-middle" />{{ __('app.view_details') }}
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('suggestions.index', $vehicle) }}"
                       class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
                       style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                        <x-heroicon-o-light-bulb class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.insights') }}
                    </a>
                    <a href="{{ route('service.index', $vehicle) }}"
                       class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
                       style="background:rgba(0,102,255,0.12);border:1px solid rgba(0,102,255,0.25);color:#6699ff;">
                        <x-heroicon-o-wrench-screwdriver class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.service_btn') }}
                    </a>
                    <a href="{{ route('fuel.index', $vehicle) }}"
                       class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
                       style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#94a3b8;">
                        <x-heroicon-o-beaker class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.fuel_btn') }}
                    </a>
                </div>
                </div>{{-- /p-4 --}}
            </div>
        @empty
            <div class="glass rounded-2xl p-8 text-center mb-4 fade-in fade-in-3 border" style="border-color:rgba(255,255,255,0.06);">
                <x-heroicon-o-truck class="w-12 h-12 mx-auto mb-3" style="color:#64748b;" />
                <p class="heading text-lg font-bold text-white">{{ __('app.no_vehicles_yet') }}</p>
                <p class="text-slate-500 text-sm mt-1">{{ __('app.add_first_vehicle') }}</p>
                <a href="{{ route('vehicles.create') }}"
                   class="mt-4 inline-block px-6 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all"
                   style="background:rgba(0,245,255,0.15);border:1px solid rgba(0,245,255,0.3);color:var(--cyan);">
                    {{ __('app.add_vehicle') }}
                </a>
            </div>
        @endforelse

        {{-- QR HISTORY PASS CAROUSEL --}}
        @if($vehicles->isNotEmpty())
            <div class="flex items-center justify-between mb-3 fade-in fade-in-5">
                <p class="section-label">{{ __('app.history_pass_label') }}</p>
                @if($vehicles->count() > 1)
                <div class="flex items-center gap-2">
                    <button onclick="qrSlide(-1)"
                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-all active:scale-90"
                            style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <span id="qr-counter" class="mono text-xs" style="color:#64748b;">1 / {{ $vehicles->count() }}</span>
                    <button onclick="qrSlide(1)"
                            class="w-7 h-7 rounded-lg flex items-center justify-center transition-all active:scale-90"
                            style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
                @endif
            </div>

            {{-- Carousel track --}}
            <div style="overflow:hidden;" class="fade-in fade-in-5 mb-6">
                <div id="qr-track" style="display:flex;transition:transform 0.35s cubic-bezier(0.4,0,0.2,1);will-change:transform;">
                    @foreach($vehicles as $qrVehicle)
                    <div style="min-width:100%;padding-right:0;">
                        <div class="rounded-2xl p-5 relative overflow-hidden border animate-glow"
                             style="background:linear-gradient(135deg,rgba(0,102,255,0.12),rgba(0,245,255,0.06));border-color:rgba(0,245,255,0.2);">
                            <div class="absolute top-0 left-0 w-32 h-32 opacity-20 pointer-events-none"
                                 style="background:radial-gradient(circle at top left,var(--cyan),transparent);"></div>
                            <div class="flex items-center gap-4">
                                <!-- QR Visual -->
                                <div class="flex-shrink-0 relative rounded-xl overflow-hidden p-3"
                                     style="background:rgba(0,245,255,0.04);border:1px solid rgba(0,245,255,0.2);">
                                    <div class="relative" style="width:80px;height:80px;">
                                        <div class="absolute top-0 left-0 w-5 h-5 border-2 border-cyan-400 rounded-sm"></div>
                                        <div class="absolute top-0 right-0 w-5 h-5 border-2 border-cyan-400 rounded-sm"></div>
                                        <div class="absolute bottom-0 left-0 w-5 h-5 border-2 border-cyan-400 rounded-sm"></div>
                                        <div class="absolute top-1.5 left-1.5 w-2 h-2 bg-cyan-400"></div>
                                        <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-cyan-400"></div>
                                        <div class="absolute bottom-1.5 left-1.5 w-2 h-2 bg-cyan-400"></div>
                                        <div class="scan-line" style="opacity:0.6;"></div>
                                    </div>
                                </div>
                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="heading text-base font-bold text-white">{{ __('app.history_pass') }}</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $qrVehicle->make }} {{ $qrVehicle->model }} · {{ $qrVehicle->license_plate ?? __('app.no_plate') }}</p>
                                    <div class="mt-2 space-y-1">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                                            <span class="text-xs text-slate-400">{{ \App\Models\ServiceLog::where('vehicle_id', $qrVehicle->id)->count() }} {{ __('app.service_records') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full" style="background:var(--cyan);"></div>
                                            <span class="text-xs text-slate-400">{{ __('app.shareable') }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('qrcode.show', $qrVehicle) }}"
                                       class="mt-3 inline-block px-4 py-1.5 rounded-lg text-xs font-semibold heading tracking-wider"
                                       style="background:rgba(0,245,255,0.15);border:1px solid rgba(0,245,255,0.3);color:var(--cyan);">
                                        {{ __('app.view_qr_code') }}
                                    </a>
                                </div>
                            </div>
                            @if($qrVehicle->qr_token)
                                <div class="mt-4 pt-3 border-t" style="border-color:rgba(0,245,255,0.1);">
                                    <p class="section-label mb-1">{{ __('app.public_token') }}</p>
                                    <p class="mono text-xs truncate" style="color:rgba(0,245,255,0.6);">{{ $qrVehicle->qr_token }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Dot indicators (only when >1 vehicle) --}}
            @if($vehicles->count() > 1)
            <div id="qr-dots" class="flex justify-center gap-1.5 -mt-4 mb-6">
                @foreach($vehicles as $i => $v)
                <div class="qr-dot rounded-full transition-all"
                     style="width:{{ $i === 0 ? '16px' : '6px' }};height:6px;background:{{ $i === 0 ? 'var(--cyan)' : 'rgba(255,255,255,0.15)' }};"></div>
                @endforeach
            </div>
            @endif

            <script>
            (function () {
                var total   = {{ $vehicles->count() }};
                var current = 0;
                var track   = document.getElementById('qr-track');
                var counter = document.getElementById('qr-counter');
                var dots    = document.querySelectorAll('.qr-dot');

                // Touch/swipe support
                var startX = 0;
                track.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
                track.addEventListener('touchend',   function (e) {
                    var diff = startX - e.changedTouches[0].clientX;
                    if (Math.abs(diff) > 40) qrSlide(diff > 0 ? 1 : -1);
                }, { passive: true });

                window.qrSlide = function (dir) {
                    current = (current + dir + total) % total;
                    track.style.transform = 'translateX(-' + (current * 100) + '%)';
                    if (counter) counter.textContent = (current + 1) + ' / ' + total;
                    dots.forEach(function (d, i) {
                        d.style.width      = i === current ? '16px' : '6px';
                        d.style.background = i === current ? 'var(--cyan)' : 'rgba(255,255,255,0.15)';
                    });
                };
            })();
            </script>
        @endif

        {{-- Admin panel link --}}
        @if(Auth::user()->role === 'admin')
            <div class="glass rounded-2xl p-4 mb-6 fade-in fade-in-5 border" style="border-color:rgba(168,85,247,0.2);">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="heading font-bold text-white">{{ __('app.admin_panel') }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ __('app.system_stats') }}</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-4 py-2 rounded-xl text-xs font-semibold heading tracking-wider"
                       style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#a855f7;">
                        {{ __('app.open') }}
                    </a>
                </div>
            </div>
        @endif

    </div>

</x-app-layout>
<style>
.ring-wrap:hover .ring-tip { opacity: 1; }
.ring-tip { opacity: 0; transition: opacity 0.2s ease; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.health-ring').forEach(function (ring) {
        var target = parseInt(ring.dataset.health, 10);
        var color  = ring.dataset.color;
        var current = 0;
        var steps   = 48;
        var inc     = target / steps;
        var i = 0;
        var timer = setInterval(function () {
            i++;
            current = Math.min(i * inc, target);
            ring.style.background =
                'conic-gradient(' + color + ' 0% ' + current + '%, rgba(255,255,255,0.05) ' + current + '% 100%)';
            if (i >= steps) clearInterval(timer);
        }, 16);
    });
});
</script>
