<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.oem_db_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.parts_title') }}
            </h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.verify_oem_hint') }}</p>
        </div>
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('parts.create') }}"
           class="flex items-center gap-1 px-4 py-2.5 rounded-xl text-sm font-semibold heading tracking-wider transition-all active:scale-95 flex-shrink-0"
           style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.3);">
            <x-heroicon-o-plus class="w-4 h-4" />{{ __('app.add_part_btn') }}
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="fade-in fade-in-1 rounded-2xl p-3 mb-4 flex items-center gap-3 border"
         style="background:rgba(74,222,128,0.06);border-color:rgba(74,222,128,0.2);">
        <x-heroicon-o-check-circle class="w-5 h-5 flex-shrink-0" style="color:#4ade80;" />
        <span class="text-sm" style="color:rgba(74,222,128,0.9);">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Search form --}}
    <div class="glass-bright rounded-2xl p-4 mb-5 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        <form method="GET" action="{{ route('parts.index') }}">
            <p class="section-label mb-3">{{ __('app.search_filters_label') }}</p>

            {{-- Keyword --}}
            <div class="mb-3">
                <label class="section-label mb-2 block">{{ __('app.field_keyword') }}</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="e.g. Oil Filter, Brake Pad..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            {{-- Make & Model --}}
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_make') }}</label>
                    <input type="text" name="make" value="{{ request('make') }}"
                           placeholder="Toyota"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_veh_model') }}</label>
                    <input type="text" name="model" value="{{ request('model') }}"
                           placeholder="Premio"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_category_p') }}</label>
                <input type="text" name="category" value="{{ request('category') }}"
                       placeholder="e.g. Filters, Brakes, Electrical..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.25);">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.search_parts_btn') }}
            </button>

            @if(request()->hasAny(['search','make','model','category']))
            <a href="{{ route('parts.index') }}"
               class="block text-center mt-2 text-sm py-2" style="color:#64748b;">
                {{ __('app.clear_filters') }}
            </a>
            @endif
        </form>
    </div>

    {{-- Results --}}
    <p class="section-label mb-3 fade-in fade-in-3">
        {{ __('app.results_label') }}
        @if(isset($parts))
            <span style="color:var(--cyan);">({{ $parts->count() }} {{ __('app.found_text') }})</span>
        @endif
    </p>

    @if(isset($parts) && $parts->count() > 0)
        @foreach($parts as $index => $part)
        <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
             style="border-color:rgba(0,245,255,0.1);">

            {{-- Header --}}
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="heading font-bold text-white text-base leading-tight">
                        {{ $part->part_name }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ $part->vehicle_make }} {{ $part->vehicle_model }}
                        @if($part->vehicle_year_from)
                            · {{ $part->vehicle_year_from }}–{{ $part->vehicle_year_to ?? __('app.present_text') }}
                        @endif
                    </p>
                </div>
                <span class="tag" style="background:rgba(0,245,255,0.08);color:rgba(0,245,255,0.7);border:1px solid rgba(0,245,255,0.15);">
                    {{ strtoupper($part->part_category ?? 'PART') }}
                </span>
            </div>

            {{-- OEM numbers --}}
            <div class="grid grid-cols-1 gap-2 mb-3">
                <div class="rounded-xl p-3" style="background:rgba(0,245,255,0.05);border:1px solid rgba(0,245,255,0.12);">
                    <p class="text-xs mb-1" style="color:rgba(0,245,255,0.5);">{{ __('app.oem_number_label') }}</p>
                    <p class="mono font-bold text-sm" style="color:var(--cyan);">{{ $part->oem_part_number }}</p>
                </div>
                @if($part->alternative_part_number)
                <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                    <p class="text-xs mb-1" style="color:#64748b;">{{ __('app.alt_number_label') }}</p>
                    <p class="mono font-bold text-sm text-white">{{ $part->alternative_part_number }}</p>
                </div>
                @endif
            </div>

            {{-- Brand & description --}}
            @if($part->brand || $part->description)
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                @if($part->brand)
                <p class="text-xs mb-1">
                    <span style="color:#64748b;">{{ __('app.brand_label') }} </span>
                    <span class="text-white font-semibold">{{ $part->brand }}</span>
                </p>
                @endif
                @if($part->description)
                <p class="text-xs" style="color:#64748b;">{{ $part->description }}</p>
                @endif
            </div>
            @endif

            {{-- Admin actions --}}
            @if(auth()->user()->role === 'admin')
            <div class="flex gap-2 mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.06);">
                <a href="{{ route('parts.edit', $part) }}"
                   class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider text-center transition-all active:scale-95"
                   style="background:rgba(0,245,255,0.08);border:1px solid rgba(0,245,255,0.15);color:var(--cyan);">
                    <x-heroicon-o-pencil-square class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.edit_part_btn') }}
                </a>
                <form method="POST" action="{{ route('parts.destroy', $part) }}"
                      onsubmit="return confirm('{{ __('app.confirm_delete_part') }}')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                            style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                        <x-heroicon-o-trash class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.delete_part_btn') }}
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach

    @elseif(request()->hasAny(['search','make','model','category']))
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-magnifying-glass class="w-12 h-12 mx-auto mb-4" style="color:#64748b;" />
            <p class="heading text-xl font-bold text-white mb-1">{{ __('app.no_parts_found') }}</p>
            <p class="text-sm" style="color:#64748b;">{{ __('app.try_diff_search') }}</p>
        </div>

    @else
        <div class="glass rounded-2xl p-8 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <x-heroicon-o-wrench-screwdriver class="w-10 h-10 mx-auto mb-3" style="color:#64748b;" />
            <p class="heading text-lg font-bold text-white mb-1">{{ __('app.search_db_hint') }}</p>
            <p class="text-sm" style="color:#64748b;">
                {{ __('app.contains_parts', ['count' => \App\Models\Part::count()]) }}
            </p>
        </div>
    @endif

</div>
</x-app-layout>