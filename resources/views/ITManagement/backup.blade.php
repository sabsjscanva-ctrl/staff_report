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

<div class="mb-8 flex flex-col xl:flex-row xl:items-end justify-between gap-6">
    <div class="flex-1">
        <h2 class="text-4xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <span class="bg-indigo-600 text-white p-3 rounded-2xl shadow-2xl shadow-indigo-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </span>
            Backup Records
        </h2>
        <p class="text-slate-500 font-bold mt-2 ml-1 text-lg">Manage office-wise staff backup logs with custom sequencing.</p>
    </div>

    <div class="bg-white p-4 rounded-[2rem] shadow-xl border border-slate-100 flex flex-wrap items-center gap-4">
        <form action="{{ route('it-management.backup.index') }}" method="GET" class="flex flex-wrap items-center gap-3" id="filterForm">
            <!-- Office Filter -->
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Select Office</label>
                <select name="office_id" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2.5 min-w-[180px]">
                    <option value="">All Offices</option>
                    @foreach($offices as $office)
                        <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>{{ $office->office_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month/Year -->
            @if(!$specificDate)
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Month / Year</label>
                <div class="flex gap-1">
                    <select name="month" class="bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 px-3 py-2.5">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="year" class="bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 px-3 py-2.5">
                        @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @endif

            <!-- Specific Date -->
            <div class="flex flex-col gap-1">
                <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Single Date View</label>
                <div class="flex gap-1">
                    <input type="date" name="specific_date" value="{{ $specificDate }}" class="bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500/20 px-4 py-2.5">
                    @if($specificDate)
                        <a href="{{ route('it-management.backup.index') }}" class="p-2.5 bg-rose-50 text-rose-500 rounded-xl hover:bg-rose-100 transition-colors" title="Clear Date">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <button type="submit" class="self-end p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-8 py-5 rounded-3xl relative mb-10 flex items-center gap-4 animate-in fade-in slide-in-from-top-6 duration-500 shadow-lg shadow-emerald-500/5">
    <div class="bg-emerald-100 p-3 rounded-2xl text-emerald-600 shadow-inner">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
    </div>
    <div>
        <span class="font-black text-lg">Success!</span>
        <p class="text-sm font-medium opacity-80">{{ session('success') }}</p>
    </div>
</div>
@endif

<form action="{{ route('it-management.backup.store') }}" method="POST" id="bulkBackupForm">
    @csrf
    <div class="bg-white rounded-[3rem] shadow-3xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
        <div class="bg-[#FFFF00] p-6 text-center border-b-4 border-black/5">
            <h1 class="text-2xl md:text-4xl font-black text-black uppercase tracking-[0.3em]">
                {{ $selectedOffice ? \App\Models\Office\OfficeModel::find($selectedOffice)->office_name : 'ALL OFFICES' }} DATA BACKUP RECORD 
                @if($specificDate) 
                    ({{ \Carbon\Carbon::parse($specificDate)->format('d M Y') }})
                @else
                    ({{ strtoupper($months[(int)$selectedMonth]) }} {{ $selectedYear }})
                @endif
            </h1>
        </div>
        
        <!-- Top Scrollbar -->
        <div class="top-scrollbar overflow-x-auto bg-slate-50 border-b border-slate-100 h-4">
            <div class="top-scrollbar-content"></div>
        </div>

        <div class="overflow-x-auto excel-container" id="mainTableContainer">
            <table class="w-full text-left border-collapse min-w-[1600px] table-fixed">
                <thead>
                    <tr class="bg-slate-50/80 backdrop-blur-sm sticky top-0 z-40">
                        <th class="w-16 px-2 py-6 text-[10px] font-black text-slate-400 uppercase border-r border-slate-200 sticky left-0 bg-slate-50 z-50 text-center border-b-2 border-slate-200">Seq</th>
                        <th class="w-16 px-2 py-6 text-[10px] font-black text-slate-400 uppercase border-r border-slate-200 sticky left-16 bg-slate-50 z-50 text-center border-b-2 border-slate-200">SR</th>
                        <th class="w-64 px-6 py-6 text-[11px] font-black text-slate-700 uppercase border-r border-slate-200 sticky left-32 bg-slate-50 z-50 border-b-2 border-slate-200">Staff Name</th>
                        
                        @foreach($saturdays as $sat)
                            <th colspan="4" class="px-2 py-6 text-[11px] font-black text-slate-800 uppercase border-r border-slate-200 text-center bg-indigo-50/50 border-b-2 border-slate-200">
                                {{ $sat->format('d-m-Y') }} ({{ strtoupper($sat->format('l')) }})
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-slate-50/30 sticky top-[68px] z-40">
                        <th class="sticky left-0 bg-slate-50 z-50 border-r border-slate-200 border-b-2"></th>
                        <th class="sticky left-16 bg-slate-50 z-50 border-r border-slate-200 border-b-2"></th>
                        <th class="sticky left-32 bg-slate-50 z-50 border-r border-slate-200 border-b-2"></th>
                        
                        @foreach($saturdays as $sat)
                            <th class="px-2 py-3 text-[10px] font-black text-slate-400 uppercase border-r border-slate-100 text-center border-b-2 w-28 bg-white/50">Status</th>
                            <th class="px-2 py-3 text-[10px] font-black text-slate-400 uppercase border-r border-slate-100 text-center border-b-2 w-44 bg-white/50">Location</th>
                            <th class="px-4 py-3 text-[10px] font-black text-slate-400 uppercase border-r border-slate-100 text-center border-b-2 min-w-[200px] bg-white/50">Remark</th>
                            <th class="px-2 py-3 text-[10px] font-black text-slate-400 uppercase border-r border-slate-200 text-center border-b-2 w-28 bg-white/50">Date</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffs as $index => $staff)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <!-- Sequence -->
                        <td class="p-0 border-r border-slate-100 sticky left-0 bg-white group-hover:bg-slate-50 z-30 text-center focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
                            <input type="number" name="sequences[{{ $staff->id }}]" value="{{ $staff->backup_sequence }}" 
                                class="w-full h-14 bg-transparent border-none text-[11px] font-black text-slate-800 text-center focus:ring-0"
                                placeholder="-">
                        </td>
                        <!-- SR -->
                        <td class="px-2 py-4 text-xs font-black text-slate-300 border-r border-slate-100 sticky left-16 bg-white group-hover:bg-slate-50 z-30 text-center">
                            {{ $index + 1 }}
                        </td>
                        <!-- Staff Name -->
                        <td class="px-6 py-4 border-r border-slate-100 sticky left-32 bg-white group-hover:bg-slate-50 z-30">
                            <div class="text-[13px] font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors">{{ $staff->name }}</div>
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
                            
                            <!-- Status -->
                            <td class="p-0 border-r border-slate-100 text-center align-middle focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/20">
                                <select name="{{ $prefix }}[status]" class="w-full h-14 bg-transparent border-none text-[11px] font-black text-center focus:ring-0 cursor-pointer text-slate-700">
                                    <option value="">-</option>
                                    <option value="YES" {{ ($backup && ($backup->status == 'YES' || $backup->status == 'Completed')) ? 'selected' : '' }}>YES</option>
                                    <option value="NO" {{ ($backup && ($backup->status == 'NO' || $backup->status == 'Failed')) ? 'selected' : '' }}>NO</option>
                                    <option value="NA" {{ ($backup && $backup->status == 'NA') ? 'selected' : '' }}>NA</option>
                                </select>
                            </td>
                            
                            <!-- Location -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/20">
                                <select name="{{ $prefix }}[location]" class="w-full h-14 bg-transparent border-none text-[10px] font-black text-slate-600 focus:ring-0 px-4 cursor-pointer">
                                    <option value="">- SELECT -</option>
                                    <option value="DRIVE" {{ ($backup && $backup->location == 'DRIVE') ? 'selected' : '' }}>DRIVE</option>
                                    <option value="HDD" {{ ($backup && $backup->location == 'HDD') ? 'selected' : '' }}>HDD</option>
                                    <option value="PENDRIVE" {{ ($backup && $backup->location == 'PENDRIVE') ? 'selected' : '' }}>PENDRIVE</option>
                                    <option value="PENDRIVE/DRIVE" {{ ($backup && $backup->location == 'PENDRIVE/DRIVE') ? 'selected' : '' }}>PENDRIVE/DRIVE</option>
                                    <option value="LAPTOP" {{ ($backup && $backup->location == 'LAPTOP') ? 'selected' : '' }}>LAPTOP</option>
                                    <option value="SOFTWARE" {{ ($backup && $backup->location == 'SOFTWARE') ? 'selected' : '' }}>SOFTWARE</option>
                                </select>
                            </td>
                            
                            <!-- Remark -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-4 focus-within:ring-inset focus-within:ring-indigo-500/20">
                                <input type="text" name="{{ $prefix }}[remark]" value="{{ $backup->remark ?? '' }}" 
                                    class="w-full h-14 bg-transparent border-none text-[11px] text-slate-500 font-bold focus:ring-0 px-4 placeholder:text-slate-200" 
                                    placeholder="Click to add remark...">
                            </td>

                            <!-- Date -->
                            <td class="p-0 border-r border-slate-200 bg-slate-50/20 text-center select-none">
                                <span class="text-[10px] font-black text-slate-300">{{ $sat->format('d-m-Y') }}</span>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Enhanced Floating Action Button -->
    <div class="fixed bottom-12 right-12 z-[60]">
        <button type="submit" class="group flex items-center gap-5 px-12 py-6 bg-slate-900 text-white font-black rounded-[2.5rem] shadow-3xl shadow-slate-900/60 hover:bg-black hover:-translate-y-1 transition-all active:scale-95 border-8 border-white/20 backdrop-blur-xl">
            <div class="bg-indigo-500 p-2.5 rounded-2xl group-hover:rotate-[360deg] transition-all duration-700 shadow-xl shadow-indigo-500/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex flex-col items-start">
                <span class="uppercase tracking-[0.2em] text-xs opacity-60">Master Save</span>
                <span class="text-xl">COMMIT CHANGES</span>
            </div>
        </button>
    </div>
</form>

<style>
    /* Premium Scrollbars */
    .excel-container, .top-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    
    .excel-container::-webkit-scrollbar, .top-scrollbar::-webkit-scrollbar {
        height: 12px;
        width: 12px;
    }
    .excel-container::-webkit-scrollbar-track, .top-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 6px;
    }
    .excel-container::-webkit-scrollbar-thumb, .top-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 6px;
        border: 3px solid #f8fafc;
    }
    .excel-container::-webkit-scrollbar-thumb:hover, .top-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Sticky Column Alignment Fix */
    .sticky.left-0 { z-index: 51; }
    .sticky.left-16 { z-index: 51; left: 64px !important; }
    .sticky.left-32 { z-index: 51; left: 128px !important; }
    
    /* Cell Highlighting */
    td:hover { background-color: #f8faff !important; }
    tr:nth-child(even) { background-color: #fbfcfe; }

    /* Custom Dropdown Styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.25em 1.25em;
        padding-right: 2.5rem;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>

<script>
    // Sync Top and Bottom Scrollbars
    const mainContainer = document.getElementById('mainTableContainer');
    const topScrollbar = document.querySelector('.top-scrollbar');
    const topScrollbarContent = document.querySelector('.top-scrollbar-content');
    
    // Set width of fake scrollbar content to match table width
    function syncScrollWidth() {
        const table = mainContainer.querySelector('table');
        topScrollbarContent.style.width = table.scrollWidth + 'px';
    }
    
    window.addEventListener('load', syncScrollWidth);
    window.addEventListener('resize', syncScrollWidth);

    mainContainer.addEventListener('scroll', () => {
        topScrollbar.scrollLeft = mainContainer.scrollLeft;
    });
    
    topScrollbar.addEventListener('scroll', () => {
        mainContainer.scrollLeft = topScrollbar.scrollLeft;
    });

    // Arrow Key Navigation
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
