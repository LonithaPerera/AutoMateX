<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ __('app.service_history_label') }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.service_history_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ $vehicle->make }} <span class="text-cyan">{{ $vehicle->model }}</span>
            </h1>
            <p class="text-xs mono mt-0.5" style="color:#64748b;">
                {{ $vehicle->year }} · {{ number_format($vehicle->mileage) }} km
            </p>
        </div>
        <a href="{{ route('service.create', $vehicle) }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            {{ __('app.log_btn') }}
        </a>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-3 gap-3 mb-5 fade-in fade-in-2">
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,245,255,0.05);border-color:rgba(0,245,255,0.15);">
            <p class="heading text-2xl font-bold text-cyan">{{ $serviceLogs->count() }}</p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.total') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(0,102,255,0.05);border-color:rgba(0,102,255,0.15);">
            <p class="heading text-2xl font-bold" style="color:#6699ff;">
                LKR {{ number_format($serviceLogs->sum('cost')) }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.total_spent') }}</p>
        </div>
        <div class="rounded-2xl p-3 text-center border" style="background:rgba(74,222,128,0.05);border-color:rgba(74,222,128,0.15);">
            <p class="heading text-xl font-bold" style="color:#4ade80;">
                {{ $serviceLogs->first() ? $serviceLogs->first()->service_date->format('M Y') : '—' }}
            </p>
            <p class="text-xs mt-0.5" style="color:#64748b;">{{ __('app.last_service') }}</p>
        </div>
    </div>

    {{-- Success message --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-1"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" /><span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Service log list --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.log_entries_label') }}</p>

    @forelse($serviceLogs as $index => $log)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(0,245,255,0.1);">

        {{-- Top row --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-start gap-3">
                <div class="rounded-xl p-2 mt-0.5" style="background:rgba(0,245,255,0.1);">
                    <x-heroicon-o-wrench-screwdriver class="w-4 h-4" style="color:#00f5ff;" />
                </div>
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $log->service_type }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ $log->service_date->format('d M Y') }}
                        @if($log->garage_name) · {{ $log->garage_name }} @endif
                    </p>
                </div>
            </div>
            @if($log->type)
            <span class="tag" style="background:rgba(0,245,255,0.08);color:rgba(0,245,255,0.7);border:1px solid rgba(0,245,255,0.15);">
                {{ strtoupper($log->type) }}
            </span>
            @endif
        </div>

        {{-- Details row --}}
        <div class="grid grid-cols-2 gap-2 mb-3">
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.mileage') }}</p>
                <p class="mono text-sm font-bold text-white">{{ number_format($log->mileage_at_service) }} km</p>
            </div>
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                <p class="text-xs mb-0.5" style="color:#64748b;">{{ __('app.cost') }}</p>
                <p class="mono text-sm font-bold" style="color:#4ade80;">LKR {{ number_format($log->cost) }}</p>
            </div>
        </div>

        @if($log->notes)
        <div class="rounded-xl p-2.5 mb-3" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#64748b;">{{ $log->notes }}</p>
        </div>
        @endif

        {{-- Delete --}}
        <form method="POST" action="{{ route('service.destroy', [$vehicle, $log]) }}"
              onsubmit="return confirm('{{ __('app.delete_service_confirm') }}')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(255,60,60,0.06);border:1px solid rgba(255,60,60,0.15);color:#f87171;">
                {{ __('app.delete_record_btn') }}
            </button>
        </form>
    </div>
    @empty
        <div class="glass rounded-2xl p-10 text-center border fade-in fade-in-3"
             style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-wrench-screwdriver class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_service_records') }}</p>
            <p class="text-sm mb-5" style="color:#64748b;">{{ __('app.log_first_service') }}</p>
            <a href="{{ route('service.create', $vehicle) }}"
               class="inline-block px-6 py-3 rounded-xl text-sm font-semibold heading tracking-wider"
               style="background:rgba(0,245,255,0.12);border:1px solid rgba(0,245,255,0.25);color:var(--cyan);">
                {{ __('app.log_service_btn') }}
            </a>
        </div>
    @endforelse

    {{-- Back --}}
    <div class="mt-2">
        <a href="{{ route('vehicles.index') }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            {{ __('app.back_to_vehicles') }}
        </a>
    </div>

</div>
</x-app-layout>
