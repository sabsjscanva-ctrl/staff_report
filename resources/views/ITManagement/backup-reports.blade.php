@extends('layouts.app')

@section('title', 'Backup Reports')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 animate-fade-in">
    <div class="mb-8">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight flex items-center gap-4">
            <span class="w-1.5 h-8 bg-indigo-600 rounded-full"></span>
            IT Backup Reports
        </h2>
        <p class="text-sm font-medium text-slate-500 mt-2 ml-5">Filter and export daily backup records.</p>
    </div>

    <!-- Filters Form -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden mb-10">
        <div class="bg-slate-50 px-8 py-5 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Report Filters</h3>
        </div>
        <form method="GET" action="{{ route('it-management.backup-reports.index') }}" class="p-8" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Staff Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Staff Member</label>
                    <select name="staff_id" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700">
                        <option value="">All Staff</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ request('staff_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter By</label>
                    <select name="filter_type" id="filter_type" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700" onchange="toggleFilterType()">
                        <option value="month" {{ request('filter_type', 'month') == 'month' ? 'selected' : '' }}>Month Wise</option>
                        <option value="date_range" {{ request('filter_type') == 'date_range' ? 'selected' : '' }}>Date Range (Day Wise)</option>
                    </select>
                </div>

                <!-- Month Wise Fields -->
                <div id="month_fields" class="col-span-2 grid grid-cols-2 gap-6" style="display: {{ request('filter_type', 'month') == 'month' ? 'grid' : 'none' }}">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Month</label>
                        <select name="month" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>
                                    {{ date("F", mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Year</label>
                        <select name="year" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700">
                            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <!-- Date Range Fields -->
                <div id="date_range_fields" class="col-span-2 grid grid-cols-2 gap-6" style="display: {{ request('filter_type') == 'date_range' ? 'grid' : 'none' }}">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 font-bold text-slate-700">
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 border-t border-slate-100 pt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl shadow-lg shadow-indigo-600/30 transition-all">
                    Apply Filters
                </button>
                <a href="{{ route('it-management.backup-reports.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-8 rounded-xl transition-all">
                    Reset
                </a>
                
                <div class="flex-1"></div>
                
                <button type="button" onclick="exportReport('pdf')" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2.5 px-6 rounded-xl border border-red-200 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export PDF
                </button>
                <button type="button" onclick="exportReport('excel')" class="bg-green-50 hover:bg-green-100 text-green-600 font-bold py-2.5 px-6 rounded-xl border border-green-200 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Results Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-wider">Staff Name</th>
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
                            {{ $backup->staff->name ?? 'Unknown' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-600">
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
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm font-medium">
                            No records found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($backups->hasPages())
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $backups->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function toggleFilterType() {
        const filterType = document.getElementById('filter_type').value;
        if (filterType === 'month') {
            document.getElementById('month_fields').style.display = 'grid';
            document.getElementById('date_range_fields').style.display = 'none';
        } else {
            document.getElementById('month_fields').style.display = 'none';
            document.getElementById('date_range_fields').style.display = 'grid';
        }
    }

    function exportReport(type) {
        const form = document.getElementById('filterForm');
        let actionUrl = '';
        
        if (type === 'pdf') {
            actionUrl = '{{ route('it-management.backup-reports.export-pdf') }}';
        } else if (type === 'excel') {
            actionUrl = '{{ route('it-management.backup-reports.export-excel') }}';
        }

        const currentAction = form.action;
        form.action = actionUrl;
        form.submit();
        form.action = currentAction; // Reset back to filter
    }
</script>
@endsection
