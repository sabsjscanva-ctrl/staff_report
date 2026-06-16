@extends('layouts.app')

@section('title', 'Stock Allotment')

@section('content')
<!-- Header Section -->
<div class="mb-8 relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-700 rounded-3xl p-8 text-white shadow-xl">
    <div class="relative z-10">
        <h2 class="text-3xl font-extrabold tracking-tight">Stock Allotment</h2>
        <p class="text-indigo-100 mt-2 text-sm max-w-2xl">Manage and distribute IT assets seamlessly. Allot items to staff and track distribution history in real-time with a premium experience.</p>
    </div>
    <div class="absolute -right-10 -top-10 opacity-20 transform rotate-12">
        <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl shadow-sm animate-[slideIn_0.5s_ease-out]" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm animate-[slideIn_0.5s_ease-out]" role="alert">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<div class="max-w-6xl mx-auto">
    <!-- Allotment Form -->
    <div>
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-indigo-50 rounded-xl text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">New Allotment</h3>
            </div>
            
            <form action="{{ route('stock-management.allotments.store') }}" method="POST" id="allotmentForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Staff & Items (Landscape) -->
                    <div class="lg:col-span-7 space-y-6">
                        <!-- Staff Selection -->
                        <div class="form-group">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Assign To Staff</label>
                            <select name="staff_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-3.5 transition-all duration-200">
                                <option value="">-- Choose a Staff Member --</option>
                                @foreach($staffs as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->designation }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Items Section -->
                        <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                            <div class="flex justify-between items-center mb-5">
                                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Items to Allot</h4>
                                <button type="button" onclick="addItemRow()" class="text-xs bg-white border border-gray-200 text-indigo-600 px-4 py-2 rounded-lg font-bold hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 flex items-center gap-1.5 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Add Row <span class="text-[10px] opacity-70 ml-1 font-medium bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 text-gray-500">Alt+A</span>
                                </button>
                            </div>
                            
                            <div id="item-rows" class="space-y-4">
                                <div class="item-row bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group hover:border-indigo-300 transition-all duration-200">
                                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                        <div class="md:col-span-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 block">Category</label>
                                            <select class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-2.5 transition-all duration-200 category-select" onchange="filterBrands(this)">
                                                <option value="">-- Select Category --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 block">Item Brand / Model</label>
                                            <select name="items[0][brand_id]" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-2.5 transition-all duration-200 brand-select" onchange="validateStock()" disabled>
                                                <option value="">-- Select Item --</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 block">Quantity</label>
                                            <input type="number" name="items[0][quantity]" required min="1" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-2.5 transition-all duration-200 qty-input" placeholder="Qty" oninput="validateStock()">
                                        </div>
                                    </div>
                                    <p class="stock-warning text-[10px] font-bold text-red-500 mt-2 hidden"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Details & Submit (Landscape) -->
                    <div class="lg:col-span-5 space-y-6">
                        <div class="form-group">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Allotment Type</label>
                            <div class="flex p-1.5 bg-gray-100 rounded-xl">
                                <label class="flex-1 text-center relative cursor-pointer">
                                    <input type="radio" name="allotment_type" value="Permanent" checked class="hidden peer" onchange="toggleReturnDate(false)">
                                    <span class="block py-2.5 text-xs font-bold text-gray-500 rounded-lg peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow transition-all duration-200">Permanent</span>
                                </label>
                                <label class="flex-1 text-center relative cursor-pointer">
                                    <input type="radio" name="allotment_type" value="Temporary" class="hidden peer" onchange="toggleReturnDate(true)">
                                    <span class="block py-2.5 text-xs font-bold text-gray-500 rounded-lg peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow transition-all duration-200">Temporary</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Allotment Date</label>
                                <input type="date" name="allotment_date" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-3.5 transition-all duration-200">
                            </div>

                            <div id="return_date_wrapper" class="overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0">
                                <label class="block text-xs font-bold text-orange-600 uppercase tracking-wider mb-2">Expected Return</label>
                                <input type="date" name="return_date" id="return_date_input" class="w-full bg-orange-50 border border-orange-200 text-orange-800 text-sm rounded-xl focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500 block p-3.5 transition-all duration-200">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Remark / Note</label>
                            <textarea name="remark" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 block p-3.5 transition-all duration-200" placeholder="Optional context for this allotment..."></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 py-4 px-6 rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Confirm Allotment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>
<script>
    const allBrands = {!! json_encode($brands->map(function($b) {
        return [
            'id' => $b->id,
            'category_id' => $b->item ? $b->item->category_id : null,
            'name' => ($b->item ? $b->item->name : 'N/A') . ' - ' . $b->name . ' (Stock: ' . $b->quantity . ')',
            'stock' => $b->quantity
        ];
    })->values()->all()) !!};

    let rowCount = 1;

    // Keyboard shortcut for Add Row (Alt+A)
    document.addEventListener('keydown', function(e) {
        if (e.altKey && (e.key === 'a' || e.key === 'A')) {
            e.preventDefault();
            addItemRow();
        }
    });

    function addItemRow() {
        const container = document.getElementById('item-rows');
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update names to use the new index
        const brandSelect = newRow.querySelector('.brand-select');
        brandSelect.name = `items[${rowCount}][brand_id]`;
        brandSelect.innerHTML = '<option value="">-- Select Item --</option>';
        brandSelect.disabled = true;
        brandSelect.classList.remove('border-red-500', 'focus:ring-red-500/50');
        
        const categorySelect = newRow.querySelector('.category-select');
        categorySelect.value = '';

        const inputs = newRow.querySelectorAll('input');
        inputs.forEach(input => {
            input.name = `items[${rowCount}][quantity]`;
            input.value = '';
            input.classList.remove('border-red-500', 'focus:ring-red-500/50');
        });

        // Hide warning in new row
        newRow.querySelector('.stock-warning').classList.add('hidden');
        newRow.querySelector('.stock-warning').textContent = '';

        // Add remove button if not already present
        if (!newRow.querySelector('.remove-row-btn')) {
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-row-btn absolute -top-3 -right-3 bg-white border border-gray-200 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-full p-1.5 shadow-sm transition-all duration-200 z-10';
            removeBtn.innerHTML = `
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            `;
            removeBtn.onclick = function() {
                newRow.remove();
                validateStock();
            };
            newRow.appendChild(removeBtn);
        }

        // Slight fade in animation
        newRow.style.opacity = '0';
        newRow.style.transform = 'translateY(-10px)';
        container.appendChild(newRow);
        
        // Trigger reflow
        void newRow.offsetWidth;
        
        newRow.style.transition = 'all 0.3s ease';
        newRow.style.opacity = '1';
        newRow.style.transform = 'translateY(0)';

        rowCount++;
    }

    function filterBrands(categorySelect) {
        const row = categorySelect.closest('.item-row');
        const brandSelect = row.querySelector('.brand-select');
        const categoryId = categorySelect.value;
        
        brandSelect.innerHTML = '<option value="">-- Select Item --</option>';
        brandSelect.value = '';
        
        if (categoryId) {
            brandSelect.disabled = false;
            const filteredBrands = allBrands.filter(b => b.category_id == categoryId);
            filteredBrands.forEach(b => {
                const option = document.createElement('option');
                option.value = b.id;
                option.textContent = b.name;
                option.setAttribute('data-stock', b.stock);
                brandSelect.appendChild(option);
            });
        } else {
            brandSelect.disabled = true;
        }
        
        validateStock();
    }

    function validateStock() {
        const allSelects = document.querySelectorAll('.brand-select');
        const brandTotals = {};
        
        allSelects.forEach(select => {
            const row = select.closest('.item-row');
            const qtyInput = row.querySelector('.qty-input');
            const qty = parseInt(qtyInput.value) || 0;
            const bId = select.value;
            
            if (bId) {
                if (!brandTotals[bId]) brandTotals[bId] = 0;
                brandTotals[bId] += qty;
            }
        });

        let formValid = true;

        allSelects.forEach(select => {
            const row = select.closest('.item-row');
            const qtyInput = row.querySelector('.qty-input');
            const warning = row.querySelector('.stock-warning');
            
            const selectedOption = select.options[select.selectedIndex];
            const availableStock = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
            const bId = select.value;
            
            if (bId && brandTotals[bId] > availableStock) {
                warning.textContent = `Insufficient stock! Total requested: ${brandTotals[bId]}, Max available: ${availableStock}`;
                warning.classList.remove('hidden');
                qtyInput.classList.add('border-red-500', 'bg-red-50', 'focus:ring-red-500/50');
                qtyInput.classList.remove('border-gray-200', 'bg-gray-50', 'focus:ring-indigo-500/50');
                formValid = false;
            } else {
                warning.classList.add('hidden');
                qtyInput.classList.remove('border-red-500', 'bg-red-50', 'focus:ring-red-500/50');
                qtyInput.classList.add('border-gray-200', 'bg-gray-50', 'focus:ring-indigo-500/50');
            }
        });
        
        const submitBtn = document.querySelector('#allotmentForm button[type="submit"]');
        if (!formValid) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('hover:-translate-y-0.5', 'hover:shadow-xl');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('hover:-translate-y-0.5', 'hover:shadow-xl');
        }
    }

    function toggleReturnDate(show) {
        const wrapper = document.getElementById('return_date_wrapper');
        const input = document.getElementById('return_date_input');
        if (show) {
            wrapper.style.maxHeight = '100px';
            wrapper.style.opacity = '1';
            input.required = true;
        } else {
            wrapper.style.maxHeight = '0';
            wrapper.style.opacity = '0';
            input.required = false;
            input.value = '';
        }
    }

</script>
@endpush
