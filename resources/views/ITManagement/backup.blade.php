@extends('layouts.app')

@section('title', 'Backup Logs')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Staff Data Backup Logs</h2>
        <p class="text-sm text-gray-500 mt-1">Monitor and log data backup activities of staff members.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Staff List & Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Log New Backup</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('it-management.backup.store') }}" method="POST">
                    @csrf
                    <div class="space-y-1">
                        <div class="form-group">
                            <label class="form-label">Select Staff</label>
                            <select name="staff_id" id="staff_id" required class="form-select">
                                <option value="">-- Select Staff --</option>
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->department->dept_name ?? 'No Dept' }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Backup Date</label>
                            <input type="date" name="backup_date" required value="{{ date('Y-m-d') }}" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                                <option value="Failed">Failed</option>
                                <option value="Not Taking">Not Taking Backup</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Backup Location</label>
                            <input type="text" name="location" class="form-input" placeholder="e.g. NAS, External HDD, GDrive">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Remarks</label>
                            <textarea name="remark" rows="2" class="form-textarea" placeholder="Any issues or notes..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all active:scale-95">
                            Save Backup Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Recent Backup History -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Recent Backup Activity</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location & Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            // Flatten and sort the backups for display
                            $allBackups = collect();
                            foreach($staffs as $staff) {
                                foreach($staff->systemBackups as $backup) {
                                    $backup->staff_name = $staff->name;
                                    $backup->dept_name = $staff->department->dept_name ?? 'N/A';
                                    $allBackups->push($backup);
                                }
                            }
                            $recentBackups = $allBackups->sortByDesc('backup_date')->take(20);
                        @endphp
                        
                        @forelse($recentBackups as $backup)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($backup->backup_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $backup->staff_name }}</div>
                                <div class="text-xs text-gray-500">{{ $backup->dept_name }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $color = match($backup->status) {
                                        'Completed' => 'bg-green-100 text-green-800',
                                        'Failed' => 'bg-red-100 text-red-800',
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Not Taking' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-blue-100 text-blue-800'
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                                    {{ $backup->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                <div class="font-medium text-gray-900">{{ $backup->location ?: 'N/A' }}</div>
                                @if($backup->remark)
                                <div class="text-xs mt-1">{{ Str::limit($backup->remark, 50) }}</div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                No backup logs found. Start adding them!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
