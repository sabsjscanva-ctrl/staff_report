@extends('layouts.app')

@section('title', 'Staff Backup Sheet')

@section('content')
@php
    $selectedMonth = request('month', date('m'));
    $selectedYear = request('year', date('Y'));
    $selectedOffice = request('office_id');
    $specificDate = request('specific_date');
    
    $currentDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
    
    $saturdays = [];
    if ($specificDate) {
        $saturdays[] = \Carbon\Carbon::parse($specificDate);
    } else {
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();
        $date = $startOfMonth->copy();
        while ($date <= $endOfMonth) {
            if ($date->isSaturday()) {
                $saturdays[] = $date->copy();
            }
            $date->addDay();
        }
    }
    
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
        7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
@endphp

<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Backup Sheet</h2>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">IT Management System</p>
    </div>

    <div class="bg-white px-3 py-2 rounded-xl shadow-sm border border-slate-200 flex flex-wrap items-center gap-3">
        <form action="{{ route('it-management.backup.index') }}" method="GET" class="flex flex-wrap items-center gap-2" id="filterForm">
            <select name="office_id" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-lg text-[11px] font-bold text-slate-700 px-3 py-1.5 min-w-[140px]">
                <option value="">All Offices</option>
                @foreach($offices as $office)
                    <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                @endforeach
            </select>

            @if(!$specificDate)
            <select name="month" class="bg-slate-50 border-none rounded-lg text-[11px] font-bold text-slate-700 px-2 py-1.5">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="year" class="bg-slate-50 border-none rounded-lg text-[11px] font-bold text-slate-700 px-2 py-1.5">
                @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            @endif

            <input type="date" name="specific_date" value="{{ $specificDate }}" class="bg-slate-50 border-none rounded-lg text-[11px] font-bold text-slate-700 px-3 py-1.5">
            
            <button type="submit" class="p-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
        </form>
    </div>
</div>

<form action="{{ route('it-management.backup.store') }}" method="POST" id="bulkBackupForm" class="pb-24">
    @csrf
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-[#FFFF00] py-2 text-center border-b border-black/10">
            <h1 class="text-[10px] font-black text-black uppercase tracking-widest">
                STAFF DATA BACKUP RECORD - {{ $selectedOffice ? \App\Models\Office\OfficeModel::find($selectedOffice)->office_name : 'ALL' }}
            </h1>
        </div>
        
        <div class="overflow-x-auto excel-container relative" id="mainTableContainer">
            <table class="w-full text-left border-collapse min-w-[1500px]">
                <thead>
                    <tr class="bg-slate-50 sticky top-0 z-[100] border-b border-slate-200">
                        <th class="sticky left-0 bg-slate-50 z-[110] w-10 px-2 py-3 text-[9px] font-black text-slate-400 uppercase border-r text-center">SR</th>
                        <th class="sticky left-10 bg-slate-50 z-[110] w-12 px-2 py-3 text-[9px] font-black text-slate-400 uppercase border-r text-center">Seq</th>
                        <th class="sticky left-22 bg-slate-50 z-[110] w-56 px-4 py-3 text-[10px] font-black text-slate-700 uppercase border-r">Staff Name</th>
                        
                        @foreach($saturdays as $sat)
                            <th colspan="4" class="px-2 py-3 text-[10px] font-black text-slate-800 uppercase border-r text-center bg-indigo-50/50">
                                {{ $sat->format('d-m-y') }} ({{ substr($sat->format('l'), 0, 3) }})
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-white sticky top-[37px] z-[90] border-b border-slate-200 shadow-sm">
                        <th class="sticky left-0 bg-white z-[110] border-r h-8"></th>
                        <th class="sticky left-10 bg-white z-[110] border-r h-8"></th>
                        <th class="sticky left-22 bg-white z-[110] border-r h-8"></th>
                        
                        @foreach($saturdays as $sat)
                            <th class="px-1 py-1 text-[8px] font-black text-slate-300 uppercase border-r text-center w-20">Stat</th>
                            <th class="px-1 py-1 text-[8px] font-black text-slate-300 uppercase border-r text-center w-28">Loc</th>
                            <th class="px-2 py-1 text-[8px] font-black text-slate-300 uppercase border-r text-center min-w-[150px]">Remark</th>
                            <th class="px-1 py-1 text-[8px] font-black text-slate-300 uppercase border-r w-20 text-center">Date</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffs as $index => $staff)
                    <tr class="hover:bg-indigo-50/20 transition-colors group">
                        <td class="sticky left-0 bg-white group-hover:bg-slate-50 z-40 px-2 py-2 text-[10px] font-bold text-slate-300 border-r text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="sticky left-10 bg-white group-hover:bg-slate-50 z-40 p-0 border-r text-center">
                            <input type="number" name="sequences[{{ $staff->id }}]" value="{{ $staff->backup_sequence }}" 
                                class="w-full h-8 bg-transparent border-none text-[10px] font-black text-slate-700 text-center focus:ring-0 p-0" placeholder="-">
                        </td>
                        <td class="sticky left-22 bg-white group-hover:bg-slate-50 z-40 px-4 py-2 border-r shadow-[2px_0_5px_rgba(0,0,0,0.01)]">
                            <div class="text-[11px] font-black text-slate-800 uppercase truncate" title="{{ $staff->name }}">{{ $staff->name }}</div>
                            <div class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">{{ $staff->department->dept_name ?? 'N/A' }}</div>
                        </td>
                        
                        @php
                            $backupsByDate = $staff->systemBackups->groupBy(function($item) {
                                return \Carbon\Carbon::parse($item->backup_date)->format('Y-m-d');
                            });
                        @endphp
                        
                        @foreach($saturdays as $sat)
                            @php
                                $dateStr = $sat->format('Y-m-d');
                                $backup = $backupsByDate->get($dateStr)?->first();
                                $prefix = "backups[{$staff->id}][{$dateStr}]";
                            @endphp
                            
                            <td class="p-0 border-r text-center focus-within:bg-indigo-50">
                                <select name="{{ $prefix }}[status]" class="w-full h-8 bg-transparent border-none text-[10px] font-bold text-center focus:ring-0 p-0 cursor-pointer">
                                    <option value="">-</option>
                                    <option value="YES" {{ ($backup && $backup->status == 'YES') ? 'selected' : '' }}>YES</option>
                                    <option value="NO" {{ ($backup && $backup->status == 'NO') ? 'selected' : '' }}>NO</option>
                                    <option value="NA" {{ ($backup && $backup->status == 'NA') ? 'selected' : '' }}>NA</option>
                                </select>
                            </td>
                            
                            <td class="p-0 border-r focus-within:bg-indigo-50">
                                <select name="{{ $prefix }}[location]" class="w-full h-8 bg-transparent border-none text-[10px] font-medium text-slate-600 focus:ring-0 px-1 cursor-pointer">
                                    <option value="">-</option>
                                    <option value="DRIVE" {{ ($backup && $backup->location == 'DRIVE') ? 'selected' : '' }}>DRIVE</option>
                                    <option value="HDD" {{ ($backup && $backup->location == 'HDD') ? 'selected' : '' }}>HDD</option>
                                    <option value="PENDRIVE" {{ ($backup && $backup->location == 'PENDRIVE') ? 'selected' : '' }}>PEN</option>
                                    <option value="PENDRIVE/DRIVE" {{ ($backup && $backup->location == 'PENDRIVE/DRIVE') ? 'selected' : '' }}>PEN+DR</option>
                                    <option value="LAPTOP" {{ ($backup && $backup->location == 'LAPTOP') ? 'selected' : '' }}>LAPTOP</option>
                                    <option value="SOFTWARE" {{ ($backup && $backup->location == 'SOFTWARE') ? 'selected' : '' }}>SOFT</option>
                                </select>
                            </td>
                            
                            <td class="p-0 border-r focus-within:bg-indigo-50">
                                <input type="text" name="{{ $prefix }}[remark]" value="{{ $backup->remark ?? '' }}" 
                                    class="w-full h-8 bg-transparent border-none text-[10px] text-slate-500 focus:ring-0 px-2" placeholder="...">
                            </td>

                            <td class="p-0 border-r bg-slate-50/10 text-center">
                                <span class="text-[9px] font-bold text-slate-300">{{ $sat->format('d-m') }}</span>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Fixed Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-slate-200 px-8 py-4 flex justify-between items-center z-[200] shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
        <div class="flex items-center gap-4">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Editing: {{ count($staffs) }} Staff Members
            </div>
        </div>
        <button type="submit" class="group flex items-center gap-3 px-8 py-3 bg-slate-900 text-white rounded-xl shadow-xl hover:bg-black transition-all active:scale-95">
            <div class="bg-indigo-500 p-1.5 rounded-lg group-hover:rotate-12 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v13a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                </svg>
            </div>
            <span class="text-xs font-black uppercase tracking-widest">Update Sheet Records</span>
        </button>
    </div>
</form>

<style>
    /* Compact Excel Fixes */
    .excel-container { scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent; }
    .excel-container::-webkit-scrollbar { height: 6px; width: 6px; }
    .excel-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    
    /* Sticky Fixes */
    .sticky.left-0 { left: 0 !important; }
    .sticky.left-10 { left: 40px !important; }
    .sticky.left-22 { left: 88px !important; }
    
    /* Sticky Top (Nav is 80px) */
    .sticky.top-0 { top: 80px !important; }
    .sticky.top-\[37px\] { top: 117px !important; }

    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.25rem center;
        background-repeat: no-repeat;
        background-size: 0.8em 0.8em;
        -webkit-appearance: none; appearance: none;
    }
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>

<script>
    document.addEventListener('keydown', function(e) {
        const active = document.activeElement;
        if (!active || (active.tagName !== 'INPUT' && active.tagName !== 'SELECT')) return;
        const cell = active.closest('td');
        const row = cell.closest('tr');
        const colIndex = Array.from(row.children).indexOf(cell);
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            row.nextElementSibling?.children[colIndex].querySelector('input, select')?.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            row.previousElementSibling?.children[colIndex].querySelector('input, select')?.focus();
        }
    });
</script>
@endsection
