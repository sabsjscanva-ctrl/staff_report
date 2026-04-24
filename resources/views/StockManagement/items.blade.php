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
    <!-- Add Item Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Item</h3>
            <form action="{{ route('stock-management.items.store') }}" method="POST">
                @csrf
                <div class="space-y-1">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" required class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Item Name / Peripheral Name</label>
                        <input type="text" name="name" required class="form-input" placeholder="e.g. HDMI Cable 1.5m">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Initial Quantity</label>
                        <input type="number" name="quantity" required min="0" class="form-input" placeholder="e.g. 50">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Details</label>
                        <textarea name="details" rows="3" class="form-textarea" placeholder="Specifications..."></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Remark</label>
                        <textarea name="remark" rows="2" class="form-textarea" placeholder="Any notes..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all active:scale-95">
                        Add to Stock
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Item List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($items as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                @if($item->remark)
                                <div class="text-xs text-gray-500">{{ $item->remark }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->quantity < 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($item->details, 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openEditModal({{ json_encode($item) }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition-colors">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No items in stock.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
    function openEditModal(item) {
        const form = document.getElementById('editItemForm');
        form.action = `/stock-management/items/${item.id}`;
        
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_details').value = item.details || '';
        document.getElementById('edit_remark').value = item.remark || '';
        
        document.getElementById('editItemModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editItemModal').classList.add('hidden');
    }
</script>
@endpush
