<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ $vehicle->make }} {{ $vehicle->model }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1 flex items-start justify-between">
        <div>
            <p class="section-label mb-1">{{ __('app.vehicle_details_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ $vehicle->make }} <span class="text-cyan">{{ $vehicle->model }}</span>
            </h1>
            <p class="text-xs mono mt-1" style="color:#64748b;">
                {{ $vehicle->year }} · {{ number_format($vehicle->mileage) }} km
            </p>
        </div>
        <a href="{{ route('vehicles.edit', $vehicle) }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1 flex-shrink-0"
           style="background:rgba(255,107,0,0.1);border:1px solid rgba(255,107,0,0.25);color:#ff6b00;">
            <x-heroicon-o-pencil-square class="w-3.5 h-3.5" />
            {{ __('app.edit_vehicle_btn') }}
        </a>
    </div>

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-xl" style="background:rgba(74,222,128,0.1);border:1px solid rgba(74,222,128,0.3);">
            <p class="text-xs flex items-center gap-1" style="color:#4ade80;"><x-heroicon-o-check class="w-3 h-3 flex-shrink-0" /> {{ session('success') }}</p>
        </div>
    @endif

    {{-- ── Document Expiry Notifications ──────────────────────────── --}}
    @php
        $docAlerts = [];
        $docChecks = [
            'insurance'    => ['label' => __('app.insurance_expiry_label'),    'date' => $vehicle->insurance_expiry],
            'registration' => ['label' => __('app.registration_expiry_label'), 'date' => $vehicle->registration_expiry],
            'emission'     => ['label' => __('app.emission_due_label'),         'date' => $vehicle->emission_due],
        ];
        foreach ($docChecks as $doc) {
            if (!$doc['date']) continue;
            $days = now()->startOfDay()->diffInDays($doc['date']->startOfDay(), false);
            if ($days <= 30) {
                $docAlerts[] = [
                    'label'    => $doc['label'],
                    'days'     => $days,
                    'expired'  => $days < 0,
                    'today'    => $days === 0,
                ];
            }
        }
        $hasExpired = collect($docAlerts)->where('expired', true)->isNotEmpty();
    @endphp

    @if(count($docAlerts) > 0)
    <div class="rounded-2xl p-4 mb-4 border fade-in"
         style="background:rgba({{ $hasExpired ? '248,113,113' : '255,107,0' }},0.07);border-color:rgba({{ $hasExpired ? '248,113,113' : '255,107,0' }},0.3);">
        <div class="flex items-start gap-3">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 flex-shrink-0 mt-0.5"
                style="color:{{ $hasExpired ? '#f87171' : '#ff6b00' }};" />
            <div class="flex-1 min-w-0">
                <p class="heading text-xs font-bold tracking-wider mb-2"
                   style="color:{{ $hasExpired ? '#f87171' : '#ff6b00' }};">
                    {{ $hasExpired ? '// DOCUMENT EXPIRED' : '// DOCUMENT EXPIRY WARNING' }}
                </p>
                @foreach($docAlerts as $alert)
                <div class="flex items-center justify-between py-1 border-b last:border-0"
                     style="border-color:rgba(255,255,255,0.05);">
                    <p class="text-xs text-white">{{ $alert['label'] }}</p>
                    <span class="tag ml-2 flex-shrink-0" style="
                        background:rgba({{ $alert['expired'] ? '248,113,113' : ($alert['today'] ? '248,113,113' : '255,107,0') }},0.15);
                        color:{{ $alert['expired'] ? '#f87171' : ($alert['today'] ? '#f87171' : '#ff6b00') }};
                        border:1px solid rgba({{ $alert['expired'] ? '248,113,113' : ($alert['today'] ? '248,113,113' : '255,107,0') }},0.3);">
                        @if($alert['expired'])
                            {{ __('app.doc_alert_expired') }}
                        @elseif($alert['today'])
                            {{ __('app.doc_alert_due_today') }}
                        @else
                            {{ __('app.doc_alert_days_left', ['days' => $alert['days']]) }}
                        @endif
                    </span>
                </div>
                @endforeach
                <p class="text-xs mt-2" style="color:#64748b;">
                    {{ __('app.doc_expiry_section') }} ↓
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Vehicle Photo ─────────────────────────────────────────── --}}
    <div class="glass-bright rounded-2xl mb-4 border overflow-hidden fade-in fade-in-1"
         style="border-color:rgba(0,245,255,0.12);">
        @if($vehicle->image)
            <img src="{{ asset('storage/' . $vehicle->image) }}"
                 alt="{{ $vehicle->make }} {{ $vehicle->model }}"
                 class="w-full object-cover"
                 style="max-height:220px;">
        @else
            <div class="flex flex-col items-center justify-center py-10 gap-2"
                 style="background:rgba(255,255,255,0.02);">
                <x-heroicon-o-photo class="w-12 h-12" style="color:#1e293b;" />
                <p class="text-xs" style="color:#334155;">{{ __('app.vehicle_photo_label') }}</p>
            </div>
        @endif
        {{-- Photo upload form --}}
        <div class="p-3 border-t" style="border-color:rgba(0,245,255,0.08);">
            <form method="POST" action="{{ route('vehicles.updatePhoto', $vehicle) }}"
                  enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf @method('PATCH')
                <label class="flex-1 flex items-center gap-2 px-3 py-2 rounded-xl cursor-pointer transition-all"
                       style="background:rgba(255,255,255,0.03);border:1px dashed rgba(0,245,255,0.2);">
                    <x-heroicon-o-camera class="w-4 h-4 flex-shrink-0" style="color:#475569;" />
                    <span class="text-xs" style="color:#64748b;" id="photo-name-show">{{ $vehicle->image ? __('app.update_photo_btn') : __('app.upload_photo_btn') }}</span>
                    <input type="file" name="photo" accept="image/*" class="sr-only"
                           onchange="document.getElementById('photo-name-show').textContent = this.files[0]?.name ?? ''; document.getElementById('photo-submit-show').classList.remove('hidden');">
                </label>
                <button type="submit" id="photo-submit-show"
                        class="hidden px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.3);color:var(--cyan);">
                    {{ __('app.update_btn') }}
                </button>
            </form>
            {{-- Remove photo (only when photo exists) --}}
            @if($vehicle->image)
            <form method="POST" action="{{ route('vehicles.removePhoto', $vehicle) }}"
                  class="mt-2"
                  onsubmit="return confirm('{{ __('app.remove_photo_confirm') }}')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-xl transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                    <x-heroicon-o-trash class="w-3 h-3" />
                    {{ __('app.remove_photo_btn') }}
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- ── Next Service Reminder ─────────────────────────────────── --}}
    @if(isset($nextService) && $nextService)
    @php
        $nsColor  = $nextService['status'] === 'overdue' ? '#f87171' : ($nextService['status'] === 'due_soon' ? '#ff6b00' : '#00f5ff');
        $nsBg     = $nextService['status'] === 'overdue' ? 'rgba(248,113,113,0.08)' : ($nextService['status'] === 'due_soon' ? 'rgba(255,107,0,0.08)' : 'rgba(0,245,255,0.06)');
        $nsBorder = $nextService['status'] === 'overdue' ? 'rgba(248,113,113,0.3)' : ($nextService['status'] === 'due_soon' ? 'rgba(255,107,0,0.3)' : 'rgba(0,245,255,0.2)');
    @endphp
    <div class="rounded-2xl p-4 mb-4 border fade-in fade-in-2 flex items-center gap-3"
         style="background:{{ $nsBg }};border-color:{{ $nsBorder }};">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background:{{ $nsBg }};border:1px solid {{ $nsBorder }};">
            @if($nextService['status'] === 'overdue')
                <x-heroicon-o-exclamation-triangle class="w-4 h-4" style="color:{{ $nsColor }};" />
            @elseif($nextService['status'] === 'due_soon')
                <x-heroicon-o-exclamation-circle class="w-4 h-4" style="color:{{ $nsColor }};" />
            @else
                <x-heroicon-o-clock class="w-4 h-4" style="color:{{ $nsColor }};" />
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="section-label mb-0.5">{{ __('app.next_service_due_label') }}</p>
            <p class="heading font-bold text-white text-sm leading-tight">{{ $nextService['name'] }}</p>
            <p class="text-xs mt-0.5" style="color:{{ $nsColor }};">
                @if($nextService['status'] === 'overdue')
                    {{ number_format(abs($nextService['km_left'])) }} {{ __('app.km_overdue') }}
                @else
                    {{ number_format($nextService['km_left']) }} {{ __('app.km_away') }}
                @endif
            </p>
        </div>
        <a href="{{ route('suggestions.index', $vehicle) }}"
           class="text-xs py-1.5 px-3 rounded-lg heading font-semibold tracking-wide flex-shrink-0"
           style="background:{{ $nsBg }};border:1px solid {{ $nsBorder }};color:{{ $nsColor }};">
            {{ __('app.view_maintenance') }}
        </a>
    </div>
    @endif

    {{-- ── Documents & Expiry ────────────────────────────────────── --}}
    @php
        $docs = [
            ['label' => __('app.insurance_expiry_label'),    'date' => $vehicle->insurance_expiry,    'icon' => 'shield-check'],
            ['label' => __('app.registration_expiry_label'), 'date' => $vehicle->registration_expiry, 'icon' => 'identification'],
            ['label' => __('app.emission_due_label'),         'date' => $vehicle->emission_due,         'icon' => 'beaker'],
        ];
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-2"
         style="border-color:rgba(168,85,247,0.15);">
        <div class="flex items-center justify-between mb-3">
            <p class="section-label">{{ __('app.doc_expiry_section') }}</p>
        </div>
        <div class="grid grid-cols-3 gap-2 mb-3">
            @foreach($docs as $doc)
            @php
                $daysLeft = $doc['date'] ? now()->startOfDay()->diffInDays($doc['date']->startOfDay(), false) : null;
                if ($daysLeft === null) {
                    $dColor = '#475569'; $dBg = 'rgba(255,255,255,0.03)'; $dBorder = 'rgba(255,255,255,0.06)';
                    $dText = __('app.doc_not_set');
                } elseif ($daysLeft < 0) {
                    $dColor = '#f87171'; $dBg = 'rgba(248,113,113,0.08)'; $dBorder = 'rgba(248,113,113,0.25)';
                    $dText = __('app.doc_expired_days', ['days' => abs($daysLeft)]);
                } elseif ($daysLeft === 0) {
                    $dColor = '#f87171'; $dBg = 'rgba(248,113,113,0.08)'; $dBorder = 'rgba(248,113,113,0.25)';
                    $dText = __('app.doc_due_today');
                } elseif ($daysLeft <= 30) {
                    $dColor = '#ff6b00'; $dBg = 'rgba(255,107,0,0.08)'; $dBorder = 'rgba(255,107,0,0.25)';
                    $dText = __('app.doc_expires_in', ['days' => $daysLeft]);
                } else {
                    $dColor = '#4ade80'; $dBg = 'rgba(74,222,128,0.06)'; $dBorder = 'rgba(74,222,128,0.2)';
                    $dText = $doc['date']->format('d M Y');
                }
            @endphp
            <div class="rounded-xl p-2.5" style="background:{{ $dBg }};border:1px solid {{ $dBorder }};">
                <p class="text-xs mb-1" style="color:#64748b;font-size:9px;">{{ $doc['label'] }}</p>
                <p class="mono text-xs font-bold leading-tight" style="color:{{ $dColor }};">{{ $dText }}</p>
            </div>
            @endforeach
        </div>
        {{-- Update document dates --}}
        <form method="POST" action="{{ route('vehicles.updateDocuments', $vehicle) }}">
            @csrf @method('PATCH')
            <div class="grid grid-cols-3 gap-2 mb-2">
                <div>
                    <label class="section-label mb-1 block" style="font-size:9px;">{{ __('app.insurance_expiry_label') }}</label>
                    <input type="date" name="insurance_expiry"
                           value="{{ $vehicle->insurance_expiry?->format('Y-m-d') }}"
                           class="w-full px-2 py-1.5 rounded-lg text-xs text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-1 block" style="font-size:9px;">{{ __('app.registration_expiry_label') }}</label>
                    <input type="date" name="registration_expiry"
                           value="{{ $vehicle->registration_expiry?->format('Y-m-d') }}"
                           class="w-full px-2 py-1.5 rounded-lg text-xs text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-1 block" style="font-size:9px;">{{ __('app.emission_due_label') }}</label>
                    <input type="date" name="emission_due"
                           value="{{ $vehicle->emission_due?->format('Y-m-d') }}"
                           class="w-full px-2 py-1.5 rounded-lg text-xs text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);color-scheme:dark;">
                </div>
            </div>
            <button type="submit"
                    class="px-4 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.3);color:#a855f7;">
                {{ __('app.update_btn') }}
            </button>
        </form>
    </div>

    {{-- ── Specifications ────────────────────────────────────────── --}}
    <div class="glass-bright rounded-2xl p-5 mb-4 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-3">{{ __('app.specifications_label') }}</p>
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                [__('app.spec_make'), $vehicle->make],
                [__('app.spec_model'), $vehicle->model],
                [__('app.spec_year'), $vehicle->year],
                [__('app.spec_mileage'), number_format($vehicle->mileage).' km'],
                [__('app.spec_fuel_type'), ucfirst($vehicle->fuel_type ?? '—')],
                [__('app.spec_color'), $vehicle->color ?? __('app.not_specified')],
                [__('app.spec_license'), $vehicle->license_plate ?? __('app.not_specified')],
                [__('app.spec_vin'), $vehicle->vin ?? __('app.not_specified')],
            ] as [$label, $value])
            <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-1" style="color:#64748b;">{{ $label }}</p>
                <p class="mono text-sm font-bold text-white">{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Charts (Bar + Donut) ──────────────────────────────────── --}}
    @php
        $hasSpend    = collect($monthlySpend)->sum('fuel') + collect($monthlySpend)->sum('service') > 0;
        $totalFuel   = (float) $vehicle->fuelLogs()->sum('cost');
        $totalSvc    = (float) $vehicle->serviceLogs()->sum('cost');
        $hasDonut    = ($totalFuel + $totalSvc) > 0;
    @endphp

    {{-- Monthly bar chart --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-3">{{ __('app.monthly_spend_label') }}</p>
        @if($hasSpend)
            <canvas id="spendChart" height="130"></canvas>
        @else
            <div class="flex flex-col items-center justify-center py-6 gap-2">
                <x-heroicon-o-chart-bar class="w-8 h-8" style="color:#334155;" />
                <p class="text-xs text-center" style="color:#475569;">{{ __('app.no_spend_chart_hint') }}</p>
            </div>
        @endif
    </div>

    {{-- Fuel vs Service donut --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="border-color:rgba(74,222,128,0.12);">
        <p class="section-label mb-3">{{ __('app.spend_breakdown_label') }}</p>
        @if($hasDonut)
        <div class="flex items-center gap-6">
            <div class="flex-shrink-0" style="width:120px;height:120px;">
                <canvas id="donutChart"></canvas>
            </div>
            <div class="flex-1 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background:#4ade80;"></div>
                        <span class="text-xs" style="color:#94a3b8;">{{ __('app.fuel_btn') }}</span>
                    </div>
                    <span class="mono text-xs font-bold" style="color:#4ade80;">LKR {{ number_format($totalFuel) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full" style="background:#6699ff;"></div>
                        <span class="text-xs" style="color:#94a3b8;">{{ __('app.service_btn') }}</span>
                    </div>
                    <span class="mono text-xs font-bold" style="color:#6699ff;">LKR {{ number_format($totalSvc) }}</span>
                </div>
                <div class="pt-1 border-t" style="border-color:rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color:#64748b;">Total</span>
                        <span class="mono text-xs font-bold text-white">LKR {{ number_format($totalFuel + $totalSvc) }}</span>
                    </div>
                </div>
                @if($costPerKmStat)
                <div class="pt-1 border-t" style="border-color:rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color:#64748b;">{{ __('app.cost_per_km_stat') }}</span>
                        <span class="mono text-xs font-bold" style="color:#f59e0b;">LKR {{ number_format($costPerKmStat, 2) }}</span>
                    </div>
                </div>
                @endif
                {{-- #4 Efficiency trend --}}
                @if(isset($efficiencyTrend) && $efficiencyTrend)
                @php
                    $tColor = $efficiencyTrend['direction'] === 'up' ? '#4ade80' : ($efficiencyTrend['direction'] === 'down' ? '#f87171' : '#94a3b8');
                    $tIcon  = $efficiencyTrend['direction'] === 'up' ? '↑' : ($efficiencyTrend['direction'] === 'down' ? '↓' : '→');
                @endphp
                <div class="pt-1 border-t" style="border-color:rgba(255,255,255,0.06);">
                    <div class="flex items-center justify-between">
                        <span class="text-xs" style="color:#64748b;">{{ __('app.efficiency_trend_label') }}</span>
                        <span class="mono text-xs font-bold" style="color:{{ $tColor }};">
                            {{ $tIcon }} {{ $efficiencyTrend['value'] }} km/L
                            @if($efficiencyTrend['direction'] !== 'stable')
                            <span style="color:#475569;font-size:10px;">({{ $efficiencyTrend['pct'] }}%)</span>
                            @endif
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @else
            <div class="flex flex-col items-center justify-center py-6 gap-2">
                <x-heroicon-o-chart-pie class="w-8 h-8" style="color:#334155;" />
                <p class="text-xs text-center" style="color:#475569;">{{ __('app.no_spend_chart_hint') }}</p>
            </div>
        @endif
    </div>

    {{-- Mileage history chart --}}
    @if(count($mileageHistory) >= 2)
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3" style="border-color:rgba(255,107,0,0.12);">
        <p class="section-label mb-3">{{ __('app.mileage_history_label') }}</p>
        <canvas id="mileageChart" height="120"></canvas>
    </div>
    @endif

    @if($hasSpend || $hasDonut || count($mileageHistory) >= 2)
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        @if($hasSpend)
        new Chart(document.getElementById('spendChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($monthlySpend)->pluck('month')) !!},
                datasets: [
                    { label: '{{ __('app.fuel_btn') }}', data: {!! json_encode(collect($monthlySpend)->pluck('fuel')) !!}, backgroundColor: 'rgba(74,222,128,0.7)', borderRadius: 4 },
                    { label: '{{ __('app.service_btn') }}', data: {!! json_encode(collect($monthlySpend)->pluck('service')) !!}, backgroundColor: 'rgba(0,102,255,0.7)', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { labels: { color: '#94a3b8', font: { size: 10 } } },
                    tooltip: { callbacks: { label: ctx => 'LKR ' + ctx.parsed.y.toLocaleString() } }
                },
                scales: {
                    x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
                    y: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } }
                }
            }
        });
        @endif
        @if($hasDonut)
        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ['{{ __('app.fuel_btn') }}', '{{ __('app.service_btn') }}'],
                datasets: [{
                    data: [{{ $totalFuel }}, {{ $totalSvc }}],
                    backgroundColor: ['rgba(74,222,128,0.8)', 'rgba(102,153,255,0.8)'],
                    borderColor: ['#4ade80', '#6699ff'],
                    borderWidth: 2,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => 'LKR ' + ctx.parsed.toLocaleString() } }
                }
            }
        });
        @endif
        @if(count($mileageHistory) >= 2)
        new Chart(document.getElementById('mileageChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($mileageHistory->pluck('date')) !!},
                datasets: [{
                    label: 'Odometer (km)',
                    data: {!! json_encode($mileageHistory->pluck('km')) !!},
                    borderColor: '#ff6b00',
                    backgroundColor: 'rgba(255,107,0,0.08)',
                    pointBackgroundColor: '#ff6b00',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ctx.parsed.y.toLocaleString() + ' km' } }
                },
                scales: {
                    x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
                    y: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' }, beginAtZero: false }
                }
            }
        });
        @endif
    });
    </script>
    @endif

    {{-- ── Update Mileage ────────────────────────────────────────── --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(255,107,0,0.2);">
        <p class="section-label mb-2">{{ __('app.update_mileage_label') }}</p>
        <form method="POST" action="{{ route('vehicles.updateMileage', $vehicle) }}">
            @csrf @method('PATCH')
            @if($errors->has('mileage'))
                <p class="text-xs mb-2 flex items-center gap-1" style="color:#f87171;"><x-heroicon-o-exclamation-triangle class="w-3 h-3 flex-shrink-0" /> {{ $errors->first('mileage') }}</p>
            @endif
            <div class="flex gap-2">
                <input type="number" name="mileage"
                       placeholder="{{ __('app.mileage_placeholder', ['min' => number_format($vehicle->mileage)]) }}"
                       min="{{ $vehicle->mileage }}"
                       class="flex-1 px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,107,0,0.2);">
                <button type="submit"
                        class="px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,107,0,0.15);border:1px solid rgba(255,107,0,0.3);color:#ff6b00;">
                    {{ __('app.update_btn') }}
                </button>
            </div>
        </form>
    </div>

    {{-- ── Vehicle Notes ─────────────────────────────────────────── --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(0,245,255,0.12);">
        <p class="section-label mb-2">{{ __('app.field_vehicle_notes') }}</p>
        <form method="POST" action="{{ route('vehicles.updateNotes', $vehicle) }}">
            @csrf @method('PATCH')
            <textarea name="notes" rows="2"
                      placeholder="{{ __('app.vehicle_notes_ph') }}"
                      class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none mb-2"
                      style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.12);">{{ $vehicle->notes }}</textarea>
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                {{ __('app.update_notes_btn') }}
            </button>
        </form>
    </div>

    {{-- ── Quick Actions ─────────────────────────────────────────── --}}
    <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.quick_actions_label') }}</p>
    <div class="grid grid-cols-2 gap-3 mb-4 fade-in fade-in-3">
        <a href="{{ route('qrcode.show', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,245,255,0.12);">
            <x-heroicon-o-qr-code class="w-6 h-6" style="color:var(--cyan);" />
            <p class="heading text-xs font-bold text-white tracking-wider">{{ __('app.action_qr_code') }}</p>
        </a>
        <a href="{{ route('suggestions.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(168,85,247,0.2);">
            <x-heroicon-o-light-bulb class="w-6 h-6" style="color:#a855f7;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#a855f7;">{{ __('app.action_suggestions') }}</p>
        </a>
        <a href="{{ route('service.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(74,222,128,0.2);">
            <x-heroicon-o-wrench-screwdriver class="w-6 h-6" style="color:#4ade80;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#4ade80;">{{ __('app.action_service') }}</p>
        </a>
        <a href="{{ route('fuel.index', $vehicle) }}"
           class="glass-bright rounded-2xl p-4 border flex flex-col items-center gap-2 transition-all active:scale-95"
           style="border-color:rgba(0,102,255,0.2);">
            <x-heroicon-o-beaker class="w-6 h-6" style="color:#6699ff;" />
            <p class="heading text-xs font-bold tracking-wider" style="color:#6699ff;">{{ __('app.action_fuel_logs') }}</p>
        </a>
    </div>

    {{-- Book a Service --}}
    <a href="{{ route('garages.index') }}"
       class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl font-semibold heading tracking-widest text-sm transition-all active:scale-95 mb-4 fade-in fade-in-4"
       style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
        <x-heroicon-o-calendar-days class="w-5 h-5" />
        {{ __('app.book_service_btn') }}
    </a>

    {{-- Export PDF --}}
    <a href="{{ route('vehicles.export', $vehicle) }}"
       class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl font-semibold heading tracking-widest text-sm transition-all active:scale-95 mb-4 fade-in fade-in-4"
       style="background:rgba(0,102,255,0.1);border:1px solid rgba(0,102,255,0.25);color:#6699ff;">
        <x-heroicon-o-arrow-down-tray class="w-4 h-4" />
        {{ __('app.export_pdf_btn') }}
    </a>

    {{-- ── Archive Vehicle ───────────────────────────────────────── --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-4"
         style="border-color:rgba(248,113,113,0.12);">
        <p class="section-label mb-1" style="color:rgba(248,113,113,0.5);">{{ __('app.archive_vehicle_btn') }}</p>
        <p class="text-xs mb-3" style="color:#475569;">{{ __('app.vehicle_archived') }}</p>
        <form method="POST" action="{{ route('vehicles.destroy', $vehicle) }}"
              onsubmit="return confirm('{{ __('app.archive_confirm', ['name' => $vehicle->make . ' ' . $vehicle->model]) }}')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                <x-heroicon-o-archive-box class="w-3.5 h-3.5" />
                {{ __('app.archive_vehicle_btn') }}
            </button>
        </form>
    </div>

    {{-- Back --}}
    <a href="{{ route('vehicles.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-4"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        {{ __('app.back_to_my_vehicles') }}
    </a>
</div>
</x-app-layout>
