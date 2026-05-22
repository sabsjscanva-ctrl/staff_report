@extends('layouts.app')
@section('title', 'Task Report: ' . $title)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Task Report</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium">Complete timeline and details for this task.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('daily-report.task.export', ['task' => $task->id, 'format' => 'excel']) }}" class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl font-bold hover:bg-emerald-100 transition shadow-sm border border-emerald-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Excel
            </a>
            <a href="{{ route('daily-report.task.export', ['task' => $task->id, 'format' => 'pdf']) }}" class="flex items-center gap-2 px-4 py-2 bg-rose-50 text-rose-600 rounded-xl font-bold hover:bg-rose-100 transition shadow-sm border border-rose-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Task Title</p>
                <p class="font-bold text-slate-800">{{ $title }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100">
                <p class="text-[11px] font-bold text-indigo-400 uppercase tracking-widest mb-1">Assigned Staff</p>
                <p class="font-bold text-indigo-800">{{ $task->dailyReport->staff->name ?? 'Unknown' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-widest mb-1">Total Time Logged</p>
                <p class="font-bold text-emerald-800">{{ $totalTimeFormatted }}</p>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-6">Activity Timeline</h3>
        
        <div class="relative border-l-2 border-indigo-100 ml-3 space-y-8 pb-4">
            @forelse($historyData as $idx => $data)
                <div class="relative pl-8">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-2.5 top-1.5 w-5 h-5 rounded-full border-4 border-white shadow-sm
                        {{ strtolower($data['status']) === 'completed' ? 'bg-emerald-400' : 'bg-indigo-400' }}">
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="flex flex-wrap items-center justify-between mb-3 gap-4">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-600 shadow-sm">
                                    <svg class="w-3.5 h-3.5 inline mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    {{ $data['date'] }}
                                </span>
                                @if($data['status'])
                                    @php
                                        $statusClass = match(strtolower($data['status'])) {
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'paused' => 'bg-amber-100 text-amber-700',
                                            'idle' => 'bg-slate-200 text-slate-700',
                                            default => 'bg-indigo-100 text-indigo-700'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                        {{ $data['status'] }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 text-slate-500 font-medium text-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $data['time_spend'] }} logged
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-slate-100 text-sm text-slate-700 whitespace-pre-wrap leading-relaxed shadow-sm font-medium">
                            @if($data['description'])
                                {!! nl2br(e($data['description'])) !!}
                            @else
                                <span class="text-slate-400 italic">No description provided.</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="pl-8 text-slate-500 font-medium">No history recorded for this task.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
