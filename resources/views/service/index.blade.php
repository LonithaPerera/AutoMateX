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
        <div class="flex flex-col gap-2 items-end">
            <a href="{{ route('service.create', $vehicle) }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
                {{ __('app.log_btn') }}
            </a>
            @if($serviceLogs->isNotEmpty())
            <a href="{{ route('service.pdf', $vehicle) }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
               style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.25);color:#4ade80;">
                <x-heroicon-o-arrow-down-tray class="w-3.5 h-3.5" />{{ __('app.download_pdf_btn') }}
            </a>
            @endif
        </div>
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

    {{-- Search --}}
    <div class="relative mb-4 fade-in fade-in-2">
        <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#64748b;" />
        <input type="text" id="service-search"
               placeholder="{{ __('app.search_service_logs_ph') }}"
               oninput="filterServiceLogs()"
               class="w-full pl-10 pr-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.12);">
    </div>

    {{-- Service log list --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.log_entries_label') }}</p>

    @forelse($serviceLogsPaged as $index => $log)
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }} service-log-card"
         data-search="{{ strtolower($log->service_type . ' ' . $log->service_date->format('d M Y') . ' ' . ($log->garage_name ?? '') . ' ' . ($log->type ?? '')) }}"
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

        {{-- Edit + Delete --}}
        <div class="flex gap-2">
            <a href="{{ route('service.edit', [$vehicle, $log]) }}"
               class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
               style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                {{ __('app.edit_log_btn') }}
            </a>
            <form method="POST" action="{{ route('service.destroy', [$vehicle, $log]) }}"
                  onsubmit="return confirm('{{ __('app.delete_service_confirm') }}')" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,60,60,0.06);border:1px solid rgba(255,60,60,0.15);color:#f87171;">
                    {{ __('app.delete_record_btn') }}
                </button>
            </form>
        </div>
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

    {{-- Pagination --}}
    @if($serviceLogsPaged->hasPages())
    <div class="mt-2 mb-4 flex items-center justify-center gap-2">
        @if($serviceLogsPaged->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs" style="background:rgba(255,255,255,0.03);color:#334155;border:1px solid rgba(255,255,255,0.06);">{{ __('app.pagination_prev') }}</span>
        @else
            <a href="{{ $serviceLogsPaged->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs transition-all" style="background:rgba(0,245,255,0.06);color:var(--cyan);border:1px solid rgba(0,245,255,0.2);">{{ __('app.pagination_prev') }}</a>
        @endif
        <span class="mono text-xs" style="color:#64748b;">{{ $serviceLogsPaged->currentPage() }} / {{ $serviceLogsPaged->lastPage() }}</span>
        @if($serviceLogsPaged->hasMorePages())
            <a href="{{ $serviceLogsPaged->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs transition-all" style="background:rgba(0,245,255,0.06);color:var(--cyan);border:1px solid rgba(0,245,255,0.2);">{{ __('app.pagination_next') }}</a>
        @else
            <span class="px-3 py-1.5 rounded-lg text-xs" style="background:rgba(255,255,255,0.03);color:#334155;border:1px solid rgba(255,255,255,0.06);">{{ __('app.pagination_next') }}</span>
        @endif
    </div>
    @endif

    {{-- Back --}}
    <div class="mt-2">
        <a href="{{ route('vehicles.show', $vehicle) }}"
           class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl"
           style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
            {{ __('app.back_to_vehicles') }}
        </a>
    </div>

</div>
<script>
function filterServiceLogs() {
    const q = document.getElementById('service-search').value.toLowerCase().trim();
    document.querySelectorAll('.service-log-card').forEach(c => {
        c.style.display = (!q || c.dataset.search.includes(q)) ? '' : 'none';
    });
}
</script>
</x-app-layout>
