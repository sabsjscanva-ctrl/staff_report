@extends('layouts.app')

@section('title', 'Manage Backup Locations')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 animate-fade-in">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
            Manage Backup Locations
        </h2>
        <p class="text-sm font-medium text-slate-500 mt-2 ml-5">Add or remove locations for staff to select when submitting daily backups.</p>
    </div>

    @if(session('success'))
    <div class="bg-indigo-600 text-white px-6 py-3 rounded-2xl mb-6 flex items-center justify-between shadow-xl shadow-indigo-600/10">
        <span class="font-bold uppercase text-[10px] tracking-widest italic">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="opacity-40 hover:opacity-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-500 text-white px-6 py-3 rounded-2xl mb-6 shadow-xl shadow-red-500/10">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li class="font-bold uppercase text-[10px] tracking-widest italic">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Add New Location Form -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden mb-10">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">Add New Location</h3>
        </div>
        <form action="{{ route('it-management.backup-locations.store') }}" method="POST" class="p-8 flex items-end gap-4">
            @csrf
            <div class="flex-1">
                <label class="block text-sm font-bold text-slate-700 mb-2">Location Name</label>
                <input type="text" name="name" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 border font-medium" placeholder="e.g. EXTERNAL SSD" required>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-600/30 transition-all active:scale-95 whitespace-nowrap">
                Add Location
            </button>
        </form>
    </div>

    <!-- Locations Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">Available Locations</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-xs font-black text-slate-500 uppercase tracking-wider w-16">ID</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Location Name</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($locations as $location)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-8 py-4 whitespace-nowrap text-sm font-bold text-slate-400">
                            {{ $location->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                            {{ $location->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <form action="{{ route('it-management.backup-locations.destroy', $location->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this location?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-2 bg-red-50 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-slate-500 text-sm font-medium">
                            No backup locations found. Add one above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
