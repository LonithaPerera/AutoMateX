<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-8">

    <div class="mb-5 fade-in fade-in-1">
        <nav class="flex items-center gap-1.5 text-xs mb-3 fade-in" style="color:#64748b;">
            <a href="{{ route('garages.index') }}" class="transition-colors hover:text-white" style="color:#64748b;">{{ __('app.nav_garages') }}</a>
            <span>›</span>
            <span style="color:#94a3b8;">{{ $garage->name }}</span>
            <span>›</span>
            <span style="color:#94a3b8;">{{ __('app.new_booking_title') }}</span>
        </nav>
        <p class="section-label mb-1">{{ __('app.book_appt_label') }}</p>
        <h1 class="heading text-3xl font-bold text-white">
            {{ __('app.new_booking_title') }}
        </h1>
        <p class="text-xs mono mt-1" style="color:#64748b;">{{ $garage->name }} · {{ $garage->city }}</p>
    </div>

    {{-- Error flash --}}
    @if(session('error'))
    <div class="rounded-2xl p-3 mb-4 border fade-in" style="background:rgba(248,113,113,0.08);border-color:rgba(248,113,113,0.2);">
        <x-heroicon-o-x-circle class="w-4 h-4 inline-block mr-1" style="color:#f87171;" />
        <span class="text-sm" style="color:#f87171;">{{ session('error') }}</span>
    </div>
    @endif

    <div class="glass-bright rounded-2xl p-5 fade-in fade-in-2 border animate-glow">
        <form method="POST" action="{{ route('bookings.store', $garage) }}">
            @csrf

            {{-- Vehicle --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_select_vehicle') }}</label>
                <select name="vehicle_id" required
                        class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                        style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('vehicle_id') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                    <option value="">{{ __('app.choose_vehicle') }}</option>
                    @foreach($vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                        {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }} — {{ $vehicle->license_plate }}
                    </option>
                    @endforeach
                </select>
                @error('vehicle_id')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            {{-- Service type --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_service_type') }}</label>
                <input type="text" name="service_type" value="{{ old('service_type') }}" required
                       placeholder="{{ __('app.ph_booking_service') }}"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('service_type') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                @error('service_type')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            {{-- Date --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_pref_date') }}</label>
                <input type="date" name="booking_date" id="booking-date"
                       value="{{ old('booking_date', date('Y-m-d', strtotime('+1 day'))) }}"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                       oninput="generateSlots()"
                       class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
            </div>

            {{-- Time Slot Picker --}}
            <div class="mb-4">
                <label class="section-label mb-2 block">{{ __('app.field_pref_time') }}</label>
                <input type="hidden" name="booking_time" id="booking-time-hidden" value="{{ old('booking_time', '') }}" required>
                <div id="time-slot-grid" class="flex flex-wrap gap-2 min-h-[40px]"></div>
                <p id="slot-closed-msg" class="text-xs hidden mt-1" style="color:#f87171;">{{ __('app.slot_closed_day') }}</p>
                <p id="slot-no-hours-msg" class="text-xs hidden mt-1" style="color:#94a3b8;">{{ __('app.slot_no_hours') }}</p>
            </div>

            @php
                $workingHoursJson = $garage->working_hours ? json_encode($garage->working_hours) : 'null';
            @endphp
            <script>
            const garageHours = {!! $workingHoursJson !!};
            const dayKeys = ['sun','mon','tue','wed','thu','fri','sat'];

            function generateSlots() {
                const dateVal = document.getElementById('booking-date').value;
                const grid    = document.getElementById('time-slot-grid');
                const closedMsg  = document.getElementById('slot-closed-msg');
                const noHoursMsg = document.getElementById('slot-no-hours-msg');
                grid.innerHTML = '';
                closedMsg.classList.add('hidden');
                noHoursMsg.classList.add('hidden');

                if (!dateVal) return;

                const dayKey = dayKeys[new Date(dateVal + 'T00:00:00').getDay()];

                if (!garageHours) {
                    noHoursMsg.classList.remove('hidden');
                    // Fallback: show a plain time input
                    const inp = document.createElement('input');
                    inp.type = 'time'; inp.className = 'w-full px-4 py-2.5 rounded-xl text-sm text-white outline-none';
                    inp.style.cssText = 'background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;';
                    inp.value = document.getElementById('booking-time-hidden').value || '09:00';
                    inp.oninput = () => { document.getElementById('booking-time-hidden').value = inp.value; };
                    grid.appendChild(inp);
                    return;
                }

                const dayData = garageHours[dayKey];
                if (!dayData || dayData.closed) {
                    closedMsg.classList.remove('hidden');
                    return;
                }

                const [openH, openM]   = dayData.open.split(':').map(Number);
                const [closeH, closeM] = dayData.close.split(':').map(Number);
                const openMins  = openH * 60 + openM;
                const closeMins = closeH * 60 + closeM;
                const currentVal = document.getElementById('booking-time-hidden').value;

                for (let m = openMins; m < closeMins; m += 30) {
                    const hh  = String(Math.floor(m / 60)).padStart(2, '0');
                    const mm  = String(m % 60).padStart(2, '0');
                    const val = hh + ':' + mm;
                    const lbl = (m % 720 < 60 && m % 720 >= 0 && Math.floor(m/60) === 12) ? '12:' + mm + ' PM'
                              : (Math.floor(m / 60) >= 12 ? (Math.floor(m/60) === 12 ? '12' : String(Math.floor(m/60)-12)) + ':' + mm + ' PM'
                                                           : String(Math.floor(m/60)) + ':' + mm + ' AM');

                    const isSelected = val === currentVal;
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = val;
                    btn.dataset.val = val;
                    btn.className = 'slot-btn px-3 py-1.5 rounded-lg text-xs font-semibold mono transition-all active:scale-95';
                    btn.style.cssText = isSelected
                        ? 'background:rgba(0,245,255,0.2);border:1px solid rgba(0,245,255,0.5);color:#00f5ff;'
                        : 'background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:#94a3b8;';
                    btn.onclick = function() {
                        document.querySelectorAll('.slot-btn').forEach(b => {
                            b.style.cssText = 'background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.1);color:#94a3b8;';
                        });
                        this.style.cssText = 'background:rgba(0,245,255,0.2);border:1px solid rgba(0,245,255,0.5);color:#00f5ff;';
                        document.getElementById('booking-time-hidden').value = this.dataset.val;
                    };
                    grid.appendChild(btn);
                }

                if (!grid.children.length) {
                    noHoursMsg.classList.remove('hidden');
                }
            }
            document.addEventListener('DOMContentLoaded', generateSlots);

            // Prevent submission if no slot selected; enable loading state only on success
            document.querySelector('form').addEventListener('submit', function(e) {
                const timeVal = document.getElementById('booking-time-hidden').value;
                if (!timeVal) {
                    e.preventDefault();
                    const msg = document.getElementById('slot-no-hours-msg');
                    msg.textContent = '{{ __('app.slot_required') }}';
                    msg.classList.remove('hidden');
                    msg.style.color = '#f87171';
                    return;
                }
                // Validation passed — show loading state
                const btn = document.getElementById('booking-submit-btn');
                btn.disabled = true;
                btn.innerHTML = '&bull;&bull;&bull;';
            });
            </script>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_notes') }}</label>
                <textarea name="notes" rows="3"
                          placeholder="{{ __('app.ph_booking_notes') }}"
                          class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none resize-none"
                          style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">{{ old('notes') }}</textarea>
            </div>

            {{-- Garage info + working hours --}}
            <div class="rounded-xl p-3 mb-4" style="background:rgba(0,245,255,0.05);border:1px solid rgba(0,245,255,0.12);">
                <p class="text-xs mb-1 section-label">{{ __('app.booking_at_label') }}</p>
                <p class="text-sm font-semibold text-white">{{ $garage->name }}</p>
                <p class="text-xs mb-2" style="color:#64748b;">
                    {{ $garage->address }} · {{ $garage->city }}
                    @if($garage->phone) · {{ $garage->phone }} @endif
                </p>

                {{-- Notice when no working hours set --}}
                @if(!$garage->working_hours)
                <div class="flex items-center gap-2 mb-2 rounded-lg px-2.5 py-2" style="background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.18);">
                    <x-heroicon-o-clock class="w-3.5 h-3.5 flex-shrink-0" style="color:#fbbf24;" />
                    <p class="text-xs" style="color:#fbbf24;">{{ __('app.booking_no_hours_notice') }}</p>
                </div>
                @endif

                {{-- #3 Working hours --}}
                @if($garage->working_hours)
                @php
                    $days = ['mon','tue','wed','thu','fri','sat','sun'];
                    $dayLabels = ['mon'=>'Mon','tue'=>'Tue','wed'=>'Wed','thu'=>'Thu','fri'=>'Fri','sat'=>'Sat','sun'=>'Sun'];
                    $todayKey = $days[\Carbon\Carbon::now()->dayOfWeekIso - 1] ?? null;
                @endphp
                <p class="text-xs section-label mb-1.5">{{ __('app.working_hours_label') }}</p>
                <div class="grid grid-cols-4 gap-1">
                @foreach($days as $day)
                @php
                    $dh = $garage->working_hours[$day] ?? null;
                    $isClosed = $dh['closed'] ?? true;
                    $isToday  = $day === $todayKey;
                @endphp
                <div class="rounded-lg px-1.5 py-1 text-center {{ $isToday ? 'ring-1' : '' }}"
                     style="background:{{ $isClosed ? 'rgba(248,113,113,0.06)' : 'rgba(74,222,128,0.06)' }};{{ $isToday ? 'ring-color:rgba(0,245,255,0.4);' : '' }}border:1px solid {{ $isClosed ? 'rgba(248,113,113,0.15)' : 'rgba(74,222,128,0.15)' }};">
                    <p class="text-xs font-bold heading" style="color:{{ $isToday ? '#00f5ff' : '#94a3b8' }};">{{ $dayLabels[$day] }}</p>
                    @if($isClosed)
                        <p class="mono" style="font-size:8px;color:#f87171;">CLOSED</p>
                    @else
                        <p class="mono" style="font-size:7px;color:#4ade80;line-height:1.4;">{{ $dh['open'] ?? '' }}<br>{{ $dh['close'] ?? '' }}</p>
                    @endif
                </div>
                @endforeach
                </div>
                @endif
            </div>

            {{-- #6 Submit with loading state --}}
            <button type="submit" id="booking-submit-btn"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.confirm_booking_btn') }}
            </button>

            <a href="{{ route('garages.show', $garage) }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>