@extends('layouts.app')

@section('title', 'IT Support - Intelligence Report')

@section('content')
<div class="space-y-8 animate-fade-in pb-20">
    <!-- Header -->
    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Report Hub</h1>
            <p class="text-slate-500 font-medium mt-1 text-sm text-center md:text-left">Track operational efficiency and ticket history.</p>
        </div>
        
        <form action="{{ route('it-tickets.report') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->format('Y-m-d')) }}" 
                       class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                       class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>
            
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-1">Staff Member</label>
                <select name="staff_id" class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">All Staff</option>
                    @foreach($allStaff as $staff)
                        <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-lg shadow-slate-100">
                Filter
            </button>

            <div class="flex gap-2">
                <a href="{{ route('it-tickets.report.export', array_merge(request()->all(), ['type' => 'excel'])) }}" 
                   class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Excel
                </a>
                <a href="{{ route('it-tickets.report.export', array_merge(request()->all(), ['type' => 'pdf'])) }}" 
                   class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-700 transition-all shadow-lg shadow-rose-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 p-8 rounded-[2rem] text-white shadow-xl shadow-indigo-100 flex flex-col justify-center">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-200">Tickets Analyzed</span>
            <span class="text-4xl font-black mt-2">{{ count($tickets) }}</span>
        </div>
        
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 flex flex-col justify-center">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Solved Today</span>
            <span class="text-4xl font-black mt-2 text-emerald-500">{{ $tickets->where('status', 'Completed')->count() }}</span>
        </div>

        <div class="bg-slate-900 p-8 rounded-[2rem] text-white shadow-xl shadow-slate-100 flex flex-col justify-center">
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Avg. Resolution Time</span>
            @php
                $completed = $tickets->where('status', 'Completed');
                $avgSeconds = $completed->count() > 0 ? $completed->avg('total_seconds_spent') : 0;
                $h = floor($avgSeconds / 3600);
                $m = floor(($avgSeconds % 3600) / 60);
                if ($avgSeconds > 0 && $avgSeconds < 60) $m = 1;
            @endphp
            <span class="text-4xl font-black mt-2">{{ $h }}h {{ $m }}m</span>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Requester</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Issue Detail</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Timestamps</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Time Consumed</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-900 uppercase">
                                    {{ substr($ticket->staff->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-800">{{ $ticket->staff->name }}</span>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $ticket->staff->staff->department->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-black text-slate-800">{{ $ticket->subject }}</span>
                                <span class="text-[9px] font-bold text-indigo-500 uppercase">{{ $ticket->category }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-300 uppercase">Raised:</span>
                                    <span class="text-[10px] font-bold text-slate-600">{{ $ticket->created_at->format('h:i A') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-[8px] font-black text-slate-300 uppercase">Solved:</span>
                                    <span class="text-[10px] font-bold text-slate-600">{{ $ticket->completed_at ? $ticket->completed_at->format('h:i A') : '--:--' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @php
                                $s = $ticket->total_seconds_spent;
                                if ($ticket->status === 'In Progress' && $ticket->last_status_change_at) {
                                    $s += now()->diffInSeconds($ticket->last_status_change_at);
                                }
                                $hh = floor($s / 3600);
                                $mm = floor(($s % 3600) / 60);
                                if ($s > 0 && $s < 60) $mm = 1;
                            @endphp
                            <span class="text-xs font-black bg-slate-100 px-3 py-1 rounded-lg text-slate-900 border border-slate-200">
                                {{ $hh }}h {{ $mm }}m
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full border
                                {{ $ticket->status === 'Completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                                {{ $ticket->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center text-slate-400 font-bold italic text-sm">
                            No tickets found for the selected criteria.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.animate-fade-in { animation: fade-in 0.6s ease-out; }
</style>
@endsection
