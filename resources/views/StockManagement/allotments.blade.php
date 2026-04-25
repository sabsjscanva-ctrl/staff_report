@extends('layouts.app')

@section('title', 'Stock Allotment')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Stock Allotment</h2>
        <p class="text-sm text-gray-500 mt-1">Allot items to staff and track distribution history.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Allotment Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Allot Stock</h3>
            <form action="{{ route('stock-management.allotments.store') }}" method="POST">
                @csrf
                <div class="space-y-1">
                    <div class="form-group">
                        <label class="form-label">Select Staff</label>
                        <select name="staff_id" required class="form-select">
                            <option value="">-- Select Staff --</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->designation }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Select Item (Brand/Model)</label>
                        <select name="brand_id" required class="form-select">
                            <option value="">-- Select Item --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->item->name }} - {{ $brand->name }} (In Stock: {{ $brand->quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity to Allot</label>
                        <input type="number" name="quantity" required min="1" class="form-input" placeholder="e.g. 1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Allotment Date</label>
                        <input type="date" name="allotment_date" required value="{{ date('Y-m-d') }}" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Remark</label>
                        <textarea name="remark" rows="2" class="form-textarea" placeholder="Reason for allotment..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all active:scale-95">
                        Process Allotment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Allotment History -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Allotment History</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allotments as $allot)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($allot->allotment_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $allot->staff->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $allot->brand->item->name ?? 'Deleted' }} ({{ $allot->brand->name ?? 'N/A' }})</div>
                                <div class="text-xs text-gray-500">{{ $allot->brand->item->category->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $allot->quantity }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No allotment history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
