<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.system_control_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">{{ __('app.admin_schedules_title') }}</h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ __('app.admin_schedules_desc') }}</p>
        </div>
        <a href="{{ route('admin.garages') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1 flex-shrink-0"
           style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.15);color:#64748b;">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('app.admin_nav_garages') }}
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" />
            <span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(248,113,113,0.06);border-color:rgba(248,113,113,0.2);">
            <span class="text-sm" style="color:#f87171;">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Add new rule --}}
    <p class="section-label mb-3 fade-in fade-in-2">{{ __('app.admin_add_schedule_btn') }}</p>
    <div class="glass-bright rounded-2xl p-4 mb-5 border fade-in fade-in-2" style="border-color:rgba(168,85,247,0.2);">
        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3 mb-3">
                <div class="col-span-2">
                    <label class="section-label mb-1 block">{{ __('app.field_service_type') }}</label>
                    <input type="text" name="service_name" value="{{ old('service_name') }}" required
                           placeholder="{{ __('app.admin_sched_name_ph') }}"
                           class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                </div>
                <div>
                    <label class="section-label mb-1 block">{{ __('app.every_km', ['km' => 'km']) }}</label>
                    <input type="number" name="interval_km" value="{{ old('interval_km') }}" required min="100"
                           placeholder="{{ __('app.admin_sched_interval_ph') }}"
                           class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none mono"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                </div>
                <div>
                    <label class="section-label mb-1 block">{{ __('app.field_category') }}</label>
                    <select name="category" required
                            class="w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none"
                            style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                        @foreach(['maintenance','repair','inspection'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}
                                style="background:#0d1421;">{{ __('app.cat_' . $cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="section-label mb-1 block">{{ __('app.field_notes') }}</label>
                    <textarea name="description" rows="2"
                              placeholder="{{ __('app.admin_sched_desc_ph') }}"
                              class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                              style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">{{ old('description') }}</textarea>
                </div>
            </div>
            <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold heading tracking-widest transition-all active:scale-95"
                    style="background:linear-gradient(135deg,rgba(168,85,247,0.3),rgba(168,85,247,0.15));border:1px solid rgba(168,85,247,0.4);color:#a855f7;">
                <x-heroicon-o-plus class="w-4 h-4 inline-block mr-1 align-middle" />{{ __('app.admin_add_schedule_btn') }}
            </button>
        </form>
    </div>

    {{-- Existing rules --}}
    <p class="section-label mb-3 fade-in fade-in-3">{{ __('app.maintenance_sched_label') }} ({{ $schedules->count() }})</p>

    @forelse($schedules as $index => $schedule)
    @php
        $catColor = match($schedule->category) {
            'repair'      => ['bg'=>'rgba(248,113,113,0.08)','color'=>'#f87171','border'=>'rgba(248,113,113,0.2)'],
            'inspection'  => ['bg'=>'rgba(251,191,36,0.08)','color'=>'#fbbf24','border'=>'rgba(251,191,36,0.2)'],
            default       => ['bg'=>'rgba(0,245,255,0.08)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+3,5) }}"
         style="border-color:rgba(168,85,247,0.1);" id="sched-{{ $schedule->id }}">

        {{-- View mode --}}
        <div id="view-{{ $schedule->id }}">
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1 min-w-0">
                    <h3 class="heading font-bold text-white text-sm leading-tight">{{ $schedule->service_name }}</h3>
                    <p class="text-xs mt-0.5" style="color:#64748b;">
                        {{ __('app.every_km', ['km' => number_format($schedule->interval_km)]) }}
                    </p>
                    @if($schedule->description)
                    <p class="text-xs mt-1 leading-relaxed" style="color:#475569;">{{ $schedule->description }}</p>
                    @endif
                </div>
                <span class="tag flex-shrink-0 ml-2" style="background:{{ $catColor['bg'] }};color:{{ $catColor['color'] }};border:1px solid {{ $catColor['border'] }};">
                    {{ strtoupper($schedule->category) }}
                </span>
            </div>
            <div class="flex gap-2 mt-3">
                <button onclick="openEdit({{ $schedule->id }})"
                        class="flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);color:#a855f7;">
                    <x-heroicon-o-pencil-square class="w-3 h-3 inline-block mr-0.5 align-middle" />{{ __('app.edit_log_btn') }}
                </button>
                <button type="button"
                        onclick="openSchedDelete({{ $schedule->id }}, '{{ addslashes($schedule->service_name) }}')"
                        class="px-4 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(248,113,113,0.06);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                    <x-heroicon-o-trash class="w-3 h-3 inline-block align-middle" />
                </button>
            </div>
        </div>

        {{-- Edit mode (hidden by default) --}}
        <div id="edit-{{ $schedule->id }}" style="display:none;">
            <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
                @csrf @method('PATCH')
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div class="col-span-2">
                        <label class="section-label mb-1 block">{{ __('app.field_service_type') }}</label>
                        <input type="text" name="service_name" value="{{ $schedule->service_name }}" required
                               class="w-full px-3 py-2 rounded-xl text-sm text-white outline-none"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                    </div>
                    <div>
                        <label class="section-label mb-1 block">Interval (km)</label>
                        <input type="number" name="interval_km" value="{{ $schedule->interval_km }}" required min="100"
                               class="w-full px-3 py-2 rounded-xl text-sm text-white outline-none mono"
                               style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                    </div>
                    <div>
                        <label class="section-label mb-1 block">{{ __('app.field_category') }}</label>
                        <select name="category" required
                                class="w-full px-3 py-2 rounded-xl text-sm text-white outline-none"
                                style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">
                            @foreach(['maintenance','repair','inspection'] as $cat)
                            <option value="{{ $cat }}" {{ $schedule->category === $cat ? 'selected' : '' }}
                                    style="background:#0d1421;">{{ __('app.cat_' . $cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="section-label mb-1 block">{{ __('app.field_notes') }}</label>
                        <textarea name="description" rows="2"
                                  class="w-full px-3 py-2 rounded-xl text-sm text-white outline-none resize-none"
                                  style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.2);">{{ $schedule->description }}</textarea>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeEdit({{ $schedule->id }})"
                            class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider"
                            style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);color:#64748b;">
                        {{ __('app.cancel') }}
                    </button>
                    <button type="submit"
                            class="flex-1 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                            style="background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.3);color:#a855f7;">
                        {{ __('app.update_btn') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @empty
    <div class="rounded-2xl p-8 text-center border fade-in fade-in-3" style="background:rgba(255,255,255,0.02);border-color:rgba(255,255,255,0.06);">
        <x-heroicon-o-light-bulb class="w-10 h-10 mx-auto mb-3" style="color:#334155;" />
        <p class="heading font-bold text-white">No schedule rules yet.</p>
    </div>
    @endforelse

</div>

{{-- Delete confirmation modal --}}
<div id="sched-delete-modal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-sm rounded-2xl p-6 border" style="background:#0d1421;border-color:rgba(248,113,113,0.3);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);">
                    <x-heroicon-o-trash class="w-5 h-5" style="color:#f87171;" />
                </div>
                <div>
                    <p class="heading font-bold text-white">{{ __('app.admin_delete_schedule_title') }}</p>
                    <p id="sched-modal-name" class="text-xs" style="color:#64748b;"></p>
                </div>
            </div>
            <p class="text-sm mb-5" style="color:#94a3b8;">{{ __('app.admin_delete_schedule_confirm') }}</p>
            <div class="flex gap-2">
                <button onclick="closeSchedDelete()"
                        class="flex-1 py-2.5 rounded-xl text-xs font-semibold heading tracking-wider"
                        style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
                    {{ __('app.cancel') }}
                </button>
                <form id="sched-delete-form" method="POST" style="flex:1;" data-no-warn>
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                            style="background:rgba(248,113,113,0.15);border:1px solid rgba(248,113,113,0.4);color:#f87171;">
                        <x-heroicon-o-trash class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.admin_delete_rule_btn') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openEdit(id) {
    document.getElementById('view-' + id).style.display = 'none';
    document.getElementById('edit-' + id).style.display = 'block';
}
function closeEdit(id) {
    document.getElementById('edit-' + id).style.display = 'none';
    document.getElementById('view-' + id).style.display = 'block';
}
function openSchedDelete(id, name) {
    document.getElementById('sched-modal-name').textContent = name;
    document.getElementById('sched-delete-form').action = '/admin/schedules/' + id;
    document.getElementById('sched-delete-modal').style.display = 'block';
}
function closeSchedDelete() {
    document.getElementById('sched-delete-modal').style.display = 'none';
}
document.getElementById('sched-delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeSchedDelete();
});
</script>
</x-app-layout>
