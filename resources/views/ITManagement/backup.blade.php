@extends('layouts.app')

@section('title', 'Staff Backup Sheets')

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

<div class="mb-10 flex flex-col xl:flex-row xl:items-end justify-between gap-8 animate-fade-in">
    <div class="flex-1">
        <div class="flex items-center gap-4 mb-2">
            <span class="px-4 py-1.5 bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-full">IT Management</span>
            <span class="h-px w-12 bg-slate-200"></span>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Backup Logs</span>
        </div>
        <h2 class="text-5xl font-black text-slate-900 tracking-tight leading-tight">
            Staff Data <span class="text-indigo-600 underline decoration-indigo-200 underline-offset-8">BackupSheet</span>
        </h2>
    </div>

    <div class="bg-white p-5 rounded-[2.5rem] shadow-2xl shadow-slate-200/40 border border-slate-100 flex flex-wrap items-center gap-5 transition-all hover:shadow-indigo-500/5">
        <form action="{{ route('it-management.backup.index') }}" method="GET" class="flex flex-wrap items-center gap-4" id="filterForm">
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-3">Office Location</label>
                <select name="office_id" onchange="this.form.submit()" class="form-select min-w-[200px] !py-3 !rounded-2xl shadow-sm border-slate-100 font-bold">
                    <option value="">All Locations</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>

            @if(!$specificDate)
            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-3">Reporting Month</label>
                <div class="flex gap-2">
                    <select name="month" class="form-select !py-3 !rounded-2xl shadow-sm border-slate-100 font-bold min-w-[120px]">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="year" class="form-select !py-3 !rounded-2xl shadow-sm border-slate-100 font-bold min-w-[100px]">
                        @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @endif

            <div class="flex flex-col gap-1.5">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-3">Specific Date View</label>
                <div class="flex gap-2">
                    <input type="date" name="specific_date" value="{{ $specificDate }}" class="form-input !py-3 !rounded-2xl shadow-sm border-slate-100 font-bold">
                    @if($specificDate)
                        <a href="{{ route('it-management.backup.index') }}" class="p-3 bg-rose-50 text-rose-500 rounded-2xl hover:bg-rose-100 transition-all active:scale-90" title="Reset Filters">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <button type="submit" class="self-end p-4 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/30 active:scale-95 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-90 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="bg-indigo-600 text-white px-8 py-4 rounded-[2rem] relative mb-10 flex items-center justify-between animate-fade-in shadow-2xl shadow-indigo-600/20">
    <div class="flex items-center gap-4">
        <div class="bg-white/20 p-2 rounded-xl backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="font-bold tracking-tight">{{ session('success') }}</p>
    </div>
    <button onclick="this.parentElement.remove()" class="text-white/60 hover:text-white transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
</div>
@endif

<form action="{{ route('it-management.backup.store') }}" method="POST" id="bulkBackupForm">
    @csrf
    <div class="bg-white rounded-[3.5rem] shadow-[0_30px_100px_rgba(0,0,0,0.06)] border border-slate-100 overflow-hidden relative isolate">
        <div class="bg-[#FFFF00] p-8 text-center border-b-[6px] border-black/10">
            <h1 class="text-3xl md:text-5xl font-black text-black uppercase tracking-[0.4em] drop-shadow-sm">
                {{ $selectedOffice ? \App\Models\Office\OfficeModel::find($selectedOffice)->office_name : 'OFFICE DATA' }} BACKUP RECORD
            </h1>
            <div class="mt-2 text-sm font-black text-black/40 tracking-[0.2em] uppercase">
                @if($specificDate) 
                    FOR DATE: {{ \Carbon\Carbon::parse($specificDate)->format('d F Y') }}
                @else
                    MONTHLY REPORT: {{ $months[(int)$selectedMonth] }} {{ $selectedYear }}
                @endif
            </div>
        </div>
        
        <!-- Top Scrollbar -->
        <div class="top-scrollbar overflow-x-auto bg-slate-50 border-b border-slate-100 h-2.5">
            <div class="top-scrollbar-content"></div>
        </div>

        <div class="overflow-x-auto excel-container" id="mainTableContainer">
            <table class="w-full text-left border-separate border-spacing-0 min-w-[1800px] table-fixed">
                <thead>
                    <tr class="bg-slate-50/95 backdrop-blur-xl sticky top-0 z-[60]">
                        <th class="w-[60px] px-2 py-8 text-[11px] font-black text-slate-400 uppercase border-r border-b-2 border-slate-200 sticky left-0 bg-slate-50 z-[70] text-center">SR</th>
                        <th class="w-[80px] px-2 py-8 text-[11px] font-black text-slate-400 uppercase border-r border-b-2 border-slate-200 sticky left-[60px] bg-slate-50 z-[70] text-center">Seq</th>
                        <th class="w-[300px] px-8 py-8 text-[12px] font-black text-slate-700 uppercase border-r border-b-2 border-slate-200 sticky left-[140px] bg-slate-50 z-[70]">Staff Member Name</th>
                        
                        @foreach($saturdays as $sat)
                            <th colspan="4" class="px-2 py-8 text-[12px] font-black text-slate-800 uppercase border-r border-b-2 border-slate-200 text-center bg-indigo-50/80">
                                {{ $sat->format('d-m-Y') }} ({{ strtoupper($sat->format('l')) }})
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-white/90 backdrop-blur-xl sticky top-[82px] z-[55]">
                        <th class="sticky left-0 bg-white z-[65] border-r border-b-2 border-slate-100 h-12 shadow-[inset_-1px_0_0_#f1f5f9]"></th>
                        <th class="sticky left-[60px] bg-white z-[65] border-r border-b-2 border-slate-100 h-12 shadow-[inset_-1px_0_0_#f1f5f9]"></th>
                        <th class="sticky left-[140px] bg-white z-[65] border-r border-b-2 border-slate-100 h-12 shadow-[inset_-2px_0_10px_rgba(0,0,0,0.02)]"></th>
                        
                        @foreach($saturdays as $sat)
                            <th class="px-2 py-4 text-[10px] font-black text-slate-300 uppercase border-r border-b-2 border-slate-100 text-center w-32 bg-slate-50/20">Status</th>
                            <th class="px-2 py-4 text-[10px] font-black text-slate-300 uppercase border-r border-b-2 border-slate-100 text-center w-48 bg-slate-50/20">Backup Location</th>
                            <th class="px-4 py-4 text-[10px] font-black text-slate-300 uppercase border-r border-b-2 border-slate-100 text-center min-w-[250px] bg-slate-50/20">Remarks / Issues</th>
                            <th class="px-2 py-4 text-[10px] font-black text-slate-300 uppercase border-r border-b-2 border-slate-200 text-center w-32 bg-slate-50/20">Entry Date</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffs as $index => $staff)
                    <tr class="hover:bg-indigo-50/40 transition-colors group">
                        <!-- SR -->
                        <td class="px-2 py-5 text-xs font-black text-slate-300 border-r border-slate-100 sticky left-0 bg-white group-hover:bg-slate-50/50 z-40 text-center shadow-[inset_-1px_0_0_#f1f5f9]">
                            {{ $index + 1 }}
                        </td>
                        <!-- Sequence -->
                        <td class="p-0 border-r border-slate-100 sticky left-[60px] bg-white group-hover:bg-slate-50/50 z-40 text-center focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 shadow-[inset_-1px_0_0_#f1f5f9]">
                            <input type="number" name="sequences[{{ $staff->id }}]" value="{{ $staff->backup_sequence }}" 
                                class="w-full h-16 bg-transparent border-none text-xs font-black text-slate-800 text-center focus:ring-0 placeholder:text-slate-200"
                                placeholder="-">
                        </td>
                        <!-- Staff Name -->
                        <td class="px-8 py-5 border-r border-slate-100 sticky left-[140px] bg-white group-hover:bg-slate-50/50 z-40 shadow-[4px_0_15px_rgba(0,0,0,0.02)]">
                            <div class="text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-all">{{ $staff->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $staff->department->dept_name ?? 'NOT ASSIGNED' }}</div>
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
                            
                            <!-- Status -->
                            <td class="p-0 border-r border-slate-100 text-center align-middle focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/10">
                                <select name="{{ $prefix }}[status]" class="w-full h-16 bg-transparent border-none text-xs font-black text-center focus:ring-0 cursor-pointer text-slate-700">
                                    <option value="">- SELECT -</option>
                                    <option value="YES" {{ ($backup && ($backup->status == 'YES' || $backup->status == 'Completed')) ? 'selected' : '' }}>YES ✅</option>
                                    <option value="NO" {{ ($backup && ($backup->status == 'NO' || $backup->status == 'Failed')) ? 'selected' : '' }}>NO ❌</option>
                                    <option value="NA" {{ ($backup && $backup->status == 'NA') ? 'selected' : '' }}>N/A ➖</option>
                                </select>
                            </td>
                            
                            <!-- Location -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/10">
                                <select name="{{ $prefix }}[location]" class="w-full h-16 bg-transparent border-none text-[11px] font-bold text-slate-600 focus:ring-0 px-4 cursor-pointer">
                                    <option value="">- SELECT DEVICE -</option>
                                    <option value="DRIVE" {{ ($backup && $backup->location == 'DRIVE') ? 'selected' : '' }}>DRIVE</option>
                                    <option value="HDD" {{ ($backup && $backup->location == 'HDD') ? 'selected' : '' }}>HARD DISK (HDD)</option>
                                    <option value="PENDRIVE" {{ ($backup && $backup->location == 'PENDRIVE') ? 'selected' : '' }}>USB PENDRIVE</option>
                                    <option value="PENDRIVE/DRIVE" {{ ($backup && $backup->location == 'PENDRIVE/DRIVE') ? 'selected' : '' }}>PEN + DRIVE</option>
                                    <option value="LAPTOP" {{ ($backup && $backup->location == 'LAPTOP') ? 'selected' : '' }}>LOCAL LAPTOP</option>
                                    <option value="SOFTWARE" {{ ($backup && $backup->location == 'SOFTWARE') ? 'selected' : '' }}>ERP SOFTWARE</option>
                                </select>
                            </td>
                            
                            <!-- Remark -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/10">
                                <input type="text" name="{{ $prefix }}[remark]" value="{{ $backup->remark ?? '' }}" 
                                    class="w-full h-16 bg-transparent border-none text-xs text-slate-500 font-bold focus:ring-0 px-5 placeholder:text-slate-200" 
                                    placeholder="Enter backup notes...">
                            </td>

                            <!-- Date -->
                            <td class="p-0 border-r border-slate-200 bg-slate-50/10 text-center select-none">
                                <span class="text-[10px] font-black text-slate-300 tracking-tighter">{{ $sat->format('d-m-Y') }}</span>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Professional Floating Save Hub -->
    <div class="fixed bottom-12 right-12 z-[100] group">
        <button type="submit" class="flex items-center gap-6 pl-10 pr-12 py-7 bg-slate-900 text-white rounded-[3rem] shadow-[0_40px_100px_rgba(0,0,0,0.5)] hover:bg-black hover:-translate-y-2 transition-all duration-500 active:scale-95 border-8 border-white/10 backdrop-blur-3xl isolate">
            <div class="relative">
                <div class="absolute inset-0 bg-indigo-500 blur-xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative bg-indigo-500 p-3.5 rounded-2xl shadow-2xl group-hover:scale-110 group-hover:rotate-[15deg] transition-all duration-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v13a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                </div>
            </div>
            <div class="flex flex-col items-start text-left">
                <span class="uppercase tracking-[0.3em] text-[10px] font-black text-indigo-400 mb-1">Backup Records</span>
                <span class="text-2xl font-black tracking-tight">COMMIT DATA</span>
            </div>
        </button>
    </div>
</form>

<style>
    /* Professional Spreadsheet UI Enhancements */
    .excel-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    
    .excel-container::-webkit-scrollbar { height: 12px; width: 12px; }
    .excel-container::-webkit-scrollbar-track { background: #fcfcfc; }
    .excel-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
        border: 4px solid #fcfcfc;
    }
    .excel-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Perfect Sticky Column Alignment */
    /* SR: 60px wide, left: 0 */
    /* Seq: 80px wide, left: 60px */
    /* Name: 300px wide, left: 140px */
    .sticky.left-0 { left: 0 !important; }
    .sticky.left-\[60px\] { left: 60px !important; }
    .sticky.left-\[140px\] { left: 140px !important; }
    
    /* Fix for App Header Conflict (Sticky Top) */
    /* App nav is 80px high, so our headers stick at 80px */
    .sticky.top-0 { top: 80px !important; }
    .sticky.top-\[82px\] { top: 162px !important; } /* 80px nav + 82px first header row */

    /* Custom Dropdown Styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
</style>

<script>
    // Advanced Scroll Handling
    const mainContainer = document.getElementById('mainTableContainer');
    const topScrollbar = document.querySelector('.top-scrollbar');
    const topScrollbarContent = document.querySelector('.top-scrollbar-content');
    
    function syncScroll() {
        const table = mainContainer.querySelector('table');
        topScrollbarContent.style.width = table.scrollWidth + 'px';
        topScrollbar.scrollLeft = mainContainer.scrollLeft;
    }
    
    window.addEventListener('load', syncScroll);
    window.addEventListener('resize', syncScroll);

    mainContainer.addEventListener('scroll', () => {
        topScrollbar.scrollLeft = mainContainer.scrollLeft;
    });
    
    topScrollbar.addEventListener('scroll', () => {
        mainContainer.scrollLeft = topScrollbar.scrollLeft;
    });

    // Excel Navigation Logic
    document.addEventListener('keydown', function(e) {
        const active = document.activeElement;
        if (!active || (active.tagName !== 'INPUT' && active.tagName !== 'SELECT')) return;
        
        const cell = active.closest('td');
        const row = cell.closest('tr');
        const colIndex = Array.from(row.children).indexOf(cell);
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const target = row.nextElementSibling?.children[colIndex].querySelector('input, select');
            target?.focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const target = row.previousElementSibling?.children[colIndex].querySelector('input, select');
            target?.focus();
        }
    });
</script>
@endsection
