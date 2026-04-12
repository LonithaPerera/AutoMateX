<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

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
        <div class="inline-flex items-center justify-center rounded-2xl p-4 mb-4"
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
    </div>

    {{-- Public link --}}
    <div class="glass-bright rounded-2xl p-4 mb-4 border fade-in fade-in-3"
         style="border-color:rgba(0,245,255,0.1);">
        <p class="section-label mb-2">{{ __('app.public_link_label') }}</p>
        <div class="rounded-xl p-3 mb-3 break-all"
             style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
            <p class="mono text-xs" style="color:var(--cyan);">
                {{ url('/vehicle/public/' . $vehicle->qr_token) }}
            </p>
        </div>
        <a href="{{ url('/vehicle/public/' . $vehicle->qr_token) }}" target="_blank"
           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl font-semibold heading tracking-wider text-sm transition-all active:scale-95"
           style="background:rgba(0,245,255,0.1);border:1px solid rgba(0,245,255,0.2);color:var(--cyan);">
            <x-heroicon-o-eye class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.preview_public_btn') }}
        </a>
    </div>

    {{-- Privacy note --}}
    <div class="rounded-2xl p-3 mb-4 border fade-in fade-in-4"
         style="background:rgba(255,107,0,0.05);border-color:rgba(255,107,0,0.15);">
        <p class="text-xs" style="color:rgba(255,107,0,0.7);">
            <x-heroicon-o-lock-closed class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.qr_privacy_note') }}
        </p>
    </div>

    {{-- Back --}}
    <a href="{{ route('vehicles.index') }}"
       class="flex items-center gap-2 text-sm py-3 px-4 rounded-xl fade-in fade-in-5"
       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
        {{ __('app.back_to_vehicles') }}
    </a>

</div>
</x-app-layout>