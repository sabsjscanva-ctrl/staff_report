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
            Save Changes
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
        <div class="bg-[#FFFF00] p-4 text-center border-b border-slate-200">
            <h1 class="text-xl md:text-2xl font-black text-black uppercase tracking-[0.2em]">
                STAFF DATA BACKUP RECORD (MONTHLY REPORT) {{ strtoupper($months[(int)$selectedMonth]) }} {{ $selectedYear }}
            </h1>
        </div>
        
        <div class="overflow-x-auto excel-container">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-2 py-4 text-[10px] font-black text-slate-400 uppercase border-r border-slate-200 sticky left-0 bg-slate-50 z-30 w-10 text-center">SR</th>
                        <th class="px-4 py-4 text-[10px] font-black text-slate-700 uppercase border-r border-slate-200 sticky left-10 bg-slate-50 z-30 min-w-[180px]">Staff Name</th>
                        
                        @foreach($saturdays as $sat)
                            <th colspan="3" class="px-2 py-4 text-[10px] font-black text-slate-700 uppercase border-r border-slate-200 text-center bg-slate-100/50">
                                {{ $sat->format('d-m-Y') }} (SATURDAY)
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-slate-50/50">
                        <th class="sticky left-0 bg-slate-50 z-20 border-r border-slate-200 border-b border-slate-200"></th>
                        <th class="sticky left-10 bg-slate-50 z-20 border-r border-slate-200 border-b border-slate-200"></th>
                        
                        @foreach($saturdays as $sat)
                            <th class="px-1 py-2 text-[9px] font-bold text-slate-400 uppercase border-r border-slate-100 text-center border-b border-slate-200 w-24">Status</th>
                            <th class="px-1 py-2 text-[9px] font-bold text-slate-400 uppercase border-r border-slate-100 text-center border-b border-slate-200 w-32">Location</th>
                            <th class="px-1 py-2 text-[9px] font-bold text-slate-400 uppercase border-r border-slate-200 text-center border-b border-slate-200 min-w-[150px]">Remark</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($staffs as $index => $staff)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-2 py-3 text-[11px] font-bold text-slate-300 border-r border-slate-100 sticky left-0 bg-white group-hover:bg-slate-50 z-20 text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-4 py-3 border-r border-slate-100 sticky left-10 bg-white group-hover:bg-slate-50 z-20">
                            <div class="text-[11px] font-black text-slate-800 uppercase tracking-tight">{{ $staff->name }}</div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase mt-0.5">{{ $staff->department->dept_name ?? 'NO DEPT' }}</div>
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
                                <select name="{{ $prefix }}[status]" class="w-full h-10 bg-transparent border-none text-[10px] font-black text-center focus:ring-0 cursor-pointer appearance-none">
                                    <option value="">-</option>
                                    <option value="YES" {{ ($backup && $backup->status == 'YES') ? 'selected' : '' }}>YES</option>
                                    <option value="NO" {{ ($backup && $backup->status == 'NO') ? 'selected' : '' }}>NO</option>
                                    <option value="NA" {{ ($backup && $backup->status == 'NA') ? 'selected' : '' }}>NA</option>
                                    <option value="Completed" {{ ($backup && $backup->status == 'Completed') ? 'selected' : '' }}>YES</option>
                                </select>
                            </td>
                            
                            <!-- Location Cell -->
                            <td class="p-0 border-r border-slate-100 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500/30">
                                <input type="text" name="{{ $prefix }}[location]" value="{{ $backup->location ?? '' }}" 
                                    class="w-full h-10 bg-transparent border-none text-[10px] font-medium text-slate-600 focus:ring-0 px-2" 
                                    placeholder="...">
                            </td>
                            
                            <!-- Remark Cell -->
                            <td class="p-0 border-r border-slate-200 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500/30">
                                <input type="text" name="{{ $prefix }}[remark]" value="{{ $backup->remark ?? '' }}" 
                                    class="w-full h-10 bg-transparent border-none text-[10px] text-slate-500 italic focus:ring-0 px-2" 
                                    placeholder="...">
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Floating Save Button for Mobile/Large Sheets -->
    <div class="fixed bottom-8 right-8 z-50">
        <button type="submit" class="group flex items-center gap-3 px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl shadow-2xl shadow-slate-900/40 hover:bg-black transition-all active:scale-95">
            <span class="bg-indigo-500 p-1.5 rounded-lg group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l6-6a1 1 0 00-1.414-1.414l-5.293 5.293-2.293-2.293z" />
                </svg>
            </span>
            Save All Entries
        </button>
    </div>
</form>

<style>
    /* Spreadsheet Feel */
    .excel-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    
    .excel-container::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    .excel-container::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    .excel-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    /* Input Styling */
    input[type="text"]:focus, select:focus {
        background-color: #fdfdfd;
    }
    
    /* Alternating row colors like Excel */
    tbody tr:nth-child(even) {
        background-color: #fafafa;
    }
    
    /* Highlight cell on hover */
    td:hover {
        background-color: #f0f4ff;
    }

    /* Sticky fixes for complex table */
    .sticky {
        position: sticky !important;
    }
    
    thead th {
        border-top: 1px solid #e2e8f0;
    }
</style>

<script>
    // Keyboard navigation (Arrow keys)
    document.addEventListener('keydown', function(e) {
        const active = document.activeElement;
        if (!active || (active.tagName !== 'INPUT' && active.tagName !== 'SELECT')) return;
        
        const cell = active.closest('td');
        if (!cell) return;
        
        const row = cell.closest('tr');
        const colIndex = Array.from(row.children).indexOf(cell);
        
        if (e.key === 'ArrowDown') {
            const nextRow = row.nextElementSibling;
            if (nextRow) {
                const nextInput = nextRow.children[colIndex].querySelector('input, select');
                if (nextInput) nextInput.focus();
            }
        } else if (e.key === 'ArrowUp') {
            const prevRow = row.previousElementSibling;
            if (prevRow) {
                const prevInput = prevRow.children[colIndex].querySelector('input, select');
                if (prevInput) prevInput.focus();
            }
        }
    });
</script>
@endsection
