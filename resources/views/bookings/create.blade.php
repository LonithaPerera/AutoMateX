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
                       placeholder="e.g. Full Service, Oil Change..."
                       class="w-full px-4 py-3 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
                       style="background:rgba(255,255,255,0.04);border:1px solid {{ $errors->has('service_type') ? 'rgba(248,113,113,0.5)' : 'rgba(0,245,255,0.15)' }};">
                @error('service_type')<p class="text-xs mt-1" style="color:#f87171;">{{ $message }}</p>@enderror
            </div>

            {{-- Date & Time --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_pref_date') }}</label>
                    <input type="date" name="booking_date"
                           value="{{ old('booking_date', date('Y-m-d', strtotime('+1 day'))) }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
                </div>
                <div>
                    <label class="section-label mb-2 block">{{ __('app.field_pref_time') }}</label>
                    <input type="time" name="booking_time"
                           value="{{ old('booking_time', '09:00') }}" required
                           class="w-full px-4 py-3 rounded-xl text-sm text-white outline-none"
                           style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);color-scheme:dark;">
                </div>
            </div>

            {{-- Notes --}}
            <div class="mb-6">
                <label class="section-label mb-2 block">{{ __('app.field_notes') }}</label>
                <textarea name="notes" rows="3"
                          placeholder="Describe the issue or any special requests..."
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
                    onclick="this.disabled=true;this.innerHTML='&bull;&bull;&bull;';this.form.submit();"
                    class="w-full py-3 rounded-xl font-semibold heading tracking-widest text-sm transition-all active:scale-95"
                    style="background:linear-gradient(135deg,#0066ff,#00f5ff);color:#080c14;box-shadow:0 0 24px rgba(0,245,255,0.3);">
                {{ __('app.confirm_booking_btn') }}
            </button>

            <a href="{{ route('garages.index') }}"
               class="block text-center mt-3 text-sm py-2" style="color:#64748b;">{{ __('app.cancel') }}</a>
        </form>
    </div>

</div>
</x-app-layout>