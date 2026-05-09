@extends('layouts.app')

@section('title', 'Excel-Style Backup Management')

@section('content')
@php
    $selectedMonth = request('month', date('m'));
    $selectedYear = request('year', date('Y'));
    $currentDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonth, 1);
    
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();
    
    $saturdays = [];
    $date = $startOfMonth->copy();
    while ($date <= $endOfMonth) {
        if ($date->isSaturday()) {
            $saturdays[] = $date->copy();
        }
        $date->addDay();
    }
    
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June',
        7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
@endphp

<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
            <span class="bg-indigo-600 text-white p-2 rounded-xl shadow-lg shadow-indigo-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </span>
            Staff Backup Sheet
        </h2>
        <p class="text-slate-500 font-medium mt-1 ml-11">Bulk entry mode — edit cells directly like Excel</p>
    </div>

    <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
        <form action="{{ route('it-management.backup.index') }}" method="GET" class="flex items-center gap-2">
            <select name="month" class="bg-slate-50 border-none rounded-xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all cursor-pointer">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <select name="year" class="bg-slate-50 border-none rounded-xl text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 transition-all cursor-pointer">
                @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <button type="submit" class="p-2 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
        <div class="h-8 w-px bg-slate-200 mx-1"></div>
        <button form="bulkBackupForm" type="submit" class="flex items-center gap-2 px-6 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/20 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l6-6a1 1 0 00-1.414-1.414l-5.293 5.293-2.293-2.293z" />
            </svg>
            Save All
        </button>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl relative mb-8 flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
    <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <span class="font-semibold">{{ session('success') }}</span>
</div>
@endif

<form action="{{ route('it-management.backup.store') }}" method="POST" id="bulkBackupForm">
    @csrf
    <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
        <div class="bg-[#FFFF00] p-5 text-center border-b border-slate-200">
            <h1 class="text-2xl md:text-3xl font-black text-black uppercase tracking-[0.25em]">
                STAFF DATA BACKUP RECORD (MONTHLY REPORT) {{ strtoupper($months[(int)$selectedMonth]) }} {{ $selectedYear }}
            </h1>
        </div>
        
        <div class="overflow-x-auto excel-container">
            <table class="w-full text-left border-collapse min-w-[1500px]">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-2 py-5 text-[11px] font-black text-slate-400 uppercase border-r border-slate-200 sticky left-0 bg-slate-50 z-30 w-12 text-center border-b">SR</th>
                        <th class="px-6 py-5 text-[11px] font-black text-slate-700 uppercase border-r border-slate-200 sticky left-12 bg-slate-50 z-30 min-w-[220px] border-b">Staff Name</th>
                        
                        @foreach($saturdays as $sat)
                            <th colspan="4" class="px-2 py-5 text-[11px] font-black text-slate-700 uppercase border-r border-slate-200 text-center bg-slate-100/50 border-b">
                                {{ $sat->format('d-m-Y') }} (SATURDAY)
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-slate-50/50">
                        <th class="sticky left-0 bg-slate-50 z-20 border-r border-slate-200 border-b"></th>
                        <th class="sticky left-12 bg-slate-50 z-20 border-r border-slate-200 border-b"></th>
                        
                        @foreach($saturdays as $sat)
                            <th class="px-2 py-3 text-[10px] font-bold text-slate-400 uppercase border-r border-slate-100 text-center border-b w-28">Status</th>
                            <th class="px-2 py-3 text-[10px] font-bold text-slate-400 uppercase border-r border-slate-100 text-center border-b w-40">Location</th>
                            <th class="px-2 py-3 text-[10px] font-bold text-slate-400 uppercase border-r border-slate-100 text-center border-b min-w-[180px]">Remark</th>
                            <th class="px-2 py-3 text-[10px] font-bold text-slate-400 uppercase border-r border-slate-200 text-center border-b w-28">Date</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffs as $index => $staff)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-2 py-4 text-xs font-bold text-slate-300 border-r border-slate-100 sticky left-0 bg-white group-hover:bg-slate-50 z-20 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 border-r border-slate-100 sticky left-12 bg-white group-hover:bg-slate-50 z-20">
                            <div class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ $staff->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase mt-0.5">{{ $staff->department->dept_name ?? 'NO DEPT' }}</div>
                        </td>
                        
                        @php
                            $backupsByDate = $staff->systemBackups->groupBy(function($item) {
                                return \Carbon\Carbon::parse($item->backup_date)->format('Y-m-d');
                            });
                        @endphp
                        
                        @foreach($saturdays as $sat)
                            @php
                                $dateStr = $sat->format('Y-m-d');
                                $backup = $backupsByDate->has($dateStr) ? $backupsByDate->get($dateStr)->first() : null;
                                $prefix = "backups[{$staff->id}][{$dateStr}]";
                            @endphp
                            
                            <!-- Status Cell -->
                            <td class="p-0 border-r border-slate-100 text-center align-middle focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500/30">
                                <select name="{{ $prefix }}[status]" class="w-full h-12 bg-transparent border-none text-[11px] font-black text-center focus:ring-0 cursor-pointer">
                                    <option value="">-</option>
                                    <option value="YES" {{ ($backup && ($backup->status == 'YES' || $backup->status == 'Completed')) ? 'selected' : '' }}>YES</option>
                                    <option value="NO" {{ ($backup && ($backup->status == 'NO' || $backup->status == 'Failed')) ? 'selected' : '' }}>NO</option>
                                    <option value="NA" {{ ($backup && $backup->status == 'NA') ? 'selected' : '' }}>NA</option>
                                </select>
                            </td>
                            
                            <!-- Location Cell -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500/30">
                                <select name="{{ $prefix }}[location]" class="w-full h-12 bg-transparent border-none text-[10px] font-bold text-slate-600 focus:ring-0 px-2 cursor-pointer">
                                    <option value="">- Select -</option>
                                    <option value="DRIVE" {{ ($backup && $backup->location == 'DRIVE') ? 'selected' : '' }}>DRIVE</option>
                                    <option value="HDD" {{ ($backup && $backup->location == 'HDD') ? 'selected' : '' }}>HDD</option>
                                    <option value="PENDRIVE" {{ ($backup && $backup->location == 'PENDRIVE') ? 'selected' : '' }}>PENDRIVE</option>
                                    <option value="PENDRIVE/DRIVE" {{ ($backup && $backup->location == 'PENDRIVE/DRIVE') ? 'selected' : '' }}>PENDRIVE/DRIVE</option>
                                    <option value="LAPTOP" {{ ($backup && $backup->location == 'LAPTOP') ? 'selected' : '' }}>LAPTOP</option>
                                    <option value="SOFTWARE" {{ ($backup && $backup->location == 'SOFTWARE') ? 'selected' : '' }}>SOFTWARE</option>
                                </select>
                            </td>
                            
                            <!-- Remark Cell -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500/30">
                                <input type="text" name="{{ $prefix }}[remark]" value="{{ $backup->remark ?? '' }}" 
                                    class="w-full h-12 bg-transparent border-none text-[11px] text-slate-500 font-medium focus:ring-0 px-3 placeholder:text-slate-300" 
                                    placeholder="Add remark...">
                            </td>

                            <!-- Date Cell (Readonly but sent in form) -->
                            <td class="p-0 border-r border-slate-200 bg-slate-50/30 text-center">
                                <span class="text-[10px] font-bold text-slate-400">{{ $sat->format('d-m-Y') }}</span>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Floating Save Button -->
    <div class="fixed bottom-10 right-10 z-50">
        <button type="submit" class="group flex items-center gap-4 px-10 py-5 bg-slate-900 text-white font-black rounded-3xl shadow-2xl shadow-slate-900/60 hover:bg-black transition-all active:scale-95 border-4 border-white/10 backdrop-blur-md">
            <span class="bg-indigo-500 p-2 rounded-xl group-hover:rotate-12 transition-transform shadow-lg shadow-indigo-500/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
            <span class="uppercase tracking-widest text-sm">Update Sheets</span>
        </button>
    </div>
</form>

<style>
    /* Premium Excel Scrollbar */
    .excel-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f8fafc;
    }
    
    .excel-container::-webkit-scrollbar {
        height: 10px;
        width: 10px;
    }
    .excel-container::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 5px;
    }
    .excel-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
        border: 2px solid #f8fafc;
    }
    .excel-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Spreadsheet Inputs */
    input[type="text"]:focus, select:focus {
        background-color: #ffffff;
        box-shadow: inset 0 0 0 2px rgba(79, 70, 229, 0.2);
    }
    
    /* Excel Alternating Rows */
    tbody tr:nth-child(even) {
        background-color: #fcfcfc;
    }
    
    /* Grid Hover Effect */
    td:hover {
        background-color: #f1f5ff !important;
    }

    /* Fixed Layout Help */
    .sticky {
        position: sticky !important;
    }
    
    /* Custom Dropdown Styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>

<script>
    // Keyboard Navigation for Power Users
    document.addEventListener('keydown', function(e) {
        const active = document.activeElement;
        if (!active || (active.tagName !== 'INPUT' && active.tagName !== 'SELECT')) return;
        
        const cell = active.closest('td');
        if (!cell) return;
        
        const row = cell.closest('tr');
        const colIndex = Array.from(row.children).indexOf(cell);
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const nextRow = row.nextElementSibling;
            if (nextRow) {
                const nextInput = nextRow.children[colIndex].querySelector('input, select');
                if (nextInput) nextInput.focus();
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const prevRow = row.previousElementSibling;
            if (prevRow) {
                const prevInput = prevRow.children[colIndex].querySelector('input, select');
                if (prevInput) prevInput.focus();
            }
        }
    });
</script>
@endsection
