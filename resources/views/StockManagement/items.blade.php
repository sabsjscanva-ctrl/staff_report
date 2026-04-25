@extends('layouts.app')

@section('title', 'Manage Stock Items')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manage Stock Items</h2>
        <p class="text-sm text-gray-500 mt-1">Add and manage items in your inventory.</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Item Type Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Item Type</h3>
            <form action="{{ route('stock-management.items.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="form-group">
                        <label class="form-label">Main Category</label>
                        <select name="category_id" required class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Item / Peripheral Name</label>
                        <input type="text" name="name" required class="form-input" placeholder="e.g. Mouse, Keyboard, HDMI Cable">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all active:scale-95">
                        Create Item Type
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Item List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 border-b bg-gray-50/50">
                <form action="{{ route('stock-management.items.index') }}" method="GET" class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Search Item</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-input !py-2" placeholder="Item name...">
                    </div>
                    <div class="w-48">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Category</label>
                        <select name="category_id" class="form-select !py-2" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-indigo-600 p-2.5 rounded-xl text-white hover:bg-indigo-700 transition-all shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($items as $item)
                <div class="p-6 hover:bg-gray-50/50 transition-colors">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded uppercase tracking-wider">{{ $item->category->name }}</span>
                            <h4 class="text-lg font-bold text-gray-900 mt-1">{{ $item->name }}</h4>
                        </div>
                        <button onclick="openAddBrandModal({{ $item->id }}, '{{ $item->name }}')" class="flex items-center gap-1.5 bg-green-50 text-green-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Brand/Model
                        </button>
                    </div>

                    <div class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Brand Name / Category</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Stock Qty</th>
                                    <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Details</th>
                                    <th class="px-4 py-2 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @forelse($item->brands as $brand)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-gray-700">{{ $brand->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $brand->quantity < 5 ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                                            {{ $brand->quantity }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ Str::limit($brand->details, 40) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <button class="text-gray-400 hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-xs text-gray-400 italic">No brands added for this item.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <p class="text-gray-400">No items found matching your criteria.</p>
                </div>
                @endforelse
            </div>
            
            @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div id="addBrandModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-xl rounded-2xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Add Brand/Model</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="modal_item_name_display">Item Type: Mouse</p>
            </div>
            <button onclick="closeAddBrandModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('stock-management.items.brands.store') }}" method="POST">
            @csrf
            <input type="hidden" name="stock_item_id" id="modal_stock_item_id">
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">Brand Name / Company</label>
                    <input type="text" name="name" required class="form-input" placeholder="e.g. Logitech, Dell, TVS">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Initial Quantity</label>
                    <input type="number" name="quantity" required min="0" class="form-input" value="0">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Details / Specs</label>
                    <textarea name="details" rows="3" class="form-textarea" placeholder="e.g. Wireless, USB-C, etc."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-8">
                <button type="button" onclick="closeAddBrandModal()" class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-indigo-600 text-sm font-bold text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                    Save Brand
                </button>
            </div>
        </form>
    </div>
</div>
            @if($items->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-xl font-bold text-gray-900">Edit Item</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="editItemForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <div class="form-group">
                    <label class="form-label">Item Name (Read-only)</label>
                    <input type="text" id="edit_name" disabled class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>

                <div class="form-group">
                    <label class="form-label">Company / Brand Name (Read-only)</label>
                    <input type="text" id="edit_brand" disabled class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Details</label>
                    <textarea name="details" id="edit_details" rows="3" class="form-textarea" placeholder="Specifications..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Remark</label>
                    <textarea name="remark" id="edit_remark" rows="2" class="form-textarea" placeholder="Any notes..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 border-t pt-4 mt-6">
                <button type="button" onclick="closeEditModal()" class="bg-white py-2 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 py-2 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none transition-all active:scale-95">
                    Update Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openAddBrandModal(itemId, itemName) {
        document.getElementById('modal_stock_item_id').value = itemId;
        document.getElementById('modal_item_name_display').innerText = 'Item Type: ' + itemName;
        document.getElementById('addBrandModal').classList.remove('hidden');
    }

    function closeAddBrandModal() {
        document.getElementById('addBrandModal').classList.add('hidden');
    }

    function openEditModal(item) {
        // ... update this if needed, or keep for later
    }
</script>
@endpush
