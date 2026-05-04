<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
        <a href="{{ route('vehicles.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_vehicles') }}</a>
        <span>›</span>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ $vehicle->make }} {{ $vehicle->model }}</a>
        <span>›</span>
        <span style="color:#94a3b8;">{{ __('app.qr_history_label') }}</span>
    </nav>

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">{{ __('app.qr_history_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.qr_code_title') }}
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">
            {{ $vehicle->make }} {{ $vehicle->model }} · {{ $vehicle->year }}
        </p>
    </div>

    {{-- QR Card --}}
    <div class="glass-bright rounded-2xl p-6 mb-4 border text-center fade-in fade-in-2 animate-glow"
         style="border-color:rgba(0,245,255,0.15);">

        <p class="section-label mb-4">{{ __('app.scan_label') }}</p>

        {{-- QR Code --}}
        <div id="qr-container"
             class="inline-flex items-center justify-center rounded-2xl p-4 mb-4"
             style="background:white;box-shadow:0 0 40px rgba(0,245,255,0.2);width:220px;height:220px;">
            <div style="width:180px;height:180px;">
                {!! $qrCode !!}
            </div>
        </div>

        {{-- Info badges --}}
        <div class="flex justify-center gap-2 mb-4">
            <span class="tag" style="background:rgba(0,245,255,0.1);color:var(--cyan);border:1px solid rgba(0,245,255,0.2);">
                <x-heroicon-o-check class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.no_login_tag') }}
            </span>
            <span class="tag" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.2);">
                <x-heroicon-o-check class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.public_access_tag') }}
            </span>
        </div>

        <p class="text-xs" style="color:#64748b;">
            {{ __('app.qr_scan_desc') }}
        </p>

        {{-- Download QR button --}}
        <button onclick="downloadQR()"
                class="mt-4 flex items-center justify-center gap-2 w-full py-3 rounded-xl font-semibold heading tracking-wider text-sm transition-all active:scale-95"
                style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
            <x-heroicon-o-arrow-down-tray class="w-4 h-4" />{{ __('app.download_qr_btn') }}
        </button>
    </div>

    {{-- Public link --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(0,245,255,0.1);">
        <p class="section-label mb-2">{{ __('app.public_link_label') }}</p>
        <div class="rounded-xl p-3 mb-3 break-all"
             style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
            <p class="mono text-xs" style="color:var(--cyan);" id="public-url">{{ $publicUrl }}</p>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <button onclick="copyLink()"
                    id="copy-btn"
                    class="flex items-center justify-center gap-2 py-3 rounded-xl font-semibold heading tracking-wider text-sm transition-all active:scale-95"
                    style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
                <x-heroicon-o-clipboard-document class="w-4 h-4" />
                <span id="copy-label">{{ __('app.copy_link_btn') }}</span>
            </button>
            <a href="{{ $publicUrl }}" target="_blank"
               class="flex items-center justify-center gap-2 py-3 rounded-xl font-semibold heading tracking-wider text-sm transition-all active:scale-95"
               style="background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);color:#4ade80;">
                <x-heroicon-o-eye class="w-4 h-4" />{{ __('app.preview_public_btn') }}
            </a>
        </div>
    </div>

    {{-- What visitors will see --}}
    @php
        $lastLog = $serviceLogs->first();
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-4"
         style="border-color:rgba(255,255,255,0.08);">
        <p class="section-label mb-3">{{ __('app.visitors_see_label') }}</p>
        <div class="flex flex-col gap-2">

            {{-- Photo --}}
            <div class="flex items-center justify-between py-2 px-3 rounded-xl"
                 style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-2 text-sm" style="color:#94a3b8;">
                    <x-heroicon-o-photo class="w-4 h-4 flex-shrink-0" />
                    {{ $vehicle->image ? __('app.visitors_photo_yes') : __('app.visitors_photo_no') }}
                </div>
                @if($vehicle->image)
                    <x-heroicon-o-check-circle class="w-4 h-4 flex-shrink-0" style="color:#4ade80;" />
                @else
                    <x-heroicon-o-x-circle class="w-4 h-4 flex-shrink-0" style="color:#475569;" />
                @endif
            </div>

            {{-- Service records --}}
            <div class="flex items-center justify-between py-2 px-3 rounded-xl"
                 style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-2 text-sm" style="color:#94a3b8;">
                    <x-heroicon-o-wrench-screwdriver class="w-4 h-4 flex-shrink-0" />
                    @if($serviceLogs->count() > 0)
                        {{ __('app.visitors_services', ['count' => $serviceLogs->count()]) }}
                    @else
                        {{ __('app.visitors_no_svc') }}
                    @endif
                </div>
                @if($serviceLogs->count() > 0)
                    <span class="mono text-xs" style="color:var(--cyan);">{{ $serviceLogs->count() }}</span>
                @else
                    <x-heroicon-o-x-circle class="w-4 h-4 flex-shrink-0" style="color:#475569;" />
                @endif
            </div>

            {{-- Last serviced --}}
            @if($lastLog)
            <div class="flex items-center justify-between py-2 px-3 rounded-xl"
                 style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-2 text-sm" style="color:#94a3b8;">
                    <x-heroicon-o-calendar class="w-4 h-4 flex-shrink-0" />
                    {{ __('app.visitors_last_svc', ['date' => $lastLog->service_date->format('d M Y')]) }}
                </div>
                <x-heroicon-o-check-circle class="w-4 h-4 flex-shrink-0" style="color:#4ade80;" />
            </div>
            @endif

            {{-- VIN --}}
            <div class="flex items-center justify-between py-2 px-3 rounded-xl"
                 style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                <div class="flex items-center gap-2 text-sm" style="color:#94a3b8;">
                    <x-heroicon-o-identification class="w-4 h-4 flex-shrink-0" />
                    {{ $vehicle->vin ? __('app.visitors_vin_yes') : __('app.visitors_vin_no') }}
                </div>
                @if($vehicle->vin)
                    <x-heroicon-o-check-circle class="w-4 h-4 flex-shrink-0" style="color:#4ade80;" />
                @else
                    <x-heroicon-o-x-circle class="w-4 h-4 flex-shrink-0" style="color:#475569;" />
                @endif
            </div>

        </div>
    </div>

    {{-- Privacy note --}}
    <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-5"
         style="background:rgba(255,107,0,0.05);border-color:rgba(255,107,0,0.15);">
        <p class="text-xs" style="color:rgba(255,107,0,0.7);">
            <x-heroicon-o-lock-closed class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.qr_privacy_note') }}
        </p>
    </div>

    {{-- Back --}}
    <a href="{{ route('vehicles.show', $vehicle) }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-5"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        ← {{ $vehicle->make }} {{ $vehicle->model }}
    </a>

</div>

<script>
function copyLink() {
    const url = document.getElementById('public-url').textContent.trim();
    const btn = document.getElementById('copy-btn');
    const label = document.getElementById('copy-label');
    const original = label.textContent;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => showCopied());
    } else {
        const el = document.createElement('textarea');
        el.value = url;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showCopied();
    }

    function showCopied() {
        label.textContent = '{{ __("app.link_copied") }}';
        btn.style.background = 'rgba(74,222,128,0.1)';
        btn.style.borderColor = 'rgba(74,222,128,0.3)';
        btn.style.color = '#4ade80';
        setTimeout(() => {
            label.textContent = original;
            btn.style.background = 'rgba(0,245,255,0.08)';
            btn.style.borderColor = 'rgba(0,245,255,0.2)';
            btn.style.color = 'var(--cyan)';
        }, 2000);
    }
}

function downloadQR() {
    const container = document.getElementById('qr-container');
    const svg = container.querySelector('svg');
    if (!svg) return;

    const svgData = new XMLSerializer().serializeToString(svg);
    const canvas = document.createElement('canvas');
    canvas.width = 400;
    canvas.height = 400;
    const ctx = canvas.getContext('2d');
    const img = new Image();

    img.onload = function () {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, 400, 400);
        ctx.drawImage(img, 10, 10, 380, 380);
        const a = document.createElement('a');
        a.href = canvas.toDataURL('image/png');
        a.download = 'automatex-qr-{{ Str::slug($vehicle->make . "-" . $vehicle->model) }}.png';
        a.click();
    };

    img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
}
</script>

</x-app-layout>
