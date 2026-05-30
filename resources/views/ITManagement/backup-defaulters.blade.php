@extends('layouts.app')

@section('title', 'Defaulters List')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 animate-fade-in">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4">
                <span class="w-1.5 h-8 bg-red-600 rounded-full"></span>
                Defaulters List
            </h2>
            <p class="text-sm font-medium text-slate-500 mt-2 ml-5">Staff members who have continuously missed their daily backups.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('it-management.backup-defaulters.export-pdf') }}" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-5 rounded-xl border border-red-200 transition-all flex items-center gap-2 text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export PDF
            </a>
            <a href="{{ route('it-management.backup-defaulters.export-excel') }}" class="bg-green-50 hover:bg-green-100 text-green-600 font-bold py-2 px-5 rounded-xl border border-green-200 transition-all flex items-center gap-2 text-sm shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Excel
            </a>
        </div>
    </div>

    <!-- Defaulters Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-red-50 px-8 py-5 border-b border-red-100 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <h3 class="text-lg font-bold text-red-800">Action Required: Continuous Missing Backups (3+ Days)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-8 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Staff Name</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider text-center">Consecutive Days Missed</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Last Backup Dates</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($defaulters as $defaulter)
                    <tr class="hover:bg-red-50/30 transition-colors">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="font-bold text-slate-800">{{ $defaulter['staff']->name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $defaulter['staff']->email }}</div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-600">
                            {{ $defaulter['staff']->department->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <span class="bg-red-100 text-red-800 font-black px-4 py-1.5 rounded-xl border border-red-200">
                                {{ $defaulter['consecutive_missed'] }} Days
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            @if($defaulter['recent_backups']->isEmpty())
                                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-lg">Never taken a backup</span>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach($defaulter['recent_backups'] as $date)
                                        <span class="text-xs font-bold text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded-md">
                                            {{ \Carbon\Carbon::parse($date)->format('d M') }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-50 text-green-500 mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">All Good!</h3>
                            <p class="text-slate-500 text-sm mt-1">No staff members have missed their backup for 3 or more consecutive days.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
