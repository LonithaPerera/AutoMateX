<x-app-layout>
<div class="max-w-lg mx-auto px-4 pt-5 pb-24">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 fade-in fade-in-1">
        <div>
            <p class="section-label mb-1">{{ __('app.user_mgmt_label') }}</p>
            <h1 class="heading text-3xl font-bold text-white">
                {{ __('app.all_users_title') }}
            </h1>
            <p class="text-xs mt-1" style="color:#64748b;">{{ $users->count() }} {{ __('app.registered_users') }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95 mt-1 flex-shrink-0"
           style="background:rgba(0,245,255,0.06);border:1px solid rgba(0,245,255,0.15);color:#64748b;">
            <x-heroicon-o-arrow-left class="w-3.5 h-3.5" />
            {{ __('app.admin_back_dashboard') }}
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="mb-4 fade-in fade-in-2 space-y-2">
        <input type="text" id="user-search"
               placeholder="{{ __('app.admin_search_ph') }}"
               oninput="filterUsers()"
               class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none"
               style="background:rgba(255,255,255,0.04);border:1px solid rgba(0,245,255,0.15);">
        <div class="flex gap-2">
            @foreach(['all','admin','garage','vehicle_owner'] as $role)
            <button onclick="setFilter('{{ $role }}')" id="filter-{{ $role }}"
                    class="filter-btn flex-1 py-1.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                    style="background:{{ $role === 'all' ? 'rgba(0,245,255,0.12)' : 'rgba(255,255,255,0.04)' }};border:1px solid {{ $role === 'all' ? 'rgba(0,245,255,0.3)' : 'rgba(255,255,255,0.08)' }};color:{{ $role === 'all' ? '#00f5ff' : '#64748b' }};">
                {{ $role === 'all' ? __('app.admin_filter_all') : strtoupper(str_replace('_',' ',$role)) }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Success / Error --}}
    @if(session('success'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(0,245,255,0.06);border-color:rgba(0,245,255,0.2);">
            <x-heroicon-o-check-circle class="w-4 h-4 inline-block mr-1" style="color:var(--cyan);" /><span class="text-sm" style="color:rgba(0,245,255,0.8);">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl p-3 mb-4 border fade-in"
             style="background:rgba(248,113,113,0.06);border-color:rgba(248,113,113,0.2);">
            <span class="text-sm flex items-center gap-1" style="color:#f87171;"><x-heroicon-o-x-circle class="w-4 h-4 flex-shrink-0" /> {{ session('error') }}</span>
        </div>
    @endif

    {{-- Users list --}}
    @foreach($users as $index => $user)
    @php
        $roleColor = match($user->role) {
            'admin'         => ['bg'=>'rgba(255,107,0,0.1)','color'=>'#ff6b00','border'=>'rgba(255,107,0,0.2)'],
            'garage'        => ['bg'=>'rgba(168,85,247,0.1)','color'=>'#a855f7','border'=>'rgba(168,85,247,0.2)'],
            'vehicle_owner' => ['bg'=>'rgba(0,245,255,0.1)','color'=>'#00f5ff','border'=>'rgba(0,245,255,0.2)'],
            default         => ['bg'=>'rgba(255,255,255,0.05)','color'=>'#94a3b8','border'=>'rgba(255,255,255,0.1)'],
        };
    @endphp
    <div class="glass-bright rounded-2xl p-4 mb-3 border fade-in fade-in-{{ min($index+2,5) }} user-card"
         data-role="{{ $user->role }}"
         data-name="{{ strtolower($user->name) }} {{ strtolower($user->email) }}"
         style="border-color:rgba(0,245,255,0.08);">

        {{-- User info --}}
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}"
                     alt="{{ $user->name }}"
                     class="rounded-xl w-10 h-10 object-cover flex-shrink-0"
                     style="border:1px solid rgba(0,245,255,0.15);">
                @else
                <div class="rounded-xl w-10 h-10 flex items-center justify-center font-bold heading text-lg flex-shrink-0"
                     style="background:rgba({{ match($user->role) { 'admin' => '255,107,0', 'garage' => '168,85,247', default => '0,245,255' } }},0.1);color:{{ match($user->role) { 'admin' => '#ff6b00', 'garage' => '#a855f7', default => 'var(--cyan)' } }};">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                @endif
                <div>
                    <p class="heading font-bold text-white text-sm">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                            <span class="text-xs" style="color:#475569;">{{ __('app.you_tag') }}</span>
                        @endif
                    </p>
                    <p class="text-xs" style="color:#64748b;">{{ $user->email }}</p>
                    <p class="text-xs mt-0.5" style="color:#475569;">
                        {{ __('app.joined_text') }} {{ $user->created_at->format('d M Y') }}
                        @if(isset($user->vehicles_count))
                         · {{ $user->vehicles_count }} {{ __('app.vehicles_count') }}
                        @endif
                    </p>
                </div>
            </div>
            <span class="tag flex-shrink-0" style="background:{{ $roleColor['bg'] }};color:{{ $roleColor['color'] }};border:1px solid {{ $roleColor['border'] }};">
                {{ strtoupper(str_replace('_',' ',$user->role)) }}
            </span>
        </div>

        {{-- Actions --}}
        @if($user->id !== auth()->id())
        <div class="grid grid-cols-2 gap-2">
            @if($user->role !== 'admin')
            <form method="POST" action="{{ route('admin.makeAdmin', $user) }}"
                  onsubmit="return confirm('{{ __('app.promote_confirm', ['name' => $user->name]) }}')">
                @csrf
                <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                        style="background:rgba(255,107,0,0.08);border:1px solid rgba(255,107,0,0.2);color:#ff6b00;">
                    <x-heroicon-o-arrow-up class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.make_admin_btn') }}
                </button>
            </form>
            @else
            <div></div>
            @endif

            <button type="button"
                    onclick="openDeleteModal('{{ $user->id }}', '{{ addslashes($user->name) }}')"
                    class="w-full py-2 rounded-xl text-xs font-semibold heading tracking-wider transition-all active:scale-95"
                    style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;">
                <x-heroicon-o-trash class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.delete_user_btn') }}
            </button>
        </div>
        @else
        <div class="rounded-xl p-2 text-center" style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);">
            <p class="text-xs" style="color:#475569;">{{ __('app.your_account_note') }}</p>
        </div>
        @endif

    </div>
    @endforeach

</div>

{{-- Delete confirmation modal --}}
<div id="delete-modal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-sm rounded-2xl p-6 border" style="background:#0d1421;border-color:rgba(248,113,113,0.3);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5" style="color:#f87171;" />
                </div>
                <div>
                    <p class="heading font-bold text-white">{{ __('app.delete_user_btn') }}</p>
                    <p id="modal-username" class="text-xs" style="color:#64748b;"></p>
                </div>
            </div>
            <p class="text-sm mb-4" style="color:#94a3b8;">Type <span class="mono font-bold" style="color:#f87171;">DELETE</span> to confirm this action. This cannot be undone.</p>
            <input type="text" id="delete-confirm-input"
                   placeholder="Type DELETE to confirm"
                   oninput="onDeleteInput()"
                   class="w-full px-4 py-2.5 rounded-xl text-sm text-white placeholder-slate-600 outline-none mb-4 mono"
                   style="background:rgba(255,255,255,0.04);border:1px solid rgba(248,113,113,0.3);">
            <div class="flex gap-2">
                <button onclick="closeDeleteModal()"
                        class="flex-1 py-2.5 rounded-xl text-xs font-semibold heading tracking-wider"
                        style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:#64748b;">
                    {{ __('app.cancel') }}
                </button>
                <form id="delete-form" method="POST" style="flex:1;">
                    @csrf @method('DELETE')
                    <button type="submit" id="delete-submit-btn" disabled
                            class="w-full py-2.5 rounded-xl text-xs font-semibold heading tracking-wider transition-all"
                            style="background:rgba(248,113,113,0.08);border:1px solid rgba(248,113,113,0.2);color:#f87171;opacity:0.4;cursor:not-allowed;">
                        <x-heroicon-o-trash class="w-3 h-3 inline-block mr-1 align-middle" />{{ __('app.delete_user_btn') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var activeFilter = 'all';

function filterUsers() {
    var search = document.getElementById('user-search').value.toLowerCase();
    document.querySelectorAll('.user-card').forEach(function (card) {
        var name    = card.dataset.name  || '';
        var role    = card.dataset.role  || '';
        var matchSearch = name.includes(search);
        var matchFilter = activeFilter === 'all' || role === activeFilter;
        card.style.display = (matchSearch && matchFilter) ? '' : 'none';
    });
}

function setFilter(role) {
    activeFilter = role;
    ['all','admin','garage','vehicle_owner'].forEach(function (r) {
        var btn = document.getElementById('filter-' + r);
        var active = r === role;
        btn.style.background   = active ? 'rgba(0,245,255,0.12)' : 'rgba(255,255,255,0.04)';
        btn.style.borderColor  = active ? 'rgba(0,245,255,0.3)'  : 'rgba(255,255,255,0.08)';
        btn.style.color        = active ? '#00f5ff' : '#64748b';
    });
    filterUsers();
}

function openDeleteModal(userId, userName) {
    document.getElementById('modal-username').textContent = userName;
    document.getElementById('delete-form').action = '/admin/users/' + userId;
    document.getElementById('delete-confirm-input').value = '';
    document.getElementById('delete-submit-btn').disabled = true;
    document.getElementById('delete-submit-btn').style.opacity = '0.4';
    document.getElementById('delete-submit-btn').style.cursor = 'not-allowed';
    document.getElementById('delete-modal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

function onDeleteInput() {
    var val = document.getElementById('delete-confirm-input').value;
    var btn = document.getElementById('delete-submit-btn');
    var ok  = val === 'DELETE';
    btn.disabled = !ok;
    btn.style.opacity = ok ? '1' : '0.4';
    btn.style.cursor  = ok ? 'pointer' : 'not-allowed';
}

document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
</x-app-layout>