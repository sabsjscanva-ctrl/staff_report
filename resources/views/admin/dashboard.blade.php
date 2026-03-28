@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
    <p class="text-gray-500 text-sm mt-1">All Control Panel.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
        <p class="text-sm text-gray-500">Total Users</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ \App\Models\User::count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Managers</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ \App\Models\User::where('role','manager')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">Staff Members</p>
        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ \App\Models\User::where('role','staff')->count() }}</p>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Sabhi Users</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Naam</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(\App\Models\User::all() as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $user->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-medium capitalize
                            {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' :
                               ($user->role === 'manager' ? 'bg-green-100 text-green-700' :
                                'bg-yellow-100 text-yellow-700') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
