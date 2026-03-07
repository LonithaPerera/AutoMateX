<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="mb-5 fade-in fade-in-1">
        <p class="section-label mb-1">// OEM DATABASE</p>
        <h1 class="heading text-3xl font-bold text-white">
            Parts <span class="text-cyan">Verification</span>
        </h1>
        <p class="text-xs mt-1" style="color:#64748b;">Verify OEM part numbers before purchase</p>
    </div>

    {{-- Search form --}}
    <div class="glass-bright rounded-2xl p-4 mb-5 border fade-in fade-in-2"
         style="border-color:rgba(0,245,255,0.12);">
        <form method="GET" action="{{ route('parts.index') }}">
            <p class="section-label mb-3">// SEARCH FILTERS</p>

            {{-- Keyword --}}
            <div class="mb-3">
                <label class="section-label mb-2 block">// keyword</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="e.g. Oil Filter, Brake Pad..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            {{-- Make & Model --}}
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="section-label mb-2 block">// vehicle make</label>
                    <input type="text" name="make" value="{{ request('make') }}"
                           placeholder="Toyota"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
                <div>
                    <label class="section-label mb-2 block">// vehicle model</label>
                    <input type="text" name="model" value="{{ request('model') }}"
                           placeholder="Premio"
                           class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
                </div>
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">// category</label>
                <input type="text" name="category" value="{{ request('category') }}"
                       placeholder="e.g. Filters, Brakes, Electrical..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 20px rgba(0,245,255,0.25);">
                🔍 SEARCH PARTS
            </button>

            @if(request()->hasAny(['search','make','model','category']))
            <a href="{{ route('parts.index') }}"
               class="block text-center mt-2 text-sm py-2" style="color:#64748b;">
                Clear filters
            </a>
            @endif
        </form>
    </div>

    {{-- Results --}}
    <p class="section-label mb-3 fade-in fade-in-3">
        // RESULTS
        @if(isset($parts))
            <span style="color:var(--cyan);">({{ $parts->count() }} found)</span>
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
                            · {{ $part->vehicle_year_from }}–{{ $part->vehicle_year_to ?? 'present' }}
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
                    <p class="text-xs mb-1" style="color:rgba(0,245,255,0.5);">// OEM PART NUMBER</p>
                    <p class="mono font-bold text-sm" style="color:var(--cyan);">{{ $part->oem_part_number }}</p>
                </div>
                @if($part->alternative_part_number)
                <div class="rounded-xl p-3" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);">
                    <p class="text-xs mb-1" style="color:#64748b;">// ALTERNATIVE NUMBER</p>
                    <p class="mono font-bold text-sm text-white">{{ $part->alternative_part_number }}</p>
                </div>
                @endif
            </div>

            {{-- Brand & description --}}
            @if($part->brand || $part->description)
            <div class="rounded-xl p-2.5" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
                @if($part->brand)
                <p class="text-xs mb-1">
                    <span style="color:#64748b;">Brand: </span>
                    <span class="text-white font-semibold">{{ $part->brand }}</span>
                </p>
                @endif
                @if($part->description)
                <p class="text-xs" style="color:#64748b;">{{ $part->description }}</p>
                @endif
            </div>
            @endif
        </div>
        @endforeach

    @elseif(request()->hasAny(['search','make','model','category']))
        <div class="glass rounded-2xl p-10 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <div class="text-5xl mb-4">🔍</div>
            <p class="heading text-xl font-bold text-white mb-1">No Parts Found</p>
            <p class="text-sm" style="color:#64748b;">Try different search terms</p>
        </div>

    @else
        <div class="glass rounded-2xl p-8 text-center border" style="border-color:rgba(255,255,255,0.06);">
            <div class="text-4xl mb-3">🔧</div>
            <p class="heading text-lg font-bold text-white mb-1">Search the Database</p>
            <p class="text-sm" style="color:#64748b;">
                Contains {{ \App\Models\Part::count() }} verified OEM part numbers
            </p>
        </div>
    @endif

</div>
</x-app-layout>