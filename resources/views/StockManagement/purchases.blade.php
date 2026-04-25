@extends('layouts.app')

@section('title', 'Stock Purchases')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Stock Purchase History</h2>
        <p class="text-sm text-gray-500 mt-1">Record new purchases and track inventory inflow.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Record Purchase Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Record New Purchase</h3>
            <form action="{{ route('stock-management.purchases.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Select Brand / Model</label>
                        <select name="brand_id" required class="form-select">
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->item->name }} - {{ $brand->name }} (In Stock: {{ $brand->quantity }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" required min="1" class="form-input" placeholder="e.g. 10">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" required value="{{ date('Y-m-d') }}" class="form-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="form-input" placeholder="e.g. Amazon, Local Store">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Invoice No.</label>
                            <input type="text" name="invoice_no" class="form-input" placeholder="e.g. INV-001">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-input" placeholder="e.g. 500.00">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Remark</label>
                        <textarea name="remark" rows="2" class="form-textarea" placeholder="Any additional notes..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none transition-all active:scale-95">
                        Add to Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchase History List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date / Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor & Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $purchase->brand->item->name ?? 'Deleted' }} ({{ $purchase->brand->name ?? 'N/A' }})</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $purchase->vendor_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">Inv: {{ $purchase->invoice_no ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-bold">
                                    +{{ $purchase->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₹{{ number_format($purchase->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No purchase records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $purchases->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
