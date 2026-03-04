<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                👥 Manage Users
            </h2>
            <a href="{{ route('admin.dashboard') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                ← Admin Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Name</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Vehicles</th>
                            <th class="px-6 py-3 text-left">Joined</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-gray-400">(You)</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($user->role === 'admin') bg-purple-100 text-purple-700
                                        @elseif($user->role === 'garage') bg-blue-100 text-blue-700
                                        @else bg-green-100 text-green-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $user->vehicles_count }}</td>
                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if($user->role !== 'admin')
                                            <form action="{{ route('admin.makeAdmin', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Make {{ $user->name }} an admin?')">
                                                @csrf
                                                <button class="bg-purple-100 text-purple-700 px-3 py-1 rounded text-xs hover:bg-purple-200">
                                                    Make Admin
                                                </button>
                                            </form>
                                        @endif

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.deleteUser', $user) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-100 text-red-700 px-3 py-1 rounded text-xs hover:bg-red-200">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>