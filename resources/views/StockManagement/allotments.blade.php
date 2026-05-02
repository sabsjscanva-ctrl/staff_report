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
            <form action="{{ route('stock-management.allotments.store') }}" method="POST" id="allotmentForm">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label font-bold text-gray-700">Select Staff</label>
                        <select name="staff_id" required class="form-select border-2 border-indigo-100 focus:border-indigo-500 rounded-xl">
                            <option value="">-- Select Staff --</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->designation }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="border-t border-b border-gray-100 py-4">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-sm font-bold text-gray-600 uppercase tracking-wider">Items to Allot</h4>
                            <button type="button" onclick="addItemRow()" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg font-bold hover:bg-indigo-100 transition-colors flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Add More
                            </button>
                        </div>
                        
                        <div id="item-rows" class="space-y-4">
                            <div class="item-row bg-gray-50 p-4 rounded-xl border border-gray-100 relative group">
                                <div class="grid grid-cols-1 gap-3">
                                    <div class="form-group">
                                        <label class="text-xs font-bold text-gray-500 mb-1 block">Item (Brand/Model)</label>
                                        <select name="items[0][brand_id]" required class="form-select text-sm border-gray-200 rounded-lg brand-select" onchange="validateStock(this)">
                                            <option value="">-- Select Item --</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" data-stock="{{ $brand->quantity }}">
                                                    {{ $brand->item->name }} - {{ $brand->name }} (In Stock: {{ $brand->quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-xs font-bold text-gray-500 mb-1 block">Quantity</label>
                                        <input type="number" name="items[0][quantity]" required min="1" class="form-input text-sm border-gray-200 rounded-lg qty-input" placeholder="Qty" oninput="validateStock(this)">
                                        <p class="stock-warning text-[10px] font-bold text-red-500 mt-1 hidden"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-50 p-4 rounded-xl space-y-3">
                        <div class="form-group">
                            <label class="form-label text-xs font-bold text-indigo-700">Allotment Type</label>
                            <div class="flex gap-2 mt-1">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="allotment_type" value="Permanent" checked class="hidden peer" onchange="toggleReturnDate(false)">
                                    <span class="block text-center py-2 rounded-lg border-2 border-white bg-white text-xs font-bold text-gray-500 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all shadow-sm">Permanent</span>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="allotment_type" value="Temporary" class="hidden peer" onchange="toggleReturnDate(true)">
                                    <span class="block text-center py-2 rounded-lg border-2 border-white bg-white text-xs font-bold text-gray-500 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all shadow-sm">Temporary</span>
                                </label>
                            </div>
                        </div>

                        <div class="hidden" id="return_date_group">
                            <label class="text-xs font-bold text-red-600 mb-1 block">Return Date</label>
                            <input type="date" name="return_date" id="return_date_input" class="form-input text-sm border-red-100 rounded-lg">
                        </div>

                        <div class="form-group">
                            <label class="text-xs font-bold text-indigo-700 mb-1 block">Allotment Date</label>
                            <input type="date" name="allotment_date" required value="{{ date('Y-m-d') }}" class="form-input text-sm border-indigo-100 rounded-lg">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label text-sm font-bold text-gray-700">Remark / Note</label>
                        <textarea name="remark" rows="2" class="form-textarea border-gray-200 rounded-xl text-sm" placeholder="Reason for allotment..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none transition-all active:scale-95 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        Confirm Allotment
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date / Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($allotments as $allot)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($allot->allotment_date)->format('d M Y') }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $allot->allotment_type == 'Temporary' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                        {{ $allot->allotment_type }}
                                    </span>
                                    @if($allot->return_date)
                                    <span class="text-[10px] text-red-500 font-semibold">Till: {{ \Carbon\Carbon::parse($allot->return_date)->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $allot->staff->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $allot->brand->item->name ?? 'Deleted' }} ({{ $allot->brand->name ?? 'N/A' }})</div>
                                <div class="text-xs text-gray-500">{{ $allot->brand->item->category->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
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

@push('scripts')
<script>
    let rowCount = 1;

    function addItemRow() {
        const container = document.getElementById('item-rows');
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update names to use the new index
        const selects = newRow.querySelectorAll('select');
        selects.forEach(select => {
            select.name = `items[${rowCount}][brand_id]`;
            select.value = '';
            select.classList.remove('border-red-500');
        });

        const inputs = newRow.querySelectorAll('input');
        inputs.forEach(input => {
            input.name = `items[${rowCount}][quantity]`;
            input.value = '';
            input.classList.remove('border-red-500');
        });

        // Hide warning in new row
        newRow.querySelector('.stock-warning').classList.add('hidden');
        newRow.querySelector('.stock-warning').textContent = '';

        // Add remove button if not already present
        if (!newRow.querySelector('.remove-row-btn')) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-row-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-md hover:bg-red-600 transition-colors z-10';
            removeBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            `;
            removeBtn.onclick = function() {
                newRow.remove();
                checkFormValidity();
            };
            newRow.appendChild(removeBtn);
        }

        container.appendChild(newRow);
        rowCount++;
    }

    function validateStock(element) {
        const row = element.closest('.item-row');
        const brandSelect = row.querySelector('.brand-select');
        const qtyInput = row.querySelector('.qty-input');
        const warning = row.querySelector('.stock-warning');
        
        const selectedOption = brandSelect.options[brandSelect.selectedIndex];
        const availableStock = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
        const requestedQty = parseInt(qtyInput.value) || 0;

        if (requestedQty > availableStock && brandSelect.value !== '') {
            warning.textContent = `Insufficient stock! Max available: ${availableStock}`;
            warning.classList.remove('hidden');
            qtyInput.classList.add('border-red-500', 'bg-red-50');
            qtyInput.classList.remove('border-gray-200');
        } else {
            warning.classList.add('hidden');
            qtyInput.classList.remove('border-red-500', 'bg-red-50');
            qtyInput.classList.add('border-gray-200');
        }
        checkFormValidity();
    }

    function checkFormValidity() {
        const form = document.getElementById('allotmentForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const warnings = form.querySelectorAll('.stock-warning:not(.hidden)');
        
        if (warnings.length > 0) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('hover:bg-indigo-700');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('hover:bg-indigo-700');
        }
    }

    function toggleReturnDate(show) {
        const group = document.getElementById('return_date_group');
        const input = document.getElementById('return_date_input');
        if (show) {
            group.classList.remove('hidden');
            input.required = true;
        } else {
            group.classList.add('hidden');
            input.required = false;
            input.value = '';
        }
    }
</script>
@endpush
