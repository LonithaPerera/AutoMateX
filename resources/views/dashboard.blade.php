<x-app-layout>

    <div class="max-w-lg mx-auto px-4 pt-5">

        {{-- Greeting --}}
        <div class="fade-in fade-in-1 mb-5">
            <p class="section-label mb-1">{{ __('app.welcome_back') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ explode(' ', Auth::user()->name)[0] }}
                <span class="text-cyan">{{ explode(' ', Auth::user()->name)[1] ?? '' }}</span>
            </h1>
            <p class="text-slate-400 text-sm mt-0.5">
                {{ Auth::user()->vehicles()->count() }} {{ __('app.vehicles_active') }}
            </p>
        </div>

        @php
            $vehicles     = Auth::user()->vehicles;
            $vehicleIds   = $vehicles->pluck('id');
            $serviceCount = \App\Models\ServiceLog::whereIn('vehicle_id', $vehicleIds)->count();
            $fuelCount    = \App\Models\FuelLog::whereIn('vehicle_id', $vehicleIds)->count();
            $bookingCount = Auth::user()->bookings()->count();

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

        {{-- STATS ROW --}}
        <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-3">
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
        </div>

        {{-- MY VEHICLES --}}
        <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.my_vehicles') }}</p>

        @forelse($vehicles as $index => $vehicle)
            <div class="glass-bright rounded-2xl p-4 mb-3 vehicle-card fade-in fade-in-{{ $index + 3 }} animate-glow border">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="tag" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.25);">{{ __('app.active_badge') }}</span>
                            <span class="tag" style="background:rgba(255,255,255,0.05);color:#64748b;">{{ strtoupper($vehicle->fuel_type) }}</span>
                        </div>
                        <h3 class="heading text-xl font-bold text-white">{{ $vehicle->make }} {{ $vehicle->model }}</h3>
                        <p class="text-xs text-slate-500 mono mt-0.5">{{ $vehicle->year }} · {{ $vehicle->license_plate ?? __('app.no_plate') }}</p>
                    </div>
                    <!-- Ring gauge -->
                    <div class="speed-ring {{ $index > 0 ? 'speed-ring-orange' : '' }}">
                        <div class="speed-ring-inner">
                            @php
                                $health = max(0, min(100, 100 - round(($vehicle->mileage / 100000) * 100)));
                            @endphp
                            <span class="text-xs font-bold mono {{ $index > 0 ? '' : 'text-cyan' }}"
                                  style="{{ $index > 0 ? 'color:var(--orange)' : '' }}">{{ $health }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        <p class="mono text-sm font-bold text-white">{{ number_format($vehicle->mileage) }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.km_odo') }}</p>
                    </div>
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        @php
                            $avgEfficiency = \App\Models\FuelLog::where('vehicle_id', $vehicle->id)
                                ->whereNotNull('km_per_liter')->avg('km_per_liter');
                        @endphp
                        <p class="mono text-sm font-bold" style="color:#4ade80;">{{ $avgEfficiency ? number_format($avgEfficiency, 1) : '—' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.km_unit') }}</p>
                    </div>
                    <div class="rounded-xl p-2.5 text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                        @php
                            $svcCount = \App\Models\ServiceLog::where('vehicle_id', $vehicle->id)->count();
                        @endphp
                        <p class="mono text-sm font-bold text-white">{{ $svcCount }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('app.services') }}</p>
                    </div>
                </div>

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

        {{-- QR HISTORY PASS --}}
        @if($vehicles->isNotEmpty())
            @php $firstVehicle = $vehicles->first(); @endphp
            <p class="section-label mb-3 fade-in fade-in-5">{{ __('app.history_pass_label') }}</p>
            <div class="fade-in fade-in-5 rounded-2xl p-5 mb-6 relative overflow-hidden border animate-glow"
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
                        <p class="text-xs text-slate-400 mt-0.5">{{ $firstVehicle->make }} {{ $firstVehicle->model }} · {{ $firstVehicle->license_plate ?? __('app.no_plate') }}</p>
                        <div class="mt-2 space-y-1">
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-400"></div>
                                <span class="text-xs text-slate-400">{{ \App\Models\ServiceLog::where('vehicle_id', $firstVehicle->id)->count() }} {{ __('app.service_records') }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full" style="background:var(--cyan);"></div>
                                <span class="text-xs text-slate-400">{{ __('app.shareable') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('qrcode.show', $firstVehicle) }}"
                           class="mt-3 inline-block px-4 py-1.5 rounded-lg text-xs font-semibold heading tracking-wider"
                           style="background:rgba(0,245,255,0.15);border:1px solid rgba(0,245,255,0.3);color:var(--cyan);">
                            {{ __('app.view_qr_code') }}
                        </a>
                    </div>
                </div>
                @if($firstVehicle->qr_token)
                    <div class="mt-4 pt-3 border-t" style="border-color:rgba(0,245,255,0.1);">
                        <p class="section-label mb-1">{{ __('app.public_token') }}</p>
                        <p class="mono text-xs truncate" style="color:rgba(0,245,255,0.6);">{{ $firstVehicle->qr_token }}</p>
                    </div>
                @endif
            </div>
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
