@extends('layouts.app')

@section('title', 'My Daily Backup')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 animate-fade-in">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4">
                <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
                My Daily Backup History
            </h2>
            <p class="text-sm font-medium text-slate-500 mt-2 ml-5">View all your past daily backup records.</p>
        </div>
        <div>
            <a href="{{ route('staff.daily-backup.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-600/30 transition-all active:scale-95 whitespace-nowrap">
                + Log Today's Backup
            </a>
        </div>
    </div>

    @php
        $currentDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
    @endphp

    <div class="mb-6 bg-white p-2 rounded-2xl shadow-sm border border-slate-200 inline-flex items-center gap-4">
        <a href="{{ route('staff.daily-backup.index', ['month' => $prevMonth->format('m'), 'year' => $prevMonth->format('Y')]) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <span class="text-sm font-black text-slate-700 uppercase tracking-widest min-w-[120px] text-center">
            {{ $currentDate->format('F Y') }}
        </span>
        <a href="{{ route('staff.daily-backup.index', ['month' => $nextMonth->format('m'), 'year' => $nextMonth->format('Y')]) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800">My Backup History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Remark</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($backups as $backup)
                    <tr class="hover:bg-indigo-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700">
                            {{ \Carbon\Carbon::parse($backup->backup_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($backup->status == 'YES')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">YES</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">NO</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-medium">
                            {{ $backup->location ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $backup->remark ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm font-medium">
                            No backup records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
